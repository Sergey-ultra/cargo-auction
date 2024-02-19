<?php

namespace App\Modules\Load\Domain\Repository;

use App\Modules\Load\Domain\Entity\Bid;

interface BidRepositoryInterface
{
    public function save(Bid $bid): void;
}
