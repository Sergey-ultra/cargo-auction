<?php

declare(strict_types=1);

namespace App\Modules\Load\Infrastructure\DTO;

final readonly class FilterDTO
{
    public function __construct(
        public ?float  $fromLongitude,
        public ?float  $fromLatitude,
        public ?string $fromRadius,
        public ?float  $toLongitude,
        public ?float  $toLatitude,
        public ?string $toRadius,
        public ?string $weightMin,
        public ?string $weightMax,
        public ?string $volumeMin,
        public ?string $volumeMax
    )
    {
    }
}
