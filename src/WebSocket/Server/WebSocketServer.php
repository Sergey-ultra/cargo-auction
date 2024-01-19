<?php

declare(strict_types=1);

namespace App\WebSocket\Server;

use App\WebSocket\Enum\MessageTypeEnum;
use Psr\Http\Message\RequestInterface;
use Psr\Log\LoggerInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Http\HttpServerInterface;
use Ratchet\MessageComponentInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use GuzzleHttp\Psr7\Request;

class WebSocketServer implements HttpServerInterface
{
    private static $cookieParts = array(
        'domain'      => 'Domain',
        'path'        => 'Path',
        'max_age'     => 'Max-Age',
        'expires'     => 'Expires',
        'version'     => 'Version',
        'secure'      => 'Secure',
        'port'        => 'Port',
        'discard'     => 'Discard',
        'comment'     => 'Comment',
        'comment_url' => 'Comment-Url',
        'http_only'   => 'HttpOnly'
    );

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

    private function parseCookie($cookie, $host = null, $path = null, $decode = false) {
        // Explode the cookie string using a series of semicolons
        $pieces = array_filter(array_map('trim', explode(';', $cookie)));

        // The name of the cookie (first kvp) must include an equal sign.
        if (empty($pieces) || !strpos($pieces[0], '=')) {
            return false;
        }

        // Create the default return array
        $data = array_merge(array_fill_keys(array_keys(self::$cookieParts), null), array(
            'cookies'   => array(),
            'data'      => array(),
            'path'      => $path ?: '/',
            'http_only' => false,
            'discard'   => false,
            'domain'    => $host
        ));
        $foundNonCookies = 0;

        // Add the cookie pieces into the parsed data array
        foreach ($pieces as $part) {

            $cookieParts = explode('=', $part, 2);
            $key = trim($cookieParts[0]);

            if (count($cookieParts) == 1) {
                // Can be a single value (e.g. secure, httpOnly)
                $value = true;
            } else {
                // Be sure to strip wrapping quotes
                $value = trim($cookieParts[1], " \n\r\t\0\x0B\"");
                if ($decode) {
                    $value = urldecode($value);
                }
            }

            // Only check for non-cookies when cookies have been found
            if (!empty($data['cookies'])) {
                foreach (self::$cookieParts as $mapValue => $search) {
                    if (!strcasecmp($search, $key)) {
                        $data[$mapValue] = $mapValue == 'port' ? array_map('trim', explode(',', $value)) : $value;
                        $foundNonCookies++;
                        continue 2;
                    }
                }
            }

            // If cookies have not yet been retrieved, or this value was not found in the pieces array, treat it as a
            // cookie. IF non-cookies have been parsed, then this isn't a cookie, it's cookie data. Cookies then data.
            $data[$foundNonCookies ? 'data' : 'cookies'][$key] = $value;
        }

        // Calculate the expires date
        if (!$data['expires'] && $data['max_age']) {
            $data['expires'] = time() + (int) $data['max_age'];
        }

        return $data;
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
    public function onClose(ConnectionInterface $connection): void
    {
        try {
            $this->clients->detach($connection);
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
