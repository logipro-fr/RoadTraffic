<?php

namespace RoadTraffic\Application\Service;

use RoadTraffic\Application\Service\RoadTraffic\RequestInterface;
use RoadTraffic\Application\Service\RoadTraffic\ResponseTrafficInterface;

interface TrafficProviderInterface
{
    public function requestFlowTraffic(RequestInterface $coordinates): ResponseTrafficInterface;
    public function requestIncidentTraffic(RequestInterface $requestIncident): ResponseTrafficInterface;
}
