<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller\Api;

use App\ApiGateway\DTO\LoadFilter;
use App\Modules\Transport\Infrastructure\Api\TransportApi;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class TransportController extends ApiController
{

    #[Route('/transport', name: 'api.transport.index', methods:['get'])]
    public function index(#[MapQueryString] ?LoadFilter $filter, Request $request, TransportApi $transportApi): JsonResponse
    {
        $page = $request->query->getInt('page', 1);
        $perPage = $request->query->getInt('perPage', 10);
        $orderBy = $request->query->getString('orderBy', $transportApi->getDefaultOrderByOption());
        $isMy = $request->query->getBoolean('isMy');

        $user = $isMy ? $this->getUser() : null;

        $listDto = $transportApi->getList($filter, $page, $perPage, $orderBy, $user);

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
}
