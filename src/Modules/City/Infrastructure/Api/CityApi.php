<?php

declare(strict_types=1);

namespace App\Modules\City\Infrastructure\Api;

use App\Modules\City\Domain\Entity\City;
use App\Modules\City\Infrastructure\DTO\CityDTO;
use App\Modules\City\Infrastructure\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;

final readonly class CityApi
{
    public function __construct(private CityRepository $cityRepository)
    {
    }

    /** @return City[] */
    public function searchCitiesByName(string $name): array
    {
        if ($name === "") {
            return $this->cityRepository->getMainCities();
        }
        return $this->cityRepository->searchByName($name);
    }

    /**
     * @param string[] $cities
     * @return City[]
     */
    public function searchCitiesByNames(array $cities): array
    {
        return $this->cityRepository->searchByNames($cities);
    }

    public function getCityById(int $id): ?CityDTO
    {
        $city = $this->cityRepository->find($id);
        if (!$city) {
            return null;
        }

        return new CityDTO(
            $city->getId(),
            $city->getName(),
            $city->getRegionName(),
            $city->getLon(),
            $city->getLat(),
        );
    }

    /**
     * @param int[] $ids
     * @return ArrayCollection<int, CityDTO>
     */
    public function getCitiesByIds(array $ids): ArrayCollection
    {
        $list = $this->cityRepository->findBy(['id' => $ids]);
        $collection = new ArrayCollection();

        foreach($list as $city) {
            $collection->set(
                $city->getId(),
                new CityDTO(
                    $city->getId(),
                    $city->getName(),
                    $city->getRegionName(),
                    $city->getLon(),
                    $city->getLat(),
                )
            );
        }

        return $collection;
    }

    public function getCityByName(string $name): ?CityDTO
    {
        $city =  $this->cityRepository->findOneBy(['name' => $name]);

        if (!$city) {
            return null;
        }

        return new CityDTO(
            $city->getId(),
            $city->getName(),
            $city->getRegionName(),
            $city->getLon(),
            $city->getLat(),
        );
    }
}
