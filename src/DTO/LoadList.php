<?php

declare(strict_types=1);

namespace App\DTO;

class LoadList
{
    public function __construct(
        public readonly array $list,
        public readonly int $totalCount
    ){}
}
