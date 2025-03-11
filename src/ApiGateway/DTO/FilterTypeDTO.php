<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

use App\ApiGateway\Enum\FilterType;

final readonly class FilterTypeDTO
{
    public function __construct(
        public FilterType $type,
    ){
    }
}
