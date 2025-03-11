<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller\Api;

use App\ApiGateway\DTO\BidCreateDTO;
use App\Modules\Load\Infrastructure\Api\BidApi;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class BidController extends ApiController
{
    #[Route('/sendBid/{id}', name: 'cargo.showBidForm', methods:['post'])]
    public function createBid(int $id, #[MapRequestPayload] BidCreateDTO $payload,BidApi $bidApi): JsonResponse
    {
        try {
            $bid = $bidApi->saveBid($payload->bid, $id);

            return $this->apiJson(['data' => $bid], Response::HTTP_CREATED);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
