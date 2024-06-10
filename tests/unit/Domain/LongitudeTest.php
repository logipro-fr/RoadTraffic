<?php

namespace RoadTraffic\Tests\Domain;

use PHPUnit\Framework\TestCase;
use RoadTraffic\Domain\Exceptions\BadLongitudeValue;
use RoadTraffic\Domain\Longitude;

class LongitudeTest extends TestCase
{
    private const LONGITUDE = 42.5;
    private const BAD_LONGITUDE = 1042.5;

    public function testGetLongitude(): void
    {
        $longitude = new Longitude(self::LONGITUDE);
        $this->assertEquals(42.5, $longitude->getLongitude());
    }

    public function testLongitudeCorrect(): void
    {
        $longitude = new Longitude(180);
        $this->assertEquals(180, $longitude->getLongitude());
    }

    public function testLongitudeCorrect2(): void
    {
        $longitude = new Longitude(-180);
        $this->assertEquals(-180, $longitude->getLongitude());
    }

    public function testExceptionBadValue(): void
    {
        $this->expectException(BadLongitudeValue::class);
        $this->expectExceptionMessage("Longitude must be between -180 and 180 degrees. 1042.5 given.");

        new Longitude(self::BAD_LONGITUDE);
    }
}
