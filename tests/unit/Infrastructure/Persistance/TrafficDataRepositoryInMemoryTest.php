<?php

namespace RoadTraffic\Tests\Infrastructure\Persistance;

use PHPUnit\Framework\TestCase;
use RoadTraffic\Domain\Exceptions\WrongJsonSynthaxe;
use RoadTraffic\Domain\TrafficData;
use RoadTraffic\Domain\TrafficDataId;
use RoadTraffic\Domain\TrafficDataJson;
use RoadTraffic\Infrastructure\Persistance\TrafficDataRepositoryInMemory;

class TrafficDataRepositoryInMemoryTest extends TestCase
{
    private TrafficDataRepositoryInMemory $repository;

    protected function setUp(): void
    {
        $this->repository = new TrafficDataRepositoryInMemory();
    }

    public function testAddTrafficData(): void
    {
        $dataId = new TrafficDataId();
        $jsonData = new TrafficDataJson('{"personnes":["Alice","Bob","Charlie"]}');
        $trafficData = new TrafficData($dataId, $jsonData);
        $this->repository->add($trafficData);
        $this->assertNotEmpty($this->repository->findById($dataId));
    }
}
