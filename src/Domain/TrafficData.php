<?php

namespace RoadTraffic\Domain;

use Safe\DateTimeImmutable;

class TrafficData
{
    private DateTimeImmutable $createdAt;

    public function __construct(private TrafficDataId $dataId, private TrafficDataJson $trafficData)
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): TrafficDataId
    {
        return $this->dataId;
    }

    public function getTrafficData(): TrafficDataJson
    {
        return $this->trafficData;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
