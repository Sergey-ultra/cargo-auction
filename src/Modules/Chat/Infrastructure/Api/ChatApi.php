<?php

declare(strict_types=1);

namespace App\Modules\Chat\Infrastructure\Api;

use App\Modules\Chat\Domain\Entity\Chat;
use App\Modules\Chat\Domain\Entity\Message;
use App\Modules\Chat\Infrastructure\Adapter\LoadAdapter;
use App\Modules\Chat\Infrastructure\Adapter\UserAdapter;
use App\Modules\Chat\Infrastructure\Repository\ChatRepository;
use App\Modules\Chat\Infrastructure\Repository\MessageRepository;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class ChatApi
{
    public function __construct(
        private ChatRepository $chatRepository,
        private MessageRepository $messageRepo,
        private LoadAdapter $loadAdapter,
        private UserAdapter $userAdapter
    )
    {
    }

    public function getMyChats(UserInterface $owner, int $perPage)
    {
        return $this->chatRepository->getMyChats($owner, $perPage);
    }

    public function saveMessage(string $messageString, int $chatId): void
    {
        $chat = $this->chatRepository->find($chatId);
        $message = new Message();

        $message
            ->setMessage($messageString)
            ->setChat($chat)
            ->setFromUser($chat->getOwner())
            ->setToUser($chat->getPartner());


        $this->messageRepo->save($message);
    }

    public function findChatById(int $id): ?Chat
    {
        return $this->chatRepository->find($id);
    }

    public function getMessagesByChatId(int $id)
    {
        return $this->messageRepo->findByChatId($id);
    }

    public function getChatByUser(UserInterface $ownerUser, int $loadId, int $userId):  Chat
    {
        $partnerUser = $this->userAdapter->getUserById($userId);
        $draftMessage = $this->loadAdapter->getLoadDraftMessageById($loadId);
        return $this->chatRepository->getByUserId($ownerUser, $partnerUser, $draftMessage);
    }
}