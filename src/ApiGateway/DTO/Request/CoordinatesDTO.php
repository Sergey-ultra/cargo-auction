<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO\Request;

use Symfony\Component\Validator\Constraints\NotBlank;

final readonly class CoordinatesDTO
{
    public function __construct(
        #[NotBlank(message: 'Широта не может быть пустым')]
        public float $latitude,
        #[NotBlank(message: 'Долгота не может быть пустым')]
        public float $longitude,
    )
    {
    }
}
