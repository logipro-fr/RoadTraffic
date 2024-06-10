<?php

namespace RoadTraffic\Infrastructure\TrafficProvider\Tomtom;

use RoadTraffic\Application\Service\RoadTraffic\ResponseTrafficInterface;

class ResponseTrafficTomtom implements ResponseTrafficInterface
{
    public function __construct(
        public readonly string $jsonData
    ) {
    }
}
