<?php

declare(strict_types=1);

namespace App\Modules\Load\Infrastructure\DTO;

final readonly class LoadListDTO
{
    public function __construct(
        public array $list,
        public int   $totalCount
    ){}
}
