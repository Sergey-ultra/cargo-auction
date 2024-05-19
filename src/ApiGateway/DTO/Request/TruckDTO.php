<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final readonly class TruckDTO
{
    public function __construct(
        #[NotBlank(message: 'Кузов не может быть пустым')]
        public array $bodyTypes,
        #[NotBlank(message: 'Тип загрузки не может быть пустым')]
        #[Type('array')]
        public array $loadingTypes,
        #[NotBlank(message: 'Тип выгрузки не может быть пустым')]
        #[Type('array')]
        public array $unloadingTypes,
        #[NotBlank(message: 'Тип загрузки не может быть пустым')]
        public string $loadType,
        public ?int $temperatureFrom,
        public ?int $temperatureTo,
    )
    {
    }
}
