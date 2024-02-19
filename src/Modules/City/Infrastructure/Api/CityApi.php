<?php

declare(strict_types=1);

namespace App\Modules\City\Infrastructure\Api;

use App\ApiGateway\DTO\LoadFilter;
use App\Modules\City\Domain\Entity\City;
use App\Modules\City\Infrastructure\Repository\CityRepository;

final readonly class CityApi
{
    public function __construct(private CityRepository $cityRepository)
    {
    }

    public function searchCitiesByName(string $name): array
    {
        return $this->cityRepository->searchByName($name);
    }

    public function getCityById(int $id): ?City
    {
        return $this->cityRepository->find($id);
    }

    public function getCityByName(string $name): ?City
    {
        return $this->cityRepository->findOneBy(['name' => $name]);
    }
}
