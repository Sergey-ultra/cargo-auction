<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\LoadFilter;
use App\Entity\BodyType;
use App\Entity\CargoType;
use App\Entity\Load;
use App\Entity\LoadingType;
use App\Entity\PackageType;
use App\Form\LoadType;
use App\Repository\LoadRepository;
use App\Services\OrderService\OrderService;
use App\Services\PaginationService\PaginationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class LoadController extends AbstractController
{
    #[Route('/', name: 'cargo.index', methods:['get'])]
    public function index(
        #[MapQueryString] ?LoadFilter $filter,
        Request                       $request,
        LoadRepository                $repository,
        PaginationService             $paginationService
    ): Response
    {
        $page = $request->query->getInt('page', 1);

        $listDto = $repository->getList($page, $filter);

        $totalCount = $listDto->totalCount;
        $lastPage = (int)ceil($totalCount / LoadRepository::PAGINATOR_PER_PAGE);

        $borders = $paginationService->getBorders($page, $lastPage);

        return $this->render('order/index.html.twig', [
            'filter' => $filter,
            'list' => $listDto->list,
            'page' => $page,
            'totalCount' => $totalCount,
            'lastPage' => $lastPage,
            'borders' => $borders,
        ]);
    }

    #[Route('/create', name: 'cargo.create', methods:['get'])]
    public function create(): Response
    {
        return $this->render('order/form.html.twig', [
            'cargoTypes' => CargoType::CARGO_TYPES,
            'packageTypes' => PackageType::PACKAGE_TYPES,
            'bodyTypes' => BodyType::BODY_TYPES,
            'loadingTypes' => LoadingType::LOADING_TYPES,
            'downloadingDateStatuses' => Load::DOWNLOADING_DATE_TITLES,
            'errors' => [],
        ]);
    }


    #[Route('/create', name: 'cargo.store', methods:['post'] )]
    //    #[IsGranted("ROLE_ADMIN")]
    public function store(Request $request, OrderService $service): RedirectResponse|Response
    {
        $order = new Load();
        $form = $this->createForm(LoadType::class, $order);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid() ) {

            $order->setUser($this->getUser());

            $service->save($order);
            return $this->redirectToRoute('cargo.index', [], Response::HTTP_SEE_OTHER);
        }
        $errors = $form->getErrors();

        return $this->render('order/form.html.twig', [
            'cargoTypes' => CargoType::CARGO_TYPES,
            'packageTypes' => PackageType::PACKAGE_TYPES,
            'bodyTypes' => BodyType::BODY_TYPES,
            'loadingTypes' => LoadingType::LOADING_TYPES,
            'downloadingDateStatuses' => Load::DOWNLOADING_DATE_TITLES,
            'errors' => $errors,
        ]);
    }

    #[Route('/{id}/edit', name: 'cargo.edit', requirements: [ "id" => "\d+"], methods: ['get'])]
    public function edit(int $id, LoadRepository $loadRepository): Response
    {
        $load = $loadRepository->find($id);

        return $this->render('order/form.html.twig', [
            'load' => $load,
            'cargoTypes' => CargoType::CARGO_TYPES,
            'packageTypes' => PackageType::PACKAGE_TYPES,
            'bodyTypes' => BodyType::BODY_TYPES,
            'loadingTypes' => LoadingType::LOADING_TYPES,
            'downloadingDateStatuses' => Load::DOWNLOADING_DATE_TITLES,
            'errors' => [],
        ]);
    }
}
