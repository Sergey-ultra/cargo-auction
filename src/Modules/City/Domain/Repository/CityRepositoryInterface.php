<?php

namespace App\Modules\City\Domain\Repository;

use App\Modules\City\Domain\Entity\City;

interface CityRepositoryInterface
{
    /** @return City[] */
    public function searchByName(string $name): array;
}
