<?php

declare(strict_types=1);

namespace App\DTO;

readonly class LoadList
{
    public function __construct(
        public array $list,
        public int   $totalCount
    ){}
}
