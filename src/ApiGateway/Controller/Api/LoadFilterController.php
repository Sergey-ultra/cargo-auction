<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller\Api;

use App\ApiGateway\DTO\FilterSaveDTO;
use App\Modules\Load\Infrastructure\Api\FilterApi;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class LoadFilterController extends ApiController
{
    #[Route('/load-filter', name: 'api.filter.index', methods:['get'])]
    public function index(FilterApi $filterApi): JsonResponse
    {
        $list = [];
        $user = $this->getUser();
        if ($user) {
            $list = $filterApi->getFiltersByUser($user);
        }

        return $this->apiJson(['data' => $list]);
    }

    #[Route('/load-filter', name: 'api.filter.save', methods:['post'])]
    public function saveFilter(#[MapRequestPayload] FilterSaveDTO $filterDto, FilterApi $filterApi): JsonResponse
    {
        $user = $this->getUser();

        $filterApi->save($filterDto, $user);

        return $this->apiJson(['data' => ['status' => 'ok']], Response::HTTP_CREATED);
    }
}
