<?php

declare(strict_types=1);

namespace App\Modules\Transport\Infrastructure\Api;

use App\ApiGateway\DTO\CompanyDTO;
use App\ApiGateway\DTO\ContactDTO;
use App\ApiGateway\DTO\ListDTO;
use App\ApiGateway\DTO\LoadFilter;
use App\ApiGateway\DTO\TransportDTO;
use App\Modules\Chat\Infrastructure\Adapter\UserAdapter;
use App\Modules\City\Infrastructure\DTO\CityCoordinatesDTO;
use App\Modules\Company\Domain\Entity\Company;
use App\Modules\Load\Infrastructure\Adapter\CityAdapter;
use App\Modules\Transport\Domain\Entity\Transport;
use App\Modules\Transport\Domain\Repository\TransportRepositoryInterface;
use App\Modules\Transport\Infrastructure\Adapter\CompanyAdapter;
use App\Modules\Transport\Infrastructure\DTO\FilterDTO;
use App\Modules\Transport\Infrastructure\Repository\TransportRepository;
use App\Modules\User\Domain\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class TransportApi
{
    public function __construct(
        private TransportRepository $transportRepository,
        private CityAdapter $cityAdapter,
        private CompanyAdapter $companyAdapter,
        private UserAdapter $userAdapter,
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
    ): ListDTO
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

        $companyIds = [];
        foreach($result->list as $item) {
            $companyIds[] = $item->getCompanyId();
        }

        $companyCollection = $this->companyAdapter->getByIds($companyIds);
        $userCollection = $this->userAdapter->getByCompanyIds($companyIds);



        $transports = [];
        /**  @var Transport $item */
        foreach($result->list as $item) {
            /**  @var Company $company */
            $company = $companyCollection->get($item->getCompanyId());
            $userContacts = $userCollection->get($item->getCompanyId());

            $contacts = [];
            /**  @var User $userContact  */
            foreach($userContacts as $userContact) {
                $contacts[] = new ContactDTO(
                    $userContact->getId(),
                    $userContact->getName(),
                    $userContact->getPhone()->getPhone(),
                    $userContact->getPhone()->getMobilePhone(),
                );
            }

            $transports[] = new TransportDTO(
                $item->getId(),
                $item->getFromName(),
                $item->getToName(),
                $item->getBodyTypeName(),
                $item->getWeight(),
                $item->getVolume(),
                $item->getPriceWithoutTax(),
                $item->getPriceWithTax(),
                $item->getPriceCash(),
                $item->getCreatedAt(),
                $item->getUpdatedAt(),
                new CompanyDTO(
                    $company->getId(),
                    $company->getName() . ', ' . $company->getOwnershipName(),
                    $company->getTypeName(),
                    $contacts,
                )
            );
        }

        return new ListDTO($transports, $result->totalCount);
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
