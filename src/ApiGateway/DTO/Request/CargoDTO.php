<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final readonly class CargoDTO
{
        public function __construct(
            #[NotBlank(message: 'Вес не может быть пустым')]
            #[Type(type: 'float', message: 'Значение должно быть float')]
            public float $weight,
            #[NotBlank(message: 'Объем не может быть пустым')]
            #[Type(type: 'float', message:'Значение должно быть float')]
            public float $volume,
            #[NotBlank(message: 'Тип груза не может быть пустым')]
            public int $type,
        )
        {
        }
}
