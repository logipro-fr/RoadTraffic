<?php

namespace RoadTraffic\Tests\Domain;

use PHPUnit\Framework\TestCase;
use RoadTraffic\Domain\Exceptions\BadLatitudeValue;
use RoadTraffic\Domain\Latitude;

class LatitudeTest extends TestCase
{
    private const LATITUDE = 3.45;
    private const BAD_LATITUDE = 92.23;

    public function testGetLatitude(): void
    {
        $latitude = new Latitude(self::LATITUDE);
        $this->assertEquals(3.45, $latitude->getLatitude());
    }

    public function testLatitudeCorrect(): void
    {
        $latitude = new Latitude(90);
        $this->assertEquals(90, $latitude->getLatitude());
    }

    public function testLatitudeCorrect2(): void
    {
        $latitude = new Latitude(-90);
        $this->assertEquals(-90, $latitude->getLatitude());
    }

    public function testExceptionBadValue(): void
    {
        $this->expectException(BadLatitudeValue::class);
        $this->expectExceptionMessage("Latitude must be between -90 and 90 degrees. 92.23 given.");

        new Latitude(self::BAD_LATITUDE);
    }
}
