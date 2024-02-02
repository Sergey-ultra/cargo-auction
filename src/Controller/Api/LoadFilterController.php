<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\DTO\FilterSaveDTO;
use App\Entity\Filter;
use App\Repository\FilterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class LoadFilterController extends AbstractController
{
    #[Route('/load-filter', name: 'api.filter.index', methods:['get'])]
    public function index(FilterRepository $filterRepository): JsonResponse
    {
        $list = $filterRepository->findBy(['user' => $this->getUser()]);


        return $this->json(['data' => $list], Response::HTTP_OK, [], [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }]
        );
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

        return $this->json(['data' => ['status' => 'ok']], Response::HTTP_CREATED);

    }
}
