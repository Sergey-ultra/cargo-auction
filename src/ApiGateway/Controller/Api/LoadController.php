<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller\Api;

use App\ApiGateway\DTO\CommentDTO;
use App\ApiGateway\DTO\CommentSaveDTO;
use App\ApiGateway\DTO\LoadFilter;
use App\ApiGateway\DTO\Request\LoadCreateDTO;
use App\Modules\Load\Infrastructure\Api\CommentApi;
use App\Modules\Load\Infrastructure\Api\LoadApi;
use Symfony\Component\HttpFoundation\JsonResponse;
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

        $byUser = $isMy ? $this->getUser() : null;

        $listDto = $loadApi->getList(
            $filter,
            $page,
            $perPage,
            $orderBy,
            (bool)$this->getUser(),
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

    #[Route('/load/{id}', name: 'api.cargo.show', requirements: ['id' => '\d+'], methods: ['get'])]
    public function show(int $id, LoadApi $loadApi): Response
    {
        $load = $loadApi->getLoadById($id, (bool)$this->getUser());

        return $this->apiJson(['data' => $load]);
    }


    #[Route(path: '/load/create', name: 'api.cargo.store', methods:['post'] )]
    //    #[IsGranted("ROLE_ADMIN")]
    public function store(#[MapRequestPayload] LoadCreateDTO $loadCreateDto, LoadApi $loadApi): JsonResponse
    {
        $createdLoadId = $loadApi->saveLoad($loadCreateDto, $this->getUser());

        return $this->apiJson($createdLoadId, Response::HTTP_CREATED);
    }

    #[Route('/load/comment', name: 'api.cargo.comment.store', methods:['post'])]
    public function createComment(#[MapRequestPayload]CommentSaveDTO $commentDto, CommentApi $commentApi): JsonResponse
    {
        $comment = $commentApi->saveComment(
            new CommentDTO(
                null,
                $commentDto->comment,
                $this->getUser()->getId(),
                $commentDto->entityId
            )
        );
        return $this->json(['data' => $comment], Response::HTTP_CREATED);
    }

    #[Route('/load/comment/{id}', name: 'api.cargo.comment.update', requirements: ['id' => '\d+'], methods: ['put'])]
    public function updateComment(#[MapRequestPayload]CommentSaveDTO $commentDto, CommentApi $commentApi): JsonResponse
    {
        $comment = $commentApi->saveComment(
            new CommentDTO(
                $commentDto->id,
                $commentDto->comment,
                $this->getUser()->getId(),
                $commentDto->entityId
            )
        );
        return $this->json(['data' => $comment]);
    }

    #[Route('/load/comment/{id}', name: 'api.cargo.comment.delete', requirements: ['id' => '\d+'], methods: ['delete'])]
    public function deleteComment(int $id, CommentApi $commentApi): void
    {
        $commentApi->deleteComment($id);
    }
}
