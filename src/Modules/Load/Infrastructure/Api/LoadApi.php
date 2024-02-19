<?php

declare(strict_types=1);

namespace App\Modules\Load\Infrastructure\Api;

use App\ApiGateway\DTO\LoadFilter;
use App\ApiGateway\DTO\LoadList;
use App\Modules\Load\Application\LoadService\LoadService;
use App\Modules\Load\Domain\Entity\BodyType;
use App\Modules\Load\Domain\Entity\CargoType;
use App\Modules\Load\Domain\Entity\Load;
use App\Modules\Load\Domain\Entity\LoadingType;
use App\Modules\Load\Domain\Entity\PackageType;
use App\Modules\Load\Infrastructure\Adapter\CityAdapter;
use App\Modules\Load\Infrastructure\DTO\CityCoordinatesDTO;
use App\Modules\Load\Infrastructure\DTO\LoadFilterDTO;
use App\Modules\Load\Infrastructure\Repository\LoadRepository;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class LoadApi
{
    public function __construct(
        private LoadRepository $loadRepository,
        private LoadService $loadService,
        private CityAdapter $cityAdapter
    )
    {
    }

    public function getCargoTypes(): array
    {
        return CargoType::CARGO_TYPES;
    }

    public function getPackageType(): array
    {
        return PackageType::PACKAGE_TYPES;
    }

    public function getLoadOption(): array
    {
        return LoadRepository::LOAD_OPTIONS;
    }

    public function getBodyTypes(): array
    {
        return BodyType::BODY_TYPES;
    }

    public function getLoadingTypes(): array
    {
        return LoadingType::LOADING_TYPES;
    }

    public function getDownloadingDateTitles(): array
    {
        return Load::DOWNLOADING_DATE_TITLES;
    }

    public function getDefaultOrderByOption(): string
    {
        return LoadRepository::LOAD_CREATED_AT;
    }



    public function getList(
        ?LoadFilter    $filter,
        int            $page = 1,
        int            $perPage = LoadRepository::PAGINATOR_PER_PAGE,
        string         $orderOption = LoadRepository::LOAD_CREATED_AT,
        ?UserInterface $byUser = null
    ): LoadList
    {
        if (isset($filter->fromAddress) && isset($filter->fromRadius)) {
            $fromCity = $this->getCityCoordinatesByCityId($filter, 'fromAddressId', 'fromAddress');
        }

        if (isset($filter->toAddress) && isset($filter->toRadius)) {
            $toCity = $this->getCityCoordinatesByCityId($filter, 'toAddressId', 'toAddress');
        }

        $apiFilter = new LoadFilterDTO(
            isset($fromCity) ? $fromCity->longitude : null,
            isset($fromCity) ? $fromCity->latitude : null,
            $filter->fromRadius ?? null,
            isset($toCity) ? $toCity->longitude : null,
            isset($toCity) ? $toCity->latitude : null,
            $filter->toRadius ?? null,
            $filter->weightMin ?? null,
            $filter->weightMax ?? null,
            $filter->volumeMin ?? null,
            $filter->volumeMax ?? null
        );

        $result =  $this->loadRepository->getList($apiFilter, $page, $perPage, $orderOption, $byUser);

        return new LoadList($result->list, $result->totalCount);
    }

    private function getCityCoordinatesByCityId(LoadFilter $filter, string $cityIdKey, string $address): ?CityCoordinatesDTO
    {
        if (isset($filter->{$cityIdKey})) {
            $city = $this->cityAdapter->getCityCoordinatesByCityId($filter->{$cityIdKey});
        } else {
            $city = $this->cityAdapter->getCityCoordinatesByCityByName($filter->{$address});
        }
        return $city;
    }

    public function getLoadById(int $id): ?Load
    {
        return $this->loadRepository->find($id);
    }

    public function getLoadDraftMessageById(int $id): string
    {
        $load = $this->loadRepository->find($id);
        return sprintf(
            "По грузу: %s - %s, %d, %s, %d т., %d м3",
            $load->getFromAddress(),
            $load->getToAddress(),
            34,
            $load->getCargoType(),
            $load->getWeight(),
            $load->getVolume()
        );
    }

    public function saveLoad(Load $load): void
    {
        $this->loadService->save($load);
    }
}
