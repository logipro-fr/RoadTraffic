<?php

namespace RoadTraffic\Application\Service\GetFlow;

use RoadTraffic\Domain\TrafficData;

class GetFlowResponse
{
    public function __construct(public readonly TrafficData $trafficData)
    {
    }
}
