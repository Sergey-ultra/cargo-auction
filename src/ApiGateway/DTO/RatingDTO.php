<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

final readonly class RatingDTO
{
    public function __construct(
        public int $score,
    )
    {
    }
}
