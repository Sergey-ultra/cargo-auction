<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\PhoneDTO;
use App\Entity\Phone;
use App\Services\PhoneService\PhoneService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
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
}
