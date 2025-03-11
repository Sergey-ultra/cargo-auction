<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

final readonly class CommentDTO
{
    public function __construct(
        public ?int $id,
        public string $comment,
        public int $userId,
        public int $entityId,
    ){
    }
}
