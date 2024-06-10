<?php

namespace RoadTraffic\Application\Service\GetIncident;

use RoadTraffic\Domain\TrafficData;

class GetIncidentResponse
{
    public function __construct(public readonly TrafficData $trafficData)
    {
    }
}
