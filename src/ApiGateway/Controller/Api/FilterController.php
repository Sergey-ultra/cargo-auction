<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller\Api;

use App\ApiGateway\DTO\FilterSaveDTO;
use App\ApiGateway\DTO\FilterShowDTO;
use App\ApiGateway\DTO\FilterTypeDTO;
use App\Modules\Filter\Infrastructure\Api\FilterApi;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class FilterController extends ApiController
{
    #[Route('/load-filter', name: 'api.filter.index', methods:['get'])]
    public function index(#[MapQueryString] FilterTypeDTO $filterType, FilterApi $filterApi): JsonResponse
    {
        $list = [];
        $user = $this->getUser();
        if ($user) {
            $list = $filterApi->getFiltersByUser($user, $filterType->type);
        }

        return $this->apiJson(['data' => $list]);
    }

    #[Route('/load-filter', name: 'api.filter.save', methods:['post'])]
    public function saveFilter(#[MapRequestPayload] FilterSaveDTO $filterDto, FilterApi $filterApi): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->apiJson(['data' => ['status' => 'false', 'message' => 'You are not autorized']]);
        }

        $filterApi->save($filterDto, $user);
        $result = ['status' => 'ok'];


        return $this->apiJson(['data' => $result], Response::HTTP_CREATED);
    }
}
