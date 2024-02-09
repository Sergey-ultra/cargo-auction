<?php

namespace App\WebSocket\Manager;

use App\WebSocket\Manager\NotificationManagerUsingDB\Entity\Messages;

interface NotificationManagerInterface
{
    public function sendMessage(Messages $message, array $clients): void;
    public function getQueuedMessages(): array;
    public function removeMessage(Messages $message): void;

}
