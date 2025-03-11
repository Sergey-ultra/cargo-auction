<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

use App\ApiGateway\Enum\FilterType;

final readonly class FilterSaveDTO
{
    public function __construct(
        public string $name,
        public LoadFilter $filter,
        public FilterType $type,
    ){}
}
