<?php

namespace App\Modules\City\Domain\Repository;

interface CityRepositoryInterface
{
    public function searchByName(string $name): array;
}
