<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller;

use App\ApiGateway\Form\LoadType;
use App\Modules\Load\Application\OrderService\LoadService;
use App\Modules\Load\Domain\Entity\BodyType;
use App\Modules\Load\Domain\Entity\CargoType;
use App\Modules\Load\Domain\Entity\Load;
use App\Modules\Load\Domain\Entity\LoadingType;
use App\Modules\Load\Domain\Entity\PackageType;
use App\Modules\Load\Infrastructure\Repository\LoadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoadController extends AbstractController
{
    #[Route('/', name: 'cargo.index', methods:['get'])]
    public function index(): Response
    {
        return $this->render('cargo/index.html.twig');
    }

    #[Route('/load/{id}', name: 'cargo.show', methods:['get'])]
    public function show(int $id, LoadRepository $loadRepository): Response
    {
        $load = $loadRepository->find($id);

        return $this->render('cargo/show.html.twig', [
            'load' => $load,
            'cargoTypes' => CargoType::CARGO_TYPES,
            'packageTypes' => PackageType::PACKAGE_TYPES,
            'bodyTypes' => BodyType::BODY_TYPES,
            'loadingTypes' => LoadingType::LOADING_TYPES,
            'downloadingDateStatuses' => Load::DOWNLOADING_DATE_TITLES,
            'errors' => [],
        ]);
    }

    #[Route('/create', name: 'cargo.create', methods:['get'])]
    public function create(): Response
    {
        return $this->render('cargo/form.html.twig', [
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
    public function store(Request $request, LoadService $service): RedirectResponse|Response
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

        return $this->render('cargo/form.html.twig', [
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

        return $this->render('cargo/form.html.twig', [
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
