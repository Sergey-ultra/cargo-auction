<?php

declare(strict_types=1);

namespace App\Modules\Load\Infrastructure\DTO;

use App\Modules\Load\Domain\Entity\Load;

final readonly class LoadListDTO
{
    public function __construct(
        /** @var Load[] */
        public array $list,
        public int   $totalCount
    ){}
}
