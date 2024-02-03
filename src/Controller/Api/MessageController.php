<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\DTO\MessageCreateDTO;
use App\Entity\Message;
use App\Repository\ChatRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[Route('/api', name: 'api_')]
class MessageController extends ApiController
{
    #[Route('/chat/{id}', name: 'api.chat.show', methods:['get'])]
    public function showChat(int $id, ChatRepository $chatRepository): Response
    {
        $chat = $chatRepository->find($id);

        $result = ['data' =>  $chat];

        return $this->apiJson($result);
    }

    #[Route('/chat/{id}/messages', name: 'api.chat.messages', methods:['get'])]
    public function getMessages(int $id, MessageRepository $messageRepository): Response
    {
        $messages = $messageRepository->findByBrandId($id);

        $result = ['data' =>  $messages];

        return $this->apiJson($result, Response::HTTP_OK, [], [DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']
        );
    }

    #[Route('/chat/{id}/messages', name: 'api.chat.messages.create', methods:['post'])]
    public function createMessages(
        int $id,
        #[MapRequestPayload]MessageCreateDTO $createMessage,
        MessageRepository $messageRepository,
        ChatRepository $chatRepository
    ): Response
    {
        $chat = $chatRepository->find($id);
        $message = new Message();

        $message
            ->setMessage($createMessage->message)
            ->setChat($chat)
            ->setFromUser($chat->getOwner())
            ->setToUser($chat->getPartner());


        $messageRepository->save($message);

        return $this->json(['data' =>  ['status' => 'ok']], Response::HTTP_CREATED);
    }

    #[Route('/chats', name: 'api.chats.index', methods:['get'])]
    public function chats(Request $request, ChatRepository $chatRepository): Response
    {
        if ($user = $this->getUser()) {

            $perPage = $request->query->getInt('perPage', 20);
            $list = $chatRepository->getMyChats($user, $perPage);

            $result = ['data' => $list];

            return $this->apiJson($result);
        }

        return $this->json(['data' => []]);
    }
}
