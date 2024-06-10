<?php

namespace RoadTraffic\Application\Service;

abstract class ProviderAbstractFactory
{
    abstract function create(string $apiName): TrafficProviderInterface;
}
