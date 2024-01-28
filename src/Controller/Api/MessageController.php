<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\DTO\ChatFilterDTO;
use App\Repository\ChatRepository;
use App\Repository\LoadRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class MessageController extends AbstractController
{
    #[Route('/messages/{id}', name: 'api.messages.show', methods:['get'])]
    public function showMessage(
        int $id,
        Request $request,
        UserRepository $userRepository,
        LoadRepository $loadRepository,
        ChatRepository $chatRepository
    ): Response
    {
        $loadId = $request->query->getInt('load_id');
        $load = $loadRepository->find($loadId);
        $user = $userRepository->find($id);

        $chat = $chatRepository->getByUserId($this->getUser(), $user, $load);

        $result = ['data' =>  $chat];

        return $this->json($result, Response::HTTP_OK, [], [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }]
        );
    }

    #[Route('/chats', name: 'api.chats.index', methods:['get'])]
    public function chats(Request $request, ChatRepository $chatRepository): Response
    {
        $perPage = $request->query->getInt('perPage', 20);

        $list = $chatRepository->getMyChats($this->getUser(), $perPage);

        $result = ['data' => $list];

        return $this->json($result, Response::HTTP_OK, [], [
                'circular_reference_handler' => function ($object) {
                    return $object->getId();
                }]
        );
    }
}
