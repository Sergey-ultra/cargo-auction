<?php

declare(strict_types=1);

namespace App\Modules\Transport\Infrastructure\Adapter;

use App\Modules\City\Domain\Entity\City;
use App\Modules\City\Infrastructure\Api\CityApi;

final readonly class CityAdapter
{
    public function __construct(private CityApi $cityApi)
    {
    }

    /** @return City[] */
    public function searchCitiesByName(string $name): array
    {
        return $this->cityApi->searchCitiesByName($name);
    }

    /**
     * @param string[] $cities
     * @return City[]
     */
    public function searchCitiesByNames(array $cities): array
    {
        return $this->cityApi->searchCitiesByNames($cities);
    }
}
