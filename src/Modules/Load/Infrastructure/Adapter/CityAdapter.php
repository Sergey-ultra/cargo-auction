<?php

declare(strict_types=1);

namespace App\Modules\Load\Infrastructure\Adapter;

use App\Modules\City\Infrastructure\Api\CityApi;
use App\Modules\City\Infrastructure\DTO\CityCoordinatesDTO;
use App\Modules\City\Infrastructure\DTO\CityDTO;
use Doctrine\Common\Collections\ArrayCollection;

final readonly class CityAdapter
{
    public function __construct(public CityApi $cityApi)
    {
    }

    public function getCityById(int $id): ?CityDTO
    {
        return $this->cityApi->getCityById($id);
    }

    /**
     * @param int[] $ids
     * @return ArrayCollection<int, CityDTO>
     */
    public function getCitiesByIds(array $ids): ArrayCollection
    {
        return$this->cityApi->getCitiesByIds($ids);
    }

    public function getCityCoordinatesByCityId(int $id): ?CityCoordinatesDTO
    {
        $city = $this->cityApi->getCityById($id);
        if (!$city) {
            return null;
        }
        return new CityCoordinatesDTO($city->longitude, $city->latitude);
    }

    public function getCityCoordinatesByCityByName(string $name): ?CityCoordinatesDTO
    {
        $city = $this->cityApi->getCityByName($name);
        if (!$city) {
            return null;
        }
        return new CityCoordinatesDTO($city->longitude, $city->latitude);
    }

}
