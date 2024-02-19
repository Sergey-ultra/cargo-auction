<?php

declare(strict_types=1);

namespace App\Modules\Transport\Infrastructure\Api;

use App\ApiGateway\DTO\LoadFilter;
use App\ApiGateway\DTO\LoadList;
use App\Modules\City\Infrastructure\DTO\CityCoordinatesDTO;
use App\Modules\Load\Infrastructure\Adapter\CityAdapter;
use App\Modules\Transport\Domain\Repository\TransportRepositoryInterface;
use App\Modules\Transport\Infrastructure\DTO\FilterDTO;
use App\Modules\Transport\Infrastructure\Repository\TransportRepository;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class TransportApi
{
    public function __construct(
        private TransportRepository $transportRepository,
        private CityAdapter $cityAdapter
    )
    {
    }

    public function getDefaultOrderByOption(): string
    {
        return TransportRepositoryInterface::CREATED_AT;
    }

    public function getList(
        ?LoadFilter    $filter,
        int            $page = 1,
        int            $perPage = TransportRepositoryInterface::PAGINATOR_PER_PAGE,
        string         $orderOption = TransportRepositoryInterface::CREATED_AT,
        ?UserInterface $byUser = null
    ): LoadList
    {
        if (isset($filter->fromAddress) && isset($filter->fromRadius)) {
            $fromCity = $this->getCityCoordinatesByCityId($filter, 'fromAddressId', 'fromAddress');
        }

        if (isset($filter->toAddress) && isset($filter->toRadius)) {
            $toCity = $this->getCityCoordinatesByCityId($filter, 'toAddressId', 'toAddress');
        }

        $apiFilter = new FilterDTO(
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

        $result =  $this->transportRepository->getList($apiFilter, $page, $perPage, $orderOption, $byUser);

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
}
