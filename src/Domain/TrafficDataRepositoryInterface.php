<?php

namespace RoadTraffic\Domain;

interface TrafficDataRepositoryInterface
{
    public function add(TrafficData $trafficData): void;
    public function findById(TrafficDataId $id): TrafficDataJson;
    //remove
    //edit
}
