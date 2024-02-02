<?php

declare(strict_types=1);

namespace App\DTO;

final readonly class FilterSaveDTO
{
    public function __construct(
        public string $name,
        public LoadFilter $filter
    ){}
}
