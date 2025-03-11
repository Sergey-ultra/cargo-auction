<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller;

use App\ApiGateway\DTO\PhoneDTO;
use App\Modules\Chat\Infrastructure\Api\ChatApi;
use App\Modules\Filter\Domain\Enum\FilterType;
use App\Modules\User\Infrastructure\Api\PhoneApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('profile/edit', name: 'profile', methods:['get'])]
    public function edit(): Response
    {
        $user = $this->getUser();

        return $this->render('profile/edit.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('profile/phones', name: 'profile.phones', methods:['get'])]
    public function phones(): Response
    {
        return $this->render('profile/phones.html.twig', [
            'phones' => $this->getUser()->getPhone(),
        ]);
    }

    #[Route('profile/phones/create', name: 'profile.phones.create', methods:['get'])]
    public function createPhone(): Response
    {
        return $this->render('profile/phones-create.html.twig');
    }

    #[Route('profile/phones/create', name: 'profile.phones.store', methods:['post'])]
    public function savePhone(#[MapRequestPayload] PhoneDTO $dto, PhoneApi $phoneApi): RedirectResponse
    {
       try {
           $phoneApi->verifyAndSave($dto->phone, $dto->mobilePhone, $this->getUser());
           return $this->redirectToRoute('profile.phones', [], Response::HTTP_SEE_OTHER);
       } catch (\Throwable $e) {
           return $this->redirectToRoute('profile.phones.create', [], Response::HTTP_SEE_OTHER);
       }
    }


    #[Route('profile/messages/{id}', name: 'profile.messages.main', methods:['get'])]
    public function getChatByUserId(int $id, Request $request, ChatApi $chatApi): RedirectResponse
    {
        $loadId = $request->query->getInt('load_id');
        $truckId = $request->query->getInt('truck_id');

        if (0 !== $loadId) {
            $chat = $chatApi->getChatByUserAndLoadId($this->getUser(), $loadId, $id);
        } else if (0 !== $truckId) {
            $chat = $chatApi->getChatByUserAndTransportId($this->getUser(), $truckId, $id);
        }


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
        return $this->render('cargo/index.html.twig');
    }
}
