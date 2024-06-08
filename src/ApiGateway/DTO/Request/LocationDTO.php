<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO\Request;

use Symfony\Component\Validator\Constraints\NotBlank;

final readonly class LocationDTO
{
    public function __construct(
        #[NotBlank(message: 'Id города не может быть пустым')]
        public int $cityId,
        #[NotBlank(message: 'Aдрес не может быть пустым')]
        public string $address,
        public CoordinatesDTO $coordinates,
    )
    {
    }
}
