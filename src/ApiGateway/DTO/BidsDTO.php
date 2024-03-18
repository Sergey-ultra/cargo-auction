<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

use Doctrine\Common\Collections\Collection;

final readonly class BidsDTO
{
    public function __construct(
        public int $count,
        public int $maxValue,
        /** @var Collection<BidDTO> */
        public Collection $bids,
    )
    {
    }
}
