<?php

declare(strict_types=1);

namespace App\WebSocket\Server;

use App\WebSocket\Manager\NotificationManagerInterface;
use Psr\Log\LoggerInterface;
use Ratchet\Server\IoServer;

class CustomIoServer extends IoServer
{
    public const WATCH_INTERVAL_SECONDS = 1;

    private NotificationManagerInterface $webSocketManager;
    private LoggerInterface $logger;

    public function attach(
        NotificationManagerInterface $webSocketManager,
        LoggerInterface              $logger
    ): self {
        $this->webSocketManager = $webSocketManager;
        $this->logger = $logger;

        return $this;
    }

    public function listenPeriodically(WebSocketServer $webSocketServer): self
    {
        $this->loop->addPeriodicTimer(self::WATCH_INTERVAL_SECONDS, function () use ($webSocketServer): void {
            $this->processMessages($webSocketServer);
        });

        return $this;
    }

    public function listenOnce(WebSocketServer $webSocketServer): self
    {
        $this->loop->addTimer(self::WATCH_INTERVAL_SECONDS, function () use ($webSocketServer): void {
            $this->processMessages($webSocketServer);
            $this->loop->stop();
        });

        return $this;
    }

    private function processMessages(WebSocketServer $webSocketServer): void
    {
        try {
            foreach ($this->webSocketManager->getQueuedMessages() as $message) {
                $this->webSocketManager->sendMessage($message, $webSocketServer->getConnections());
            }
        } catch (\Exception $e) {
            $this->logger->error('Failed to process messages', ['exception' => $e]);
        }
    }
}
