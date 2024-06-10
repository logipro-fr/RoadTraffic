<?php

namespace RoadTraffic\Infrastructure\TrafficProvider\Tomtom;

use RoadTraffic\Application\Service\RoadTraffic\RequestInterface;
use RoadTraffic\Domain\GeographicCoordinates;

class RequestTomtom implements RequestInterface
{
    public function __construct(public readonly GeographicCoordinates $geographicCoordinates)
    {
    }
}
