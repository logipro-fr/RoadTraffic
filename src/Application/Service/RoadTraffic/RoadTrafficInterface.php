<?php

namespace RoadTraffic\Application\Service\RoadTraffic;

use RoadTraffic\Application\Service\RoadTraffic\RequestInterface;
use RoadTraffic\Application\Service\RoadTraffic\ResponseTrafficInterface;

interface RoadTrafficInterface
{
    public function requestFlowTraffic(RequestInterface $coordinates, string $flowTrafficUrlBuild): ResponseTrafficInterface;
    public function requestIncidentTraffic(RequestInterface $requestIncident, string $incidentTrafficUrlBuild): ResponseTrafficInterface;
}
