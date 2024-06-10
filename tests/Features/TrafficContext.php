<?php

namespace Features;

use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;
use RoadTraffic\Application\Service\GetFlow\GetFlow;
use RoadTraffic\Application\Service\GetFlow\GetFlowRequest;
use RoadTraffic\Application\Service\GetFlow\GetFlowResponse;
use RoadTraffic\Infrastructure\Persistance\TrafficDataRepositoryInMemory;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\RoadTrafficTomtomApi;
use RoadTraffic\Infrastructure\TrafficProviderFactory;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

use function Safe\file_get_contents;
use function Safe\json_decode;

/**
 * Defines application features from the specific context.
 */
class TrafficContext implements Context
{
    public RoadTrafficTomtomApi $trafficProvider;
    public TrafficProviderFactory $trafficProviderFactory;
    public GetFlowResponse $response;

    public string $apiName;

    /**
     * @Given I select the traffic API provider :apiName for traffic
     */
    public function iSelectTheTrafficApiProvider(string $apiName): void
    {
        $content = file_get_contents(getcwd() . "/tests/resources/tomtomApiFlowTraffic2.json");
        $responses = [new MockResponse($content)];
        $client = new MockHttpClient($responses);

        $this->apiName = $apiName;
        $this->trafficProviderFactory = new TrafficProviderFactory($client);
    }

    /**
     * @When I request to the traffic API with :coordinatesLat and :coordinatesLong
     */
    public function iRequestToTheTrafficApiWithSpecificGeographicalCoordinates(float $coordinatesLat, float $coordinatesLong): void
    {

        $repository = new TrafficDataRepositoryInMemory();
        $request = new GetFlowRequest($coordinatesLat, $coordinatesLong, $this->apiName);
        $getTraffic = new GetFlow($repository, $this->trafficProviderFactory);

        $getTraffic->execute($request);

        $this->response = $getTraffic->getResponse();
    }

    /**
     * @Then I receive a successful response containing real-time traffic information
     */
    public function iReceiveASuccessfulResponseContainingRealTimeTrafficInformation(): void
    {
        Assert::assertNotNull($this->response);
    }

    /**
     * @Then the response includes details such as traffic level, reported incidents, and estimated delays
     */
    public function theResponseIncludesDetailsSuchAsTrafficLevelReportedIncidentsAndEstimatedDelays(): void
    {
        /** @var \stdClass */
        $responseDecode = json_decode($this->response->trafficData->getTrafficData());
        $responseData = $responseDecode->flowSegmentData;

        Assert::assertObjectHasProperty('currentSpeed', $responseData);
        Assert::assertObjectHasProperty('roadClosure', $responseData);
        Assert::assertObjectHasProperty('freeFlowTravelTime', $responseData);
    }
}
