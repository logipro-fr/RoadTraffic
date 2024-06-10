<?php

namespace RoadTraffic\Tests\Domain;

use PHPUnit\Framework\TestCase;
use RoadTraffic\Domain\TrafficDataId;

class TrafficDataIdTest extends TestCase
{
    public function testCreate(): void
    {
        $id = new TrafficDataId("unId");
        $this->assertEquals("unId", $id);
    }

    public function testEqualId(): void
    {
        $id = new TrafficDataId("idUn");
        $id2 = new TrafficDataId("idUn");

        $this->assertTrue($id->equals($id2));
    }

    public function testNotEqualId(): void
    {
        $id = new TrafficDataId("idUn");
        $id2 = new TrafficDataId("idDeux");

        $this->assertFalse($id->equals($id2));
    }

    public function testNotEqualIdEmpty(): void
    {
        $id = new TrafficDataId();
        //var_dump($id);
        $id2 = new TrafficDataId();
        //var_dump($id2);
        $this->assertFalse($id->equals($id2));
    }

    public function testIdFormat(): void
    {
        $id = new TrafficDataId();
        $this->assertStringStartsWith("trd_", $id->__toString());
    }
}
