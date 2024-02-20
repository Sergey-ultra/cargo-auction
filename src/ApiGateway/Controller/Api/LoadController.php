<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller\Api;


use App\ApiGateway\DTO\LoadFilter;
use App\ApiGateway\Request\CreateRequest;
use App\Modules\Load\Infrastructure\Api\LoadApi;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
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
    public function store(CreateRequest $createDto, LoadApi $loadApi): RedirectResponse|Response
    {
        if ($createDto->isValid()) {
            $load = $loadApi->saveLoad($createDto, $this->getUser());
            return $this->redirectToRoute('cargo.show', ['id' => $load->getId()], Response::HTTP_SEE_OTHER);
        }

        $errors = $createDto->getErrors();
        return $this->render('cargo/form.html.twig', [
            'cargoTypes' => $loadApi->getCargoTypes(),
            'packageTypes' => $loadApi->getPackageType(),
            'bodyTypes' => $loadApi->getBodyTypes(),
            'loadingTypes' => $loadApi->getLoadingTypes(),
            'downloadingDateStatuses' => $loadApi->getDownloadingDateTitles(),
            'errors' => $errors,
        ]);
    }
}
