<?php

namespace RoadTraffic\Tests\Application\Service\GetFlow;

use PHPUnit\Framework\TestCase;
use RoadTraffic\Application\Service\GetFlow\GetFlow;
use RoadTraffic\Application\Service\GetFlow\GetFlowRequest;
use RoadTraffic\Application\Service\GetFlow\GetFlowResponse;
use RoadTraffic\Domain\TrafficDataId;
use RoadTraffic\Infrastructure\Persistance\TrafficDataRepositoryInMemory;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\RoadTrafficTomtomApi;
use RoadTraffic\Infrastructure\TrafficProviderFactory;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

use function Safe\file_get_contents;

class GetFlowTest extends TestCase
{
    private TrafficDataRepositoryInMemory $repository;
    private TrafficProviderFactory $factory;

    private float $lat;
    private float $lon;

    public function setUp(): void
    {
        $this->lat = 45.995;
        $this->lon = 4.721;

        $content = file_get_contents(getcwd() . "/tests/resources/tomtomApiFlowTraffic1.json");
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
        $request = new GetFlowRequest($this->lat, $this->lon, "TomTom");
        $service = new GetFlow($this->repository, $this->factory);

        $service->execute($request);
        $response1 = $service->getResponse();
        $service->execute($request);
        $response2 = $service->getResponse();

        $this->assertIsString($response1->trafficData->getId()->__toString());
        $this->assertInstanceOf(GetFlowResponse::class, $response1);
        $this->assertNotEquals($response1->trafficData->getId(), $response2->trafficData->getId());
    }

    public function testStockRepository(): void
    {
        $getTraffic = new GetFlow($this->repository, $this->factory);
        $getTraffic->execute(new GetFlowRequest($this->lat, $this->lon, "TomTom"));

        $dataId = $getTraffic->getResponse()->trafficData->getId();
        $this->assertNotEmpty($this->repository->findById(new TrafficDataId($dataId)));
    }
}
