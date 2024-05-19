<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

final class CityFilterDTO
{
    public ?string $name = '';
    public ?string $lang = 'RU';
    public ?string $type;
}
