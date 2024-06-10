<?php

namespace RoadTraffic\Tests\Application\Service\GetIncident;

use PHPUnit\Framework\TestCase;
use RoadTraffic\Application\Service\GetIncident\GetIncident;
use RoadTraffic\Application\Service\GetIncident\GetIncidentRequest;
use RoadTraffic\Application\Service\GetIncident\GetIncidentResponse;
use RoadTraffic\Domain\TrafficDataId;
use RoadTraffic\Infrastructure\Persistance\TrafficDataRepositoryInMemory;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\RoadTrafficTomtomApi;
use RoadTraffic\Infrastructure\TrafficProviderFactory;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

use function Safe\file_get_contents;
use function Safe\json_decode;

class GetIncidentTest extends TestCase
{
    private TrafficDataRepositoryInMemory $repository;
    private TrafficProviderFactory $factory;


    public function setUp(): void
    {
        $content = file_get_contents(getcwd() . "/tests/resources/tomtomApiIncidentTraffic1.json");
        $responses = [
            new MockResponse($content),
            new MockResponse($content),
            new MockResponse($content),
            new MockResponse($content)
        ];
        $clientMock = new MockHttpClient($responses);

        $this->repository = new TrafficDataRepositoryInMemory();
        $this->factory = new TrafficProviderFactory($clientMock);
    }

    public function testGetTrafficWhenIdInequal(): void
    {
        $request = new GetIncidentRequest([45.995,4.721,45,4.5], "TomTom");
        $service = new GetIncident($this->repository, $this->factory);

        $service->execute($request);
        $response1 = $service->getResponse();
        $service->execute($request);
        $response2 = $service->getResponse();

        $this->assertIsString($response1->trafficData->getId()->__toString());
        $this->assertInstanceOf(GetIncidentResponse::class, $response1);
        $this->assertNotEquals($response1->trafficData->getId(), $response2->trafficData->getId());
    }

    public function testStockRepository(): void
    {
        $getTraffic = new GetIncident($this->repository, $this->factory);
        $getTraffic->execute(new GetIncidentRequest([45.995,4.721,45,4.5], "TomTom"));

        $dataId = $getTraffic->getResponse()->trafficData->getId();
        $this->assertNotEmpty($this->repository->findById(new TrafficDataId($dataId)));
    }
}
