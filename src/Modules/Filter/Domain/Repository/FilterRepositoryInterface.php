<?php

namespace App\Modules\Filter\Domain\Repository;

use App\Modules\Filter\Domain\Entity\Filter;

interface FilterRepositoryInterface
{
    public function save(Filter $filter): void;
}
