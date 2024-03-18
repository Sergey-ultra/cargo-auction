<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

use App\ApiGateway\Enum\FilterType;

final readonly class FilterShowDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public array $filter,
        public FilterType $type,
        public string $createdAt,
    ){}
}
