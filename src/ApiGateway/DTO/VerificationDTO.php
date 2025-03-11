<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

use Symfony\Component\Validator\Constraints\Email;

final readonly class VerificationDTO
{
    public function __construct(
        #[Email(message: '{{ value }} должен быть валидный')]
        public string $email,
    )
    {
    }
}
