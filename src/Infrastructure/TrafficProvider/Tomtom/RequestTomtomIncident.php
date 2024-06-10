<?php

namespace RoadTraffic\Infrastructure\TrafficProvider\Tomtom;

use RoadTraffic\Application\Service\RoadTraffic\RequestInterface;

class RequestTomtomIncident implements RequestInterface
{
    /** @param array<float> $bbox */
    public function __construct(public readonly array $bbox)
    {
    }
}
