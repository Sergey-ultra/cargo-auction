<?php

declare(strict_types=1);

namespace App\Modules\Load\Infrastructure\Api;

use App\ApiGateway\DTO\CompanyDTO;
use App\ApiGateway\DTO\ContactDTO;
use App\ApiGateway\DTO\ListDTO;
use App\ApiGateway\DTO\LoadCreateDTO;
use App\ApiGateway\DTO\LoadDTO;
use App\ApiGateway\DTO\LoadFilter;
use App\Modules\Chat\Infrastructure\Adapter\UserAdapter;
use App\Modules\City\Infrastructure\DTO\CityCoordinatesDTO;
use App\Modules\Company\Domain\Entity\Company;
use App\Modules\Load\Application\LoadService\LoadService;
use App\Modules\Load\Domain\Entity\BodyType;
use App\Modules\Load\Domain\Entity\CargoType;
use App\Modules\Load\Domain\Entity\Load;
use App\Modules\Load\Domain\Entity\LoadingType;
use App\Modules\Load\Domain\Entity\PackageType;
use App\Modules\Load\Domain\Repository\LoadRepositoryInterface;
use App\Modules\Load\Infrastructure\Adapter\CityAdapter;
use App\Modules\Load\Infrastructure\DTO\FilterDTO;
use App\Modules\Load\Infrastructure\Repository\LoadRepository;
use App\Modules\Transport\Infrastructure\Adapter\CompanyAdapter;
use App\Modules\User\Domain\Entity\User;
use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class LoadApi
{
    public function __construct(
        private LoadRepository $loadRepository,
        private LoadService $loadService,
        private CityAdapter $cityAdapter,
        private CompanyAdapter $companyAdapter,
        private UserAdapter $userAdapter
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
        return LoadRepositoryInterface::OPTIONS;
    }

    public function getBodyTypes(): array
    {
        return array_column(BodyType::BODY_TYPES, 'Name');
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
        return LoadRepositoryInterface::CREATED_AT;
    }

    public function getPriceTypes(): array
    {
        return Load::PRICE_TYPE;
    }

    public function getList(
        ?LoadFilter    $filter,
        int            $page = 1,
        int            $perPage = LoadRepositoryInterface::PAGINATOR_PER_PAGE,
        string         $orderOption = LoadRepositoryInterface::CREATED_AT,
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

        $result = $this->loadRepository->getList($apiFilter, $page, $perPage, $orderOption, $byUser);
        $companyIds = [];
        /**  @var Load $item */
        foreach($result->list as $item) {
            $companyIds[] = $item->getCompanyId();
        }
        $companyIds = array_unique($companyIds);

        $companyCollection = $this->companyAdapter->getByIds($companyIds);
        $userCollection = $this->userAdapter->getByCompanyIds($companyIds);

        $loads = [];
        /**  @var Load $item */
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

            $loads[] = new LoadDTO(
                $item->getId(),
                $item->getUser()->getId(),
                $item->getDownloadingDateStatus(),
                $item->getDownloadingDate(),
                $item->getBids(),
                $item->getFromAddress(),
                $item->getToAddress(),
                $item->getCargoTypeName(),
                $item->getBodyTypeName(),
                $item->getWeight(),
                $item->getVolume(),
                $item->getPriceWithoutTax(),
                $item->getPriceWithTax(),
                $item->getPriceCash(),
                $item->getDownloadingTypeName(),
                $item->getUnloadingTypeName(),
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


        return new ListDTO($loads, $result->totalCount);
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

    public function saveLoad(LoadCreateDTO $createDto, UserInterface $user): Load
    {
        $load = new Load();
        $load
            ->setDownloadingDateStatus($createDto->downloadingDateStatus)
            ->setDownloadingDate(new DateTime($createDto->downloadingDate))
            ->setFromAddress($createDto->fromAddress)
            ->setToAddress($createDto->toAddress)
            ->setWeight($createDto->weight)
            ->setVolume($createDto->volume)
            ->setPriceType($createDto->priceType)
            ->setPriceWithoutTax((int)$createDto->priceWithoutTax)
            ->setPriceWithTax((int)$createDto->priceWithTax)
            ->setPriceCash((int)$createDto->priceCash)
            ->setCargoType((int)$createDto->cargoType)
            ->setBodyType((int)$createDto->bodyType)
            ->setDownloadingType((int)$createDto->downloadingType)
            ->setUnloadingType((int)$createDto->unloadingType)
            ->setUser($user)
            ->setCreatedAt()
            ->setUpdatedAt();

        $this->loadService->save($load);
        return $load;
    }
}
