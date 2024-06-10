<?php

namespace RoadTraffic\Application\Service\GetFlow;

class GetFlowRequest
{
    public function __construct(
        public readonly float $latitude,
        public readonly float $longitude,
        public readonly string $providerName
    ) {
    }
}
