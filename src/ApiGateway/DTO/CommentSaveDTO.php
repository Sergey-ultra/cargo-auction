<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final readonly class CommentSaveDTO
{
    public function __construct(
        public ?int $id,
        #[NotBlank(message: 'Комментарий не может быть пустым')]
        #[Type('string')]
        public string $comment,
        #[NotBlank(message: 'Комментарий не может быть пустым')]
        #[Type(type: 'int', message: 'EntityId не должно быть пустым')]
        public int $entityId,
    ){}
}
