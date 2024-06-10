<?php

namespace RoadTraffic\Infrastructure\Api\V1;

use Exception;
use RoadTraffic\Application\Service\GetIncident\GetIncident;
use RoadTraffic\Application\Service\GetIncident\GetIncidentRequest;
use RoadTraffic\Application\Service\ProviderAbstractFactory;
use RoadTraffic\Domain\TrafficDataRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReturnIncidentController
{
    public function __construct(private TrafficDataRepositoryInterface $repository, private ProviderAbstractFactory $factory)
    {
    }

    #[Route('/api/v1/incident/Return', 'returnIncident', methods: ['POST'])]
    public function execute(Request $request): Response
    {
        $getFlowRequest = $this->buildGetFlowRequest($request);

        $incident = new GetIncident($this->repository, $this->factory);

        try {
            $incident->execute($getFlowRequest);
        } catch (Exception $e) {
            return new JsonResponse(
                [
                    'success' => false,
                    'ErrorCode' => $incident,
                    'data' => '',
                    'message' => $e->getMessage(),
                ],
                $e->getCode(),
            );
        }
        $response = $incident->getResponse();
        return new JsonResponse(
            [
                'success' => true,
                'ErrorCode' => "",
                'data' => ['IncidentId' =>  $response->trafficData->getId()->__toString(),
                           'IncidentData' => $response->trafficData->getTrafficData()->__toString(),
                           'CreatedAt' => $response->trafficData->getCreatedAt()
                          ],
                'message' => "",
            ],
            201
        );
    }

    private function buildGetFlowRequest(Request $request): GetIncidentRequest
    {
        $content = $request->getContent();
        $data = json_decode($content, true);

        /** @var array<float> */
        $bbox = $data['bbox'];
        /** @var string*/
        $apiName = $data['apiName'];

        return new GetIncidentRequest($bbox, $apiName);
    }
}
