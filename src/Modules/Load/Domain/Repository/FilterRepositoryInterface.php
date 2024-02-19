<?php

namespace App\Modules\Load\Domain\Repository;

use App\Modules\Load\Domain\Entity\Filter;

interface FilterRepositoryInterface
{
    public function save(Filter $filter): void;
}
