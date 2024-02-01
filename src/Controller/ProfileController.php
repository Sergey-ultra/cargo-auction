<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\LoadFilter;
use App\DTO\PhoneDTO;
use App\Entity\Phone;
use App\Repository\ChatRepository;
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


    #[Route('profile/messages/{id}', name: 'profile.messages.main', methods:['get'])]
    public function getChat(
        int $id,
        Request $request,
        UserRepository $userRepository,
        LoadRepository $loadRepository,
        ChatRepository $chatRepository
    ): RedirectResponse
    {
        $loadId = $request->query->getInt('load_id');
        $load = $loadRepository->find($loadId);
        $user = $userRepository->find($id);

        $chat = $chatRepository->getByUserId($this->getUser(), $user, $load);

        return $this->redirectToRoute('profile.chat', ['id' => $chat->getId()]);
    }

    #[Route('profile/chat/{id}', name: 'profile.chat', methods:['get'])]
    public function getChatById(): Response
    {
        return $this->render('profile/messages.html.twig');
    }

    #[Route('profile/messages', name: 'profile.messages.show', methods:['get'])]
    public function showMessage(): Response
    {
        return $this->render('profile/messages.html.twig');
    }

    #[Route('profile/company', name: 'profile.company', methods:['get'])]
    public function showCompany(): Response
    {
        return $this->render('profile/company.html.twig');
    }

    #[Route('profile/load-list', name: 'profile.my-cargos', methods:['get'])]
    public function showMyCargos(): Response
    {
        return $this->render('order/index.html.twig');
    }
}
