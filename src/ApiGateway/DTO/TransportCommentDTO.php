<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

final readonly class TransportCommentDTO
{
    public function __construct(
        public string $comment,
        public string $userName,
    ){}
}
