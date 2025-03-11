<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final readonly class MessageCreateDTO
{
    public function __construct(
        #[NotBlank(message: 'Сообщение не может быть пустым')]
        #[Type('string')]
        public string $message,
    ){}
}
