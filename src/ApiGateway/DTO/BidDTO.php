<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

final readonly class BidDTO
{
    public function __construct(
       public int $id,
       public int $bid,
    )
    {
    }
}
