<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\LoadFilter;
use App\DTO\PhoneDTO;
use App\Entity\Phone;
use App\Repository\LoadRepository;
use App\Repository\UserRepository;
use App\Services\PaginationService\PaginationService;
use App\Services\PhoneService\PhoneService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('profile/edit', name: 'profile', methods:['get'])]
    public function edit(): Response
    {
        return $this->render('profile/edit.html.twig');
    }

    #[Route('profile/phones', name: 'profile.phones', methods:['get'])]
    public function phones(): Response
    {
        return $this->render('profile/phones.html.twig', [
            'phones' => $this->getUser()->getPhones(),
        ]);
    }

    #[Route('profile/phones/create', name: 'profile.phones.create', methods:['get'])]
    public function createPhone(): Response
    {
        return $this->render('profile/phones-create.html.twig');
    }

    #[Route('profile/phones/create', name: 'profile.phones.store', methods:['post'])]
    public function savePhone(#[MapRequestPayload] PhoneDTO $dto, PhoneService $phoneService): RedirectResponse
    {

        $phone = new Phone();
        $phone->setPhone($dto->phone)->setUser($this->getUser());

        if ($phoneService->verifyAndSave($phone)) {
            return $this->redirectToRoute('profile.phones', [], Response::HTTP_SEE_OTHER);
        }

        return $this->redirectToRoute('profile.phones.create', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('profile/messages', name: 'profile.messages', methods:['get'])]
    public function messagesList(): Response
    {
        return $this->render('profile/messages.html.twig');
    }

    #[Route('profile/messages/{id}', name: 'profile.messages.show', methods:['get'])]
    public function showMessage(int $id, Request $request, UserRepository $userRepository, LoadRepository $orderRepository): Response
    {
        $loadId = $request->query->getInt('load_id');
        $user = $userRepository->find($id);

        return $this->render('profile/messages.html.twig');
    }

    #[Route('profile/company', name: 'profile.company', methods:['get'])]
    public function showCompany(): Response
    {
        return $this->render('profile/company.html.twig');
    }

    #[Route('profile/my-cargos', name: 'profile.my-cargos', methods:['get'])]
    public function showMyCargos(
        #[MapQueryString] ?LoadFilter $filter,
        Request                       $request,
        LoadRepository                $repository,
        PaginationService             $paginationService
    ): Response
    {
        $page = $request->query->getInt('page', 1);

        $paginator = $repository->getPaginator($page, $filter, $this->getUser());

        $totalCount = $paginator->count();
        $lastPage = (int)ceil($totalCount / LoadRepository::PAGINATOR_PER_PAGE);

        $borders = $paginationService->getBorders($page, $lastPage);

        return $this->render('profile/my-cargos.html.twig', [
        'filter' => $filter,
            'list' => $paginator,
            'page' => $page,
            'totalCount' => $totalCount,
            'lastPage' => $lastPage,
            'borders' => $borders,
        ]);
    }
}
