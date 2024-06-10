<?php

namespace RoadTraffic\Domain;

use RoadTraffic\Domain\Exceptions\BadLatitudeValue;

class Latitude
{
    public function __construct(private float $latitude)
    {
        if ($latitude < -90 || $latitude > 90) {
            throw new BadLatitudeValue("Latitude must be between -90 and 90 degrees. $latitude given.");
        }
    }
    public function getLatitude(): float
    {
        return $this->latitude;
    }
}
