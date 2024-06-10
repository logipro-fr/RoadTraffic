<?php

namespace RoadTraffic\Tests\Domain;

use PHPUnit\Framework\TestCase;
use RoadTraffic\Domain\GeographicCoordinates;
use RoadTraffic\Domain\Latitude;
use RoadTraffic\Domain\Longitude;

class GeographicCoordinatesTest extends TestCase
{
    public function testGetGeographicCoordinates(): void
    {
        $geographicCoordinates = new GeographicCoordinates(new Latitude(3.56), new Longitude(41.21));
        $this->assertEquals(3.56, $geographicCoordinates->getCoordinatesLatitude());
        $this->assertEquals(41.21, $geographicCoordinates->getCoordinatesLongitude());
    }
}
