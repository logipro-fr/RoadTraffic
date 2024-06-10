<?php

namespace RoadTraffic\Domain;

use RoadTraffic\Domain\Exceptions\WrongJsonSynthaxe;

class TrafficDataJson
{
    public function __construct(private string $jsonTrafficData)
    {
        if (!json_validate($jsonTrafficData)) {
            throw new WrongJsonSynthaxe("Wrong json synthaxe !");
        }
    }

    public function __toString(): string
    {
        return strval($this->jsonTrafficData);
    }

    public function equals(string $jsonToCompare): bool
    {
        if ($this->jsonTrafficData === $jsonToCompare) {
            return true;
        }
        return false;
    }
}
