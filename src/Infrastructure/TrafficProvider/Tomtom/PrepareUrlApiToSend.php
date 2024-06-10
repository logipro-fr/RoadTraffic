<?php

namespace RoadTraffic\Infrastructure\TrafficProvider\Tomtom;

use RoadTraffic\Application\Service\RoadTraffic\Calculate;
use RoadTraffic\Application\Service\RoadTraffic\Exceptions\BadRequestIncidentClassException;
use RoadTraffic\Application\Service\RoadTraffic\Exceptions\BadRequestTrafficClassException;
use RoadTraffic\Application\Service\RoadTraffic\RequestInterface;
use RoadTraffic\Application\Service\RoadTraffic\Responses\ResponseUrlInterface;
use RoadTraffic\Application\Service\RoadTraffic\Responses\ResponseUrlTomtom;

class PrepareUrlApiToSend
{
    public const URL_TRAFFIC = "https://api.tomtom.com/traffic/services/4/flowSegmentData/absolute/10/json?key=%s&point=%s";
    public const URL_INCIDENT = "https://api.tomtom.com/traffic/services/5/incidentDetails?key=%s&bbox=%s&fields={incidents{type,geometry{type,coordinates},properties{id,iconCategory,magnitudeOfDelay,startTime,endTime,from,to,length,roadNumbers,probabilityOfOccurrence,timeValidity,delay,events{description,code}}}}";

    public const ERROR_LOCA = "La localisation est recquise";
    public const EXCEPTION_REQUEST_TOMTOM = "Should be 'RequestTomtom' but '%s' given";
    public const EXCEPTION_MISSING_POINT =  "Latitude or longitude is missing. Latitude: %s, Longitude: %s";
    public const SEPARATOR = ',';
    private string $apiKey;
    public Calculate $classCalcul;

    public const CONCAT_POINT_FLOW = '%s' . self::SEPARATOR . '%s';

    public string $point;

    public function __construct()
    {
        $this->classCalcul = new Calculate();
        $this->apiKey = 'FakeApiKeyTomtom';
    }

    public function buildFlowTrafficUrl(RequestInterface $requestUrl): ResponseUrlInterface
    {
        $pointFlow = null;

        if ($requestUrl instanceof RequestTomtom) {
                $pointFlow = sprintf(
                    self::CONCAT_POINT_FLOW,
                    $requestUrl->geographicCoordinates->getCoordinatesLatitude(),
                    $requestUrl->geographicCoordinates->getCoordinatesLongitude()
                );

                $responseFlowUrl = sprintf(self::URL_TRAFFIC, $this->apiKey, $pointFlow);
                return new ResponseUrlTomtom($responseFlowUrl);
        }

        throw new BadRequestTrafficClassException(sprintf(
            self::EXCEPTION_REQUEST_TOMTOM,
            $requestUrl::class
        ));
    }

    public function buildIncidentTrafficUrl(RequestInterface $requestUrl): ResponseUrlInterface
    {
        $pointIncident = null;

        if ($requestUrl instanceof RequestTomtomIncident) {
                $pointIncident = $this->classCalcul->calculateStringPoint($requestUrl->bbox);
                $responseIncidentUrl = sprintf(self::URL_INCIDENT, $this->apiKey, $pointIncident);
                return new ResponseUrlTomtom($responseIncidentUrl);
        }

        throw new BadRequestIncidentClassException(sprintf(
            self::EXCEPTION_REQUEST_TOMTOM,
            $requestUrl::class
        ));
    }
}
