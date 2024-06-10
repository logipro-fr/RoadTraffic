<?php

namespace RoadTraffic\Domain;

use RoadTraffic\Domain\Exceptions\BadLongitudeValue;

class Longitude
{
    public function __construct(private float $longitude)
    {
        if ($longitude < -180 || $longitude > 180) {
            throw new BadLongitudeValue("Longitude must be between -180 and 180 degrees. $longitude given.");
        }
    }
    public function getLongitude(): float
    {
        return $this->longitude;
    }
}
