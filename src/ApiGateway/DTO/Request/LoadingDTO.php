<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final readonly class LoadingDTO
{
    public function __construct(
        public CargoDTO $cargos,
        public LocationDTO $location,
        public DatesDTO $dates,
    )
    {
    }
}
