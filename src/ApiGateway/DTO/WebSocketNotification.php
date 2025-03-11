<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

readonly class WebSocketNotification
{
    public function __construct(
        public string $message,
        public int $userId,
    ){}

    /** @return array{message: string, userId: int} */
    public function toArray(): array
    {
        return (array)$this;
    }

    public function toJson(): string
    {
        return json_encode($this);
    }
}
