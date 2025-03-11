<?php

declare(strict_types=1);

namespace App\Modules\Transport\Infrastructure\DTO;

final readonly class CityParsingDTO
{
    public function __construct(
        public int $id,
        public int $type
    ){}
}
