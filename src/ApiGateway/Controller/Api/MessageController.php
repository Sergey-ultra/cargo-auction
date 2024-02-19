<?php

declare(strict_types=1);

namespace App\ApiGateway\Controller\Api;

use App\ApiGateway\DTO\MessageCreateDTO;
use App\Modules\Chat\Infrastructure\Api\ChatApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[Route('/api', name: 'api_')]
class MessageController extends ApiController
{
    #[Route('/chat/{id}', name: 'api.chat.show', methods:['get'])]
    public function showChat(int $id, ChatApi $chatApi): Response
    {
        $chat = $chatApi->findChatById($id);

        $result = ['data' =>  $chat];

        return $this->apiJson($result);
    }

    #[Route('/chat/{id}/messages', name: 'api.chat.messages', methods:['get'])]
    public function getMessages(int $id, ChatApi $chatApi): Response
    {
        $messages = $chatApi->getMessagesByChatId($id);

        $result = ['data' =>  $messages];

        return $this->apiJson(
            $result,
            Response::HTTP_OK,
            [],
            [DateTimeNormalizer::FORMAT_KEY => 'Y-m-d H:i:s']
        );
    }

    #[Route('/chat/{id}/messages', name: 'api.chat.messages.create', methods:['post'])]
    public function createMessages(
        int $id,
        #[MapRequestPayload]MessageCreateDTO $createMessage,
        ChatApi $chatApi
    ): Response
    {
        $chatApi->saveMessage($createMessage->message, $id);
        return $this->json(['data' =>  ['status' => 'ok']], Response::HTTP_CREATED);
    }

    #[Route('/chats', name: 'api.chats.index', methods:['get'])]
    public function chats(Request $request, ChatApi $chatApi): Response
    {
        $user = $this->getUser();
        if ($user) {
            $perPage = $request->query->getInt('perPage', 20);
            $list = $chatApi->getMyChats($user, $perPage);

            return $this->apiJson(['data' => $list]);
        }

        return $this->json(['data' => []]);
    }
}
