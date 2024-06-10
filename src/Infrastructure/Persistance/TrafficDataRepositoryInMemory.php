<?php

namespace RoadTraffic\Infrastructure\Persistance;

use RoadTraffic\Domain\TrafficDataRepositoryInterface;
use RoadTraffic\Domain\TrafficData;
use RoadTraffic\Domain\TrafficDataId;
use RoadTraffic\Domain\TrafficDataJson;

class TrafficDataRepositoryInMemory implements TrafficDataRepositoryInterface
{
    /** @var array<TrafficDataJson> */
    private array $arrayTrafficDatas = [];

    public function add(TrafficData $trafficData): void
    {
        $this->arrayTrafficDatas[$trafficData->getId()->__toString()] = $trafficData->getTrafficData();
    }

    public function findById(TrafficDataId $id): TrafficDataJson
    {
        return $this->arrayTrafficDatas[$id->__toString()];
    }
}
