<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class CommentRequestDTO
{
    public function __construct(
        #[NotBlank(message: 'Комментарий не может быть пустым')]
        #[Type('string')]
        public string $comment,
        #[NotBlank(message: 'Комментарий не может быть пустым')]
        #[Type(type: 'int', message: 'EntityId не должно быть пустым')]
        public int $entityId,
    ){}
}
