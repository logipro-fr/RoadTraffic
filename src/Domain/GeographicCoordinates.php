<?php

namespace RoadTraffic\Domain;

use RoadTraffic\Domain\Latitude;
use RoadTraffic\Domain\Longitude;

class GeographicCoordinates
{
    public function __construct(private Latitude $latitude, private Longitude $longitude)
    {
    }

    public function getCoordinatesLongitude(): float
    {
        return ($this->longitude->getLongitude());
    }

    public function getCoordinatesLatitude(): float
    {
        return ($this->latitude->getLatitude());
    }
}
