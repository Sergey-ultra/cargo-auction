<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller\Api;

use App\ApiGateway\DTO\CommentDTO;
use App\ApiGateway\DTO\CommentSaveDTO;
use App\ApiGateway\DTO\LoadFilter;
use App\Modules\Transport\Infrastructure\Api\CommentApi;
use App\Modules\Transport\Infrastructure\Api\TransportApi;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
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

        $byUser = $isMy ? $this->getUser() : null;

        $listDto = $transportApi->getList(
            $filter,
            $page,
            $perPage,
            $orderBy,
            $this->getUser()?->getId(),
            $byUser
        );

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

    #[Route('/transport/comment', name: 'api.transport.comment.store', methods:['post'])]
    public function saveComment(#[MapRequestPayload]CommentSaveDTO $commentDto, CommentApi $commentApi): JsonResponse
    {
        $comment = $commentApi->saveComment(
            new CommentDTO(
                $commentDto->comment,
                $this->getUser()->getId(),
                $commentDto->entityId
            )
        );

        return $this->json(['data' => $comment], Response::HTTP_CREATED);
    }
}
