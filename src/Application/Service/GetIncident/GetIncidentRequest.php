<?php

namespace RoadTraffic\Application\Service\GetIncident;

class GetIncidentRequest
{
    /** @param array<float> $bbox */
    public function __construct(
        public readonly array $bbox,
        public readonly string $providerName
    ) {
    }
}

//4 point float au lieu d'un tableau
