<?PHP

namespace RoadTraffic\Tests\Infrastructure\TrafficProvider\Tomtom;

use PHPUnit\Framework\TestCase;
use RoadTraffic\Application\Service\RoadTraffic\Exceptions\BadRequestIncidentClassException;
use RoadTraffic\Application\Service\RoadTraffic\Exceptions\BadRequestTrafficClassException;
use RoadTraffic\Application\Service\RoadTraffic\Responses\ResponseUrlInterface;
use RoadTraffic\Domain\GeographicCoordinates;
use RoadTraffic\Domain\Latitude;
use RoadTraffic\Domain\Longitude;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\PrepareUrlApiToSend;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\RequestTomtom;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\RequestTomtomIncident;

use function Safe\file_get_contents;
use function Safe\json_decode;

class PrepareUrlToSendTest extends TestCase
{

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

    public function testTrafficFlowPoint(): void
    {
        $contentHotPoint = file_get_contents(__DIR__ . "/../../../../resources/hot_point_6_departments.json");

        $contentDecode = json_decode($contentHotPoint);
        $this->assertIsArray($contentDecode);

        $this->assertEquals("https://api.tomtom.com/traffic/services/5/incidentDetails?key=FakeApiKeyTomtom&bbox=4.721,45.995,4.721,45.995&fields={incidents{type,geometry{type,coordinates},properties{id,iconCategory,magnitudeOfDelay,startTime,endTime,from,to,length,roadNumbers,probabilityOfOccurrence,timeValidity,delay,events{description,code}}}}", $this->buildIncidentTrafficUrl->__toString());
        $this->assertEquals("https://api.tomtom.com/traffic/services/4/flowSegmentData/absolute/10/json?key=FakeApiKeyTomtom&point=45.995,4.721", $this->buildFlowTrafficUrl->__toString());
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
