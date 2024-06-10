<?php

namespace RoadTraffic\Tests\Domain;

use PHPUnit\Framework\TestCase;
use RoadTraffic\Domain\TrafficData;
use RoadTraffic\Domain\TrafficDataId;
use RoadTraffic\Domain\TrafficDataJson;
use Safe\DateTimeImmutable;

class TrafficDataTest extends TestCase
{
    public function testCreate(): void
    {
        $dataId = new TrafficDataId("1");
        $trafficData = new TrafficData($dataId, new TrafficDataJson('{"personnes":["Alice","Bob","Charlie"]}'));
        $this->assertInstanceOf(TrafficDataId::class, $trafficData->getId());
        $this->assertInstanceOf(DateTimeImmutable::class, $trafficData->getCreatedAt());
    }

    public function testDataIdNull(): void
    {
        $dataId = new TrafficDataId();
        $trafficData = new TrafficData($dataId, new TrafficDataJson('{"personnes":["Alice","Bob","Charlie"]}'));
        $this->assertNotNull($trafficData->getId());
    }
}
