<?PHP

namespace RoadTraffic\Tests;

use PHPUnit\Framework\TestCase;
use RoadTraffic\Application\Service\RoadTraffic\Responses\ResponseUrlInterface;
use RoadTraffic\Domain\GeographicCoordinates;
use RoadTraffic\Domain\Latitude;
use RoadTraffic\Domain\Longitude;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\RequestTomtom;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\ResponseTrafficTomtom;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\RoadTrafficTomtomApi;
use Symfony\Component\HttpClient\HttpClient;

class RoadtrafficInteTest extends TestCase
{
    public const LONGITUDE = 4.8422;
    public const LATITUDE = 45.7597;

    public ResponseUrlInterface $prepareUrlApiToSend;

    public function testIntegrationTrafficFlow(): void
    {
        $client = HttpClient::create();
        $trafficApi = new RoadTrafficTomtomApi($client);
        $request = new RequestTomtom(new GeographicCoordinates(new Latitude(self::LATITUDE), new Longitude(self::LONGITUDE)));
        $response = $trafficApi->requestFlowTraffic($request);

        $this->assertInstanceOf(ResponseTrafficTomtom::class, $response);
        $this->assertIsString($response->jsonData);
    }
}
