<?php

declare(strict_types=1);

namespace App\WebSocket\Server;

use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServerInterface;
use GuzzleHttp\Psr7\Request;

class WebSocketServer implements HttpServerInterface
{
    protected ?\SplObjectStorage $clients = null;
    private array $connections = [];

    public function __construct(private LoggerInterface $logger)
    {
        if (null === $this->clients) {
            $this->clients = new \SplObjectStorage();
        }
    }

    public function getClients(): ?\SplObjectStorage
    {
        return $this->clients;
    }

    public function getConnections(): array
    {
        return $this->connections;
    }
    /**
     * Save the new connection for sending messages later.
     */
    public function onOpen(ConnectionInterface $conn, RequestInterface $request = null): void
    {
        try {
            $this->clients->attach($conn);

            $this->logger->info(sprintf(
                'New client connected with resourceId "%d"',
                /* @phpstan-ignore-next-line */
                $conn->resourceId
            ));

            if ($userId = $this->getUserId($conn->httpRequest)) {
                $this->connections[$userId] = $conn;
            }




//            $data = [
//                'type' => MessageTypeEnum::CONNECTION_CALLBACK,
//                /* @phpstan-ignore-next-line */
//                'resourceId' => $connection->resourceId,
//            ];
//
//            $connection->send(json_encode($data));

            $this->logger->info(sprintf(
                'Callback resourceId "%d" sent',
                /* @phpstan-ignore-next-line */
                $conn->resourceId
            ));
        } catch (\Exception $e) {
            $this->logger->error('Failed to open connection', ['exception' => $e]);
        }
    }

    private function getUserId(Request $request): ?int
    {
        parse_str($request->getUri()->getQuery(), $queryArray);

        return isset($queryArray['user_id'])
            ? (int)$queryArray['user_id']
            : null;
    }

    public function onMessage(ConnectionInterface $from, $message): void
    {
        try {
            $this->logger->info(sprintf(
                'Connection %d sending message "%s" to %d other connections',
                /* @phpstan-ignore-next-line */
                $from->resourceId,
                $message,
                \count($this->clients) - 1
            ));

            foreach ($this->clients as $client) {
                // The sender and the receiver are not the same. Send to each connected client.
                if ($from !== $client) {
                    $client->send($message);
                }
            }
        } catch (\Exception $e) {
            $this->logger->error('Failed to send message', ['exception' => $e]);
        }
    }

    /**
     * The connection has been closed; remove it since we can no longer send messages through it.
     */
    public function onClose(ConnectionInterface $conn): void
    {
        try {
            $this->clients->detach($conn);
        } catch (\Exception $e) {
            $this->logger->error('Failed to close connection', ['exception' => $e]);
        }
    }

    public function onError(ConnectionInterface $connection, \Exception $exception): void
    {
        $this->logger->warning(sprintf('An error has occurred: %s', $exception->getMessage()));
        $connection->close();
    }
}
