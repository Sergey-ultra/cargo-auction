<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

final readonly class ListDTO
{
    public function __construct(
        public array $list,
        public int  $totalCount
    ){}
}
