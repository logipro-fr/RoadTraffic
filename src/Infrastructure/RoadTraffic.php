<?php

namespace RoadTraffic\Infrastructure;

use RoadTraffic\Application\Service\RoadTraffic\RequestInterface;
use RoadTraffic\Application\Service\RoadTraffic\Responses\ResponseUrlInterface;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\PrepareUrlApiToSend;

class RoadTraffic
{
    private PrepareUrlApiToSend $urlApi;

    public function __construct()
    {
        $this->urlApi = new PrepareUrlApiToSend();
    }

    public function executeRequestFlowTrafficUrlApi(RequestInterface $requestUrl): ResponseUrlInterface
    {
        return $this->urlApi->buildFlowTrafficUrl(($requestUrl));
    }

    public function executeRequestIncidentTrafficUrlApi(RequestInterface $requestUrl): ResponseUrlInterface
    {
        return $this->urlApi->buildIncidentTrafficUrl(($requestUrl));
    }
}
