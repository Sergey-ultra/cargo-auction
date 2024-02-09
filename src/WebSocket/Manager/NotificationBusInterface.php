<?php

namespace App\WebSocket\Manager;

interface NotificationBusInterface
{
    public function sendNotificationMessage(string $message, string $userId = null): void;
}
