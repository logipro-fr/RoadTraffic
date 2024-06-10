<?php

namespace RoadTraffic\Infrastructure\Api\V1;

use Exception;
use RoadTraffic\Application\Service\GetFlow\GetFlow;
use RoadTraffic\Application\Service\GetFlow\GetFlowRequest;
use RoadTraffic\Application\Service\ProviderAbstractFactory;
use RoadTraffic\Domain\TrafficDataRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReturnFlowController
{
    public function __construct(private TrafficDataRepositoryInterface $repository, private ProviderAbstractFactory $factory)
    {
    }

    #[Route('/api/v1/flow/Return', 'returnFlow', methods: ['GET'])]
    public function execute(Request $request): Response
    {
        try {
            $request = $this->buildGetFlowRequest($request);
            $flow = new GetFlow($this->repository, $this->factory);
            $flow->execute($request);
        } catch (Exception $e) {
            $statusCode = $e->getCode();
            return new JsonResponse(
                [
                    'success' => false,
                    'ErrorCode' => $e::class,
                    'data' => '',
                    'message' => $e->getMessage(),
                ],
                400,
            );
        }
        $response = $flow->getResponse();
        return new JsonResponse(
            [
                'success' => true,
                'ErrorCode' => "",
                'data' => ['FlowId' =>  $response->trafficData->getId()->__toString(),
                           'FlowData' => $response->trafficData->getTrafficData()->__toString(),
                           'CreatedAt' => $response->trafficData->getCreatedAt()
                          ],
                'message' => "",
            ],
            201
        );
    }

    private function buildGetFlowRequest(Request $request): GetFlowRequest
    {
        /** @var string */
        $latitude = $request->get('latitude');
        /** @var string */
        $longitude = $request->get('longitude');
        /** @var string */
        $apiName = $request->get('apiName');


        return new GetFlowRequest($latitude, $longitude, $apiName);
    }
}
