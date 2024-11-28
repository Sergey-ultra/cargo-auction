<?php

declare(strict_types=1);

namespace App\Modules\Transport\Infrastructure\DTO;

use App\Modules\Transport\Domain\Entity\Transport;

final readonly class ListDTO
{
    public function __construct(
        /** @var Transport[] */
        public array $list,
        public int   $totalCount
    ){}
}
