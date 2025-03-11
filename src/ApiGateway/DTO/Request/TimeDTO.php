<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO\Request;

use DateTimeInterface;

final readonly class TimeDTO
{
    public function __construct(
        public ?DateTimeInterface $start,
        public ?DateTimeInterface $end,
    )
    {
    }
}
