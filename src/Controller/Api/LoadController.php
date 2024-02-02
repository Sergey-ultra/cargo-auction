<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\DTO\LoadFilter;
use App\Repository\LoadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class LoadController extends AbstractController
{
    #[Route('/load-list', name: 'api.cargo.index', methods:['get'])]
    public function index(#[MapQueryString] ?LoadFilter $filter, Request $request, LoadRepository $repository): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $perPage = $request->query->getInt('per_page', 10);
        $orderBy = $request->query->getString('order_by', LoadRepository::ORDER_CREATED_AT);
        $isMy = $request->query->getBoolean('isMy');

        $user = $isMy ? $this->getUser() : null;

        $listDto = $repository->getList($filter, $page, $perPage, $orderBy, $user);

        $totalCount = $listDto->totalCount;
        $lastPage = (int)ceil($totalCount / $perPage);

        $result = [
            'data' => [
                'list' => $listDto->list,
                'page' => $page,
                'perPage' => $perPage,
                'orderBy' => $orderBy,
                'totalCount' => $totalCount,
                'lastPage' => $lastPage,
            ]];

        return $this->json($result, Response::HTTP_OK, [], [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }]
        );
    }
}
