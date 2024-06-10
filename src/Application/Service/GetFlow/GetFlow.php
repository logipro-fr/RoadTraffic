<?php

namespace RoadTraffic\Application\Service\GetFlow;

use RoadTraffic\Application\Service\ProviderAbstractFactory;
use RoadTraffic\Application\Service\TrafficProviderInterface;
use RoadTraffic\Domain\GeographicCoordinates;
use RoadTraffic\Domain\Latitude;
use RoadTraffic\Domain\Longitude;
use RoadTraffic\Domain\TrafficDataRepositoryInterface;
use RoadTraffic\Domain\TrafficData;
use RoadTraffic\Domain\TrafficDataId;
use RoadTraffic\Domain\TrafficDataJson;
use RoadTraffic\Infrastructure\TrafficProvider\Tomtom\RequestTomtom;
use RoadTraffic\Infrastructure\TrafficProviderFactory;
use stdClass;

class GetFlow
{
    private GetFlowResponse $response;



    public function __construct(private TrafficDataRepositoryInterface $repository, private ProviderAbstractFactory $trafficProviderFactory)
    {
    }

    public function execute(GetFlowRequest $request): void
    {
        $trafficProvider = $this->trafficProviderFactory->create($request->providerName);

        $dataId = new TrafficDataId();
        $request =  new RequestTomtom(new GeographicCoordinates(new Latitude($request->latitude), new Longitude($request->longitude)));

        /** @var \stdClass */
        $resultDataJsonFlow = $trafficProvider->requestFlowTraffic(
            $request
        );

        $trafficData = new TrafficData($dataId, new TrafficDataJson($resultDataJsonFlow->jsonData));
        $this->repository->add($trafficData);

        $this->response = new GetFlowResponse($trafficData);
    }

    // /**
    //  * @return array<int, \stdClass>
    //  */
    // private function requestTraffic(RequestTomtom $request): array
    // {
    //     /** @var \stdClass */
    //     $resultDataJsonFlow = $this->trafficProvider->requestFlowTraffic(
    //         $request
    //     );

    //     /** @var \stdClass */
    //     $resultDataJsonIncident = $this->trafficProvider->requestIncidentTraffic(
    //         $request
    //     );

    //     return [$resultDataJsonFlow, $resultDataJsonIncident];
    // }

    public function getResponse(): GetFlowResponse
    {
        return $this->response;
    }
}
