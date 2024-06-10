<?php

namespace Features;

use Behat\Behat\Context\Context;
use PHPUnit\Framework\Assert;
use RoadTraffic\Application\Service\GetIncident\GetIncident;
use RoadTraffic\Application\Service\GetIncident\GetIncidentRequest;
use RoadTraffic\Application\Service\GetIncident\GetIncidentResponse;
use RoadTraffic\Infrastructure\Persistance\TrafficDataRepositoryInMemory;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\RoadTrafficTomtomApi;
use RoadTraffic\Infrastructure\TrafficProviderFactory;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * Defines application features from the specific context.
 */
class IncidentContext implements Context
{
    public RoadTrafficTomtomApi $trafficProvider;
    public TrafficProviderFactory $trafficProviderFactory;
    public GetIncidentResponse $response;

    public string $apiName;
    /** @var array<float> */
    public array $bbox;

    public function before()
    {
        
    }
   /**
     * @Given I select the traffic API provider :apiName for incident
     */
    public function iSelectTheTrafficApiProviderForIncident(string $apiName): void
    {
        $content = file_get_contents(getcwd() . "/tests/resources/tomtomApiIncidentTraffic1.json");
        $responses = [new MockResponse(strval($content))];
        $client = new MockHttpClient($responses);

        $this->apiName = $apiName;
        $this->trafficProviderFactory = new TrafficProviderFactory($client);
    }

    /**
     * @Given Valid bbox point :bbox
     */
    public function validBboxPoint(string $bbox): void
    {
        $bboxArray = array_map('floatval', explode(',', $bbox));

        if (count($bboxArray) == 4) {
            $this->bbox = $bboxArray;
        }
    }

    /**
     * @When I make a request to the traffic API for incidents
     */
    public function iMakeARequestToTheTrafficApiForIncidents(): void
    {
        $repository = new TrafficDataRepositoryInMemory();
        $request = new GetIncidentRequest($this->bbox, $this->apiName);
        $getTraffic = new GetIncident($repository, $this->trafficProviderFactory);

        $getTraffic->execute($request);

        $this->response = $getTraffic->getResponse();
    }

    /**
     * @Then I receive a successful response containing real-time incident information
     */
    public function iReceiveASuccessfulResponseContainingRealTimeIncidentInformation(): void
    {
        Assert::assertNotNull($this->response);
    }

    /**
     * @Then the response includes details such as probability of occurrence, reported incidents, and estimated delays
     */
    public function theResponseIncludesDetailsSuchAsProbabilityOfOccurrenceReportedIncidentsAndEstimatedDelays(): void
    {
        /**  @var \stdClass */
        $responseDecode = json_decode($this->response->trafficData->getTrafficData());
        $responseData = $responseDecode->incidents[0];
        Assert::assertObjectHasProperty('type', $responseData);
    }
}
