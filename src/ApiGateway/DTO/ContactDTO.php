<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

final readonly class ContactDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $phone,
        public ?string $mobilePhone,
        public ?string $email,
    )
    {
    }
}
