<?php

namespace RoadTraffic\Tests\Domain;

use PHPUnit\Framework\TestCase;
use RoadTraffic\Domain\Exceptions\WrongJsonExceptions;
use RoadTraffic\Domain\Exceptions\WrongJsonSynthaxe;
use RoadTraffic\Domain\TrafficDataJson;

class TrafficDataJsonTest extends TestCase
{
    public function testCreateWithData(): void
    {
        $dataJson = new TrafficDataJson('{"personnes":["Alice","Bob","Charlie"]}');
        $this->assertEquals('{"personnes":["Alice","Bob","Charlie"]}', $dataJson);
    }

    public function testCreateWithoutData(): void
    {
        $this->expectException(WrongJsonSynthaxe::class);
        new TrafficDataJson("");
    }

    public function testEqualData(): void
    {
        $dataJson = new TrafficDataJson('{"personnes":["Alice","Bob","Charlie"]}');
        $dataJson1 = new TrafficDataJson('{"personnes":["Alice","Bob","Charlie"]}');

        $this->assertTrue($dataJson->equals($dataJson1));
    }

    public function testNotEqualData(): void
    {
        $dataJson = new TrafficDataJson('{"personnes":["Alice","Bob","Charlie"]}');
        $dataJson1 = new TrafficDataJson('{"personnes":["Mathis","Jeremy","Romain"]}');
        $this->assertFalse($dataJson->equals($dataJson1));
    }
}
