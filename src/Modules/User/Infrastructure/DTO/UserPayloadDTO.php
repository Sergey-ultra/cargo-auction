<?php

declare(strict_types=1);

namespace App\Modules\User\Infrastructure\DTO;

final readonly class UserPayloadDTO
{
    public function __construct(
        public string $name,
        public string $email,
        public string $plainPassword,
        public array $roles,
    ){}
}
