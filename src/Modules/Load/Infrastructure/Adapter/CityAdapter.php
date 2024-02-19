<?php

declare(strict_types=1);

namespace App\Modules\Load\Infrastructure\Adapter;

use App\ApiGateway\DTO\LoadFilter;
use App\Modules\City\Infrastructure\Api\CityApi;
use App\Modules\Load\Infrastructure\DTO\CityCoordinatesDTO;

final readonly class CityAdapter
{
    public function __construct(public CityApi $cityApi)
    {
    }

    public function getCityCoordinatesByCityId(int $id): ?CityCoordinatesDTO
    {
        $city = $this->cityApi->getCityById($id);
        return new CityCoordinatesDTO($city->getLon(), $city->getLat());
    }

    public function getCityCoordinatesByCityByName(string $name): ?CityCoordinatesDTO
    {
        $city = $this->cityApi->getCityByName($name);
        return new CityCoordinatesDTO($city->getLon(), $city->getLat());
    }

}
