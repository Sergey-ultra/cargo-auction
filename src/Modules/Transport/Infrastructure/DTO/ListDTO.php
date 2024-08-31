<?php

declare(strict_types=1);

namespace App\Modules\Transport\Infrastructure\DTO;

final readonly class ListDTO
{
    public function __construct(
        public array $list,
        public int   $totalCount
    ){}
}
