<?php

namespace RoadTraffic\Integration\Infrastructure\Api\V1;

use PHPUnit\Framework\TestCase;
use RoadTraffic\Infrastructure\Api\V1\ReturnFlowController;
use RoadTraffic\Infrastructure\Persistance\TrafficDataRepositoryInMemory;
use RoadTraffic\Infrastructure\TrafficProviderFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\HttpFoundation\Request;

class ReturnFlowControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    public function testFlowControllerRouting(): void
    {
        $this->client = static::createClient();
        $this->client->request(
            "GET",
            "/api/v1/flow/Return",
            [
                "latitude" => 45.995,
                "longitude" => 4.721,
                "apiName" => "TomTom",
            ]
        );
        /** @var string */
        $responseContent = $this->client->getResponse()->getContent();
        $this->assertResponseIsSuccessful();
    }

    public function testFlowControllerExecute(): void
    {
        $content = file_get_contents(getcwd() . "/tests/resources/tomtomApiIncidentTraffic1.json");
        $responses = [new MockResponse(strval($content))];
        $client = new MockHttpClient($responses);
        
        $repository = new TrafficDataRepositoryInMemory();
        $factory = new TrafficProviderFactory($client);
        $controller = new ReturnFlowController($repository, $factory);
        $request = Request::create(
            "api/v1/flow/Return",
            "GET",
            [
                "latitude" => 45.995,
                "longitude" => 4.721,
                "apiName" => "TomTom"
            ]
        );


        $response = $controller->execute($request);
        /** @var string */
        $responseContent = $response->getContent();
        $responseStatus = $response->getStatusCode();

        $this->assertStringContainsString('"success":true', $responseContent);
        $this->assertEquals(201, $responseStatus);
        $this->assertStringContainsString('"FlowId":"trd_', $responseContent);
        $this->assertStringContainsString('"message":"', $responseContent);
    }
}
