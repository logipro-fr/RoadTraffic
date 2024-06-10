<?php

namespace RoadTraffic\Domain;

class TrafficDataId
{
    public function __construct(private ?string $idTrafficData = null)
    {
        if (!isset($idTrafficData)) {
            $this->idTrafficData = uniqid("trd_");
        }
    }

    public function __toString(): string
    {
        return strval($this->idTrafficData);
    }

    public function equals(string $idToCompare): bool
    {
        if ($this->idTrafficData === $idToCompare) {
            return true;
        }
        return false;
    }
}
