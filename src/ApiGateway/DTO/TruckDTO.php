<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

final readonly class TruckDTO
{
    public function __construct(
        public string $bodyType,
        public string $bodyTypeShort,
        public string $loadingType,
        public string $loadingTypeShort,
        public string $unloadingType,
        public string $unloadingTypeShort,
    )
    {
    }
}
