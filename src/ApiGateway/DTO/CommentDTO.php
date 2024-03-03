<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class CommentDTO
{
    public function __construct(
        #[NotBlank(message: 'Комментарий не может быть пустым')]
        #[Type('string')]
        public string $comment,
    ){}
}
