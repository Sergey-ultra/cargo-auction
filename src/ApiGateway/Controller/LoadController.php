<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller;

use App\ApiGateway\Form\LoadType;
use App\Modules\Load\Domain\Entity\Load;
use App\Modules\Load\Infrastructure\Api\LoadApi;
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

    #[Route('/load/{id}', name: 'cargo.show', requirements: ['page' => '\d+'], methods: ['get'])]
    public function show(int $id, LoadApi $loadApi): Response
    {
        $load = $loadApi->getLoadById($id);

        return $this->render('cargo/show.html.twig', [
            'load' => $load,
            'cargoTypes' => $loadApi->getCargoTypes(),
            'packageTypes' => $loadApi->getPackageType(),
            'bodyTypes' => $loadApi->getBodyTypes(),
            'loadingTypes' => $loadApi->getLoadingTypes(),
            'downloadingDateStatuses' => $loadApi->getDownloadingDateTitles(),
            'errors' => [],
        ]);
    }

    #[Route('/load/create', name: 'cargo.create', methods:['get'], priority: 1)]
    public function create(LoadApi $loadApi): Response
    {
        return $this->render('cargo/form.html.twig', [
            'cargoTypes' => $loadApi->getCargoTypes(),
            'packageTypes' => $loadApi->getPackageType(),
            'bodyTypes' => $loadApi->getBodyTypes(),
            'loadingTypes' => $loadApi->getLoadingTypes(),
            'downloadingDateStatuses' => $loadApi->getDownloadingDateTitles(),
            'errors' => [],
        ]);
    }

    #[Route('/create', name: 'cargo.store', methods:['post'] )]
    //    #[IsGranted("ROLE_ADMIN")]
    public function store(Request $request, LoadApi $loadApi): RedirectResponse|Response
    {
        $load = new Load();
        $form = $this->createForm(LoadType::class, $load);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid() ) {

            $load->setUser($this->getUser());
            dd($load);

            $loadApi->saveLoad($load);
            return $this->redirectToRoute('cargo.show', ['id' => $load->getId()], Response::HTTP_SEE_OTHER);
        }
        $errors = $form->getErrors();

        return $this->render('cargo/form.html.twig', [
            'cargoTypes' => $loadApi->getCargoTypes(),
            'packageTypes' => $loadApi->getPackageType(),
            'bodyTypes' => $loadApi->getBodyTypes(),
            'loadingTypes' => $loadApi->getLoadingTypes(),
            'downloadingDateStatuses' => $loadApi->getDownloadingDateTitles(),
            'errors' => $errors,
        ]);
    }

    #[Route('/{id}/edit', name: 'cargo.edit', requirements: [ "id" => "\d+"], methods: ['get'])]
    public function edit(int $id, LoadApi $loadApi): Response
    {
        $load = $loadApi->getLoadById($id);

        return $this->render('cargo/form.html.twig', [
            'cargoTypes' => $loadApi->getCargoTypes(),
            'packageTypes' => $loadApi->getPackageType(),
            'bodyTypes' => $loadApi->getBodyTypes(),
            'loadingTypes' => $loadApi->getLoadingTypes(),
            'downloadingDateStatuses' => $loadApi->getDownloadingDateTitles(),
            'errors' => [],
        ]);
    }
}
