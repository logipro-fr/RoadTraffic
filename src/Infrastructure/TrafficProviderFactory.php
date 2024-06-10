<?php

namespace RoadTraffic\Infrastructure;

use RoadTraffic\Application\Service\ProviderAbstractFactory;
use RoadTraffic\Application\Service\TrafficProviderInterface;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\RoadTrafficTomtomApi;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class TrafficProviderFactory extends ProviderAbstractFactory
{
    public function __construct(private HttpClientInterface $client)
    {
    }
    public function create(string $apiName): TrafficProviderInterface
    {
        switch ($apiName) {
            case 'TomTom':
                return new RoadTrafficTomtomApi($this->client);
        }
    }
}
