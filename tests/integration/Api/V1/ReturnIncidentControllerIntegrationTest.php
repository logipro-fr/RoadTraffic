<?php

namespace RoadTraffic\Integration\Infrastructure\Api\V1;

use RoadTraffic\Infrastructure\Api\V1\ReturnIncidentController;
use RoadTraffic\Infrastructure\Persistance\TrafficDataRepositoryInMemory;
use RoadTraffic\Infrastructure\TrafficProviderFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpFoundation\Request;

use function Safe\json_encode;

class ReturnIncidentControllerIntegrationTest extends WebTestCase
{
    private KernelBrowser $client;
    public function testIncidentControllerRouting(): void
    {
        $this->client = static::createClient();
        $this->client->request(
            "POST",
            "/api/v1/incident/Return",
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "bbox" => [4.8187,45.7417,4.8657,45.7777],
                "apiName" => "TomTom",
            ])
        );
        /** @var string */
        $responseContent = $this->client->getResponse()->getContent();
        $this->assertResponseIsSuccessful();
    }

    public function testIncidentControllerExecute(): void
    {
        $repository = new TrafficDataRepositoryInMemory();
        $factory = new TrafficProviderFactory(new CurlHttpClient());
        $controller = new ReturnIncidentController($repository, $factory);
        $request = Request::create(
            "api/v1/incident/Return",
            "POST",
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                "bbox" => [4.8187,45.7417,4.8657,45.7777],
                "apiName" => "TomTom"
            ])
        );


        $response = $controller->execute($request);
        /** @var string */
        $responseContent = $response->getContent();
        $responseStatus = $response->getStatusCode();

        $this->assertStringContainsString('"success":true', $responseContent);
        $this->assertEquals(201, $responseStatus);
        $this->assertStringContainsString('"IncidentId":"trd_', $responseContent);
        $this->assertStringContainsString('"message":"', $responseContent);
    }
}
