<?PHP

namespace RoadTraffic\Infrastructure\TrafficProvider\Tomtom;

use RoadTraffic\Application\Service\RoadTraffic\Calculate;
use RoadTraffic\Application\Service\RoadTraffic\Exceptions\BadRequestIncidentClassException;
use RoadTraffic\Application\Service\RoadTraffic\Exceptions\BadRequestTrafficClassException;
use RoadTraffic\Application\Service\RoadTraffic\RequestInterface;
use RoadTraffic\Application\Service\RoadTraffic\ResponseTrafficInterface;
use RoadTraffic\Application\Service\TrafficProviderInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

use function Safe\file_get_contents;

class RoadTrafficTomtomApi implements TrafficProviderInterface
{
    public const URL_TRAFFIC = "https://api.tomtom.com/traffic/services/4/flowSegmentData/absolute/10/json?key=%s&point=%s";
    public const URL_INCIDENT = "https://api.tomtom.com/traffic/services/5/incidentDetails?key=%s&bbox=%s&fields={incidents{type,geometry{type,coordinates},properties{id,iconCategory,magnitudeOfDelay,startTime,endTime,from,to,length,roadNumbers,probabilityOfOccurrence,timeValidity,delay,events{description,code}}}}";

    public const ERROR_LOCA = "La localisation est recquise";
    private const EXCEPTION_REQUEST_TOMTOM = "Should be 'RequestTomtom' but '%s' given";
    public const EXCEPTION_MISSING_POINT =  "Latitude or longitude is missing. Latitude: %s, Longitude: %s";
    private const SEPARATOR = ',';
    public Calculate $classCalcul;

    public const CONCAT_POINT_FLOW = '%s' . self::SEPARATOR . '%s';

    public string $point;

    public function __construct(private HttpClientInterface $client)
    {
        $this->classCalcul = new Calculate();
    }

    public function requestFlowTraffic(RequestInterface $requestTraffic): ResponseTrafficInterface
    {

        $buildFlowTrafficUrl = (new PrepareUrlApiToSend())->buildFlowTrafficUrl($requestTraffic)->__toString();
        if ($requestTraffic instanceof RequestTomtom) {
            $response = $this->client->request("GET", $buildFlowTrafficUrl);
            $jsonResponse = $response->getContent();
            return new ResponseTrafficTomtom($jsonResponse);
        }

        throw new BadRequestTrafficClassException(sprintf(
            self::EXCEPTION_REQUEST_TOMTOM,
            $requestTraffic::class
        ));
    }

    public function requestIncidentTraffic(RequestInterface $requestIncident): ResponseTrafficTomtom
    {

        $buildIncidentTrafficUrl = (new PrepareUrlApiToSend())->buildIncidentTrafficUrl($requestIncident)->__toString();

        if ($requestIncident instanceof RequestTomtomIncident) {
            $response = $this->client->request("GET", $buildIncidentTrafficUrl);
            $jsonResponse = $response->getContent();
            return new ResponseTrafficTomtom($jsonResponse);
        }

        throw new BadRequestIncidentClassException(
            sprintf(self::EXCEPTION_REQUEST_TOMTOM, $requestIncident::class)
        );
    }
}
