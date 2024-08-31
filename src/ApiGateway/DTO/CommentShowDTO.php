<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

class CommentShowDTO
{
    public function __construct(
        public int $id,
        public string $comment,
        public int $entityId,
    ){
    }
}
