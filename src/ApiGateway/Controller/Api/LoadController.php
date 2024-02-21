<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller\Api;


use App\ApiGateway\DTO\LoadCreateDTO;
use App\ApiGateway\DTO\LoadFilter;
use App\ApiGateway\Request\CreateRequest;
use App\Modules\Load\Infrastructure\Api\LoadApi;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class LoadController extends ApiController
{
    #[Route('/load-list', name: 'api.cargo.index', methods:['get'])]
    public function index(#[MapQueryString] ?LoadFilter $filter, Request $request, LoadApi $loadApi): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $perPage = $request->query->getInt('perPage', 10);
        $orderBy = $request->query->getString('orderBy', $loadApi->getDefaultOrderByOption());
        $isMy = $request->query->getBoolean('isMy');

        $user = $isMy ? $this->getUser() : null;

        $listDto = $loadApi->getList($filter, $page, $perPage, $orderBy, $user);

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

        return $this->apiJson($result);
    }

    #[Route('load/create', name: 'api.cargo.store', methods:['post'] )]
    //    #[IsGranted("ROLE_ADMIN")]
    public function store(#[MapRequestPayload] LoadCreateDTO $loadCreateDto, LoadApi $loadApi): JsonResponse
    {
        $load = $loadApi->saveLoad($loadCreateDto, $this->getUser());
        return $this->apiJson(data: $load);

    }
}
