<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller\Api;

use App\ApiGateway\DTO\FilterSaveDTO;
use App\Modules\Load\Domain\Entity\Filter;
use App\Modules\Load\Infrastructure\Repository\FilterRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class LoadFilterController extends ApiController
{
    #[Route('/load-filter', name: 'api.filter.index', methods:['get'])]
    public function index(FilterRepository $filterRepository): JsonResponse
    {
        $list = $filterRepository->findBy(['user' => $this->getUser()]);


        return $this->apiJson(['data' => $list]);
    }

    #[Route('/load-filter', name: 'api.filter.save', methods:['post'])]
    public function saveFilter(#[MapRequestPayload] FilterSaveDTO $filterDto, FilterRepository $filterRepository): JsonResponse
    {
        $user = $this->getUser();

        $filter = new Filter();
        $filter
            ->setName($filterDto->name)
            ->setFilter($filterDto->filter->toArray())
            ->setUser($user);

        $filterRepository->save($filter);

        return $this->apiJson(['data' => ['status' => 'ok']], Response::HTTP_CREATED);

    }
}
