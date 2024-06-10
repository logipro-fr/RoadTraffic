<?PHP

namespace RoadTraffic\Tests\Infrastructure\TrafficProvider\Tomtom;

use PHPUnit\Framework\TestCase;
use RoadTraffic\Application\Service\RoadTraffic\Calculate;
use RoadTraffic\Application\Service\RoadTraffic\Exceptions\BadRequestIncidentClassException;
use RoadTraffic\Application\Service\RoadTraffic\Exceptions\BadRequestTrafficClassException;
use RoadTraffic\Application\Service\RoadTraffic\Responses\ResponseUrlInterface;
use RoadTraffic\Domain\GeographicCoordinates;
use RoadTraffic\Domain\Latitude;
use RoadTraffic\Domain\Longitude;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\PrepareUrlApiToSend;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\RequestTomtom;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\RequestTomtomIncident;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\ResponseTrafficTomtom;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\RoadTrafficTomtomApi;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

use function Safe\file_get_contents;
use function Safe\json_decode;

class RoadTrafficTomtomApiTest extends TestCase
{
    private const LONGITUDE = 4.8422;
    private const LATITUDE = 45.7597;
    private const DISTANCE_LAT = 0.018;
    private const DISTANCE_LONG = 0.0235;

    public const ERROR_RESPONSE = "La localisation est recquise";

    private RequestTomtom $request;
    private RequestTomtomIncident $requestIncident;
    private ResponseUrlInterface $buildFlowTrafficUrl;
    private ResponseUrlInterface $buildIncidentTrafficUrl;

    protected function setUp(): void
    {
        $this->request = new RequestTomtom(new GeographicCoordinates(new Latitude(45.995), new Longitude(4.721)));
        $this->requestIncident = new RequestTomtomIncident([4.721,45.995,4.721,45.995]);

        $this->buildIncidentTrafficUrl = (new PrepareUrlApiToSend())->buildIncidentTrafficUrl($this->requestIncident);
        $this->buildFlowTrafficUrl = (new PrepareUrlApiToSend())->buildFlowTrafficUrl($this->request);
    }

    public function testGetTrafficfromGPS(): void
    {
        $clientMock = $this->createMockHttpClient(__DIR__ . "/../../../../resources/tomtomApiFlowTraffic1.json");

        $trafficApi = new RoadTrafficTomtomApi($clientMock);
        $response = $trafficApi->requestFlowTraffic($this->request);

        $this->assertInstanceOf(ResponseTrafficTomtom::class, $response);
        $this->assertIsString($response->jsonData);
    }

    private function createMockHttpClient(string $pathRequest): MockHttpClient
    {
        $content = file_get_contents($pathRequest);
        $responses = [new MockResponse($content)];
        $client = new MockHttpClient($responses);

        return $client;
    }

    public function testGetTrafficfromGPS2(): void
    {
        $clientMock = $this->createMockHttpClient(__DIR__ . "/../../../../resources/tomtomApiFlowTraffic2.json");

        $trafficApi = new RoadTrafficTomtomApi($clientMock);
        $response = $trafficApi->requestFlowTraffic($this->request);

        $this->assertInstanceOf(ResponseTrafficTomtom::class, $response);

        $this->assertIsString($response->jsonData);
    }

    public function testGetIncidentTrafficfromGPS(): void
    {
        $clientMockIncident = $this->createMockHttpClient(__DIR__ . "/../../../../resources/tomtomApiIncidentTraffic1.json");
        $incidentApi = new RoadTrafficTomtomApi($clientMockIncident);
        $response = $incidentApi->requestIncidentTraffic($this->requestIncident);

        /** @var \stdClass $incident */
        $incident = json_decode($response->jsonData);
        $fsd = $incident->incidents[0];

        $this->assertInstanceOf(ResponseTrafficTomtom::class, $response);
        $this->assertIsString($response->jsonData);
        $this->assertEquals('Feature', $fsd->type);
        $this->assertEquals('793bfb23752c4ac3a903fe0f18c2975f', $fsd->properties->id);
    }

    public function testCalculateArea(): void
    {
        $classCalcul = new Calculate();

        $this->assertEquals(
            [4.8187,45.7417,4.8657,45.7777],
            $classCalcul->calculateArea(
                new Latitude(self::LATITUDE),
                new Longitude(self::LONGITUDE),
                self::DISTANCE_LAT,
                self::DISTANCE_LONG
            )
        );
    }

    public function testReturnStringPoint(): void
    {
        $classCalcul = new Calculate();

        $this->assertEquals(
            "4.8187,45.7417,4.8657,45.7777",
            $classCalcul->calculateStringPoint([4.8187,45.7417,4.8657,45.7777])
        );
    }

    public function testTrafficFlowPoint(): void
    {
        $contentHotPoint = file_get_contents(__DIR__ . "/../../../../resources/hot_point_6_departments.json");

        $contentDecode = json_decode($contentHotPoint);
        $this->assertIsArray($contentDecode);

        $this->assertEquals("https://api.tomtom.com/traffic/services/5/incidentDetails?key=FakeApiKeyTomtom&bbox=4.721,45.995,4.721,45.995&fields={incidents{type,geometry{type,coordinates},properties{id,iconCategory,magnitudeOfDelay,startTime,endTime,from,to,length,roadNumbers,probabilityOfOccurrence,timeValidity,delay,events{description,code}}}}", $this->buildIncidentTrafficUrl->__toString());
        $this->assertEquals("https://api.tomtom.com/traffic/services/4/flowSegmentData/absolute/10/json?key=FakeApiKeyTomtom&point=45.995,4.721", $this->buildFlowTrafficUrl->__toString());
    }

    public function testShouldBeRequestTrafficTomtom(): void
    {
        $this->expectException(BadRequestTrafficClassException::class);
        $this->expectExceptionMessage(
            "Should be 'RequestTomtom' but 'RoadTraffic\Tests\Infrastructure\TrafficProvider\Tomtom\RequestTrafficTomtomFake' given"
        );
        $request = new RequestTrafficTomtomFake();
        $clientMock = $this->createMockHttpClient(getcwd() . "/tests/resources/tomtomApiFlowTraffic2.json");

        $trafficApi = new RoadTrafficTomtomApi($clientMock);
        $trafficApi->requestFlowTraffic($request);
    }

    public function testShouldBeRequestIncidentTomtom(): void
    {
        $this->expectException(BadRequestIncidentClassException::class);
        $this->expectExceptionMessage(
            "Should be 'RequestTomtom' but 'RoadTraffic\Tests\Infrastructure\TrafficProvider\Tomtom\RequestIncidentTomtomFake' given"
        );
        $request = new RequestIncidentTomtomFake();
        $clientMockIncident = $this->createMockHttpClient(getcwd() . "/tests/resources/tomtomApiIncidentTraffic1.json");

        $incidentApi = new RoadTrafficTomtomApi($clientMockIncident);
        $incidentApi->requestIncidentTraffic($request);
    }

    public function testShouldBeRequestIncidentUrlTomtom(): void
    {
        $this->expectException(BadRequestIncidentClassException::class);

        $request = new RequestIncidentTomtomFake();
        $classBuild = new PrepareUrlApiToSend();
        $classBuild->buildIncidentTrafficUrl($request);
    }

    public function testShouldBeRequestFlowUrlTomtom(): void
    {
        $this->expectException(BadRequestTrafficClassException::class);

        $request = new RequestTrafficTomtomFake();
        $classBuild = new PrepareUrlApiToSend();
        $classBuild->buildFlowTrafficUrl($request);
    }
}
