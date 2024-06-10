<?php

namespace RoadTraffic\Application\Service\GetIncident;

use RoadTraffic\Application\Service\ProviderAbstractFactory;
use RoadTraffic\Domain\GeographicCoordinates;
use RoadTraffic\Domain\Latitude;
use RoadTraffic\Domain\Longitude;
use RoadTraffic\Domain\TrafficDataRepositoryInterface;
use RoadTraffic\Domain\TrafficData;
use RoadTraffic\Domain\TrafficDataId;
use RoadTraffic\Domain\TrafficDataJson;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\RequestTomtom;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\RequestTomtomIncident;
use RoadTraffic\Infrastructure\TrafficProvider\TrafficProviderInterface;
use stdClass;

class GetIncident
{
    private GetIncidentResponse $response;

    public function __construct(
        private TrafficDataRepositoryInterface $repository,
        private ProviderAbstractFactory $trafficProviderFactory
    ) {
        $this->repository = $repository;
        $this->trafficProviderFactory = $trafficProviderFactory;
    }

    public function execute(GetIncidentRequest $request): void
    {
        $trafficProvider = $this->trafficProviderFactory->create($request->providerName);

        $dataId = new TrafficDataId();
        $request =  new RequestTomtomIncident($request->bbox);

        /** @var \stdClass */
        $resultDataJsonIncident = $trafficProvider->requestIncidentTraffic(
            $request
        );

        $trafficData = new TrafficData($dataId, new TrafficDataJson($resultDataJsonIncident->jsonData));
        $this->repository->add($trafficData);

        $this->response = new GetIncidentResponse($trafficData);
    }

    public function getResponse(): GetIncidentResponse
    {
        return $this->response;
    }
}
