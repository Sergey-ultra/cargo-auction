<?php

declare(strict_types=1);

namespace App\Modules\Load\Infrastructure\Api;

use App\ApiGateway\DTO\BidDTO;
use App\ApiGateway\DTO\BidsDTO;
use App\ApiGateway\DTO\CommentShowDTO;
use App\ApiGateway\DTO\CompanyShowDTO;
use App\ApiGateway\DTO\CompanyWithContactsDTO;
use App\ApiGateway\DTO\ContactDTO;
use App\ApiGateway\DTO\ListDTO;
use App\ApiGateway\DTO\LoadDTO;
use App\ApiGateway\DTO\LoadFilter;
use App\ApiGateway\DTO\LoadingDTO;
use App\ApiGateway\DTO\LoadInnerDTO;
use App\ApiGateway\DTO\LocationDTO;
use App\ApiGateway\DTO\RateDTO;
use App\ApiGateway\DTO\RatingDTO;
use App\ApiGateway\DTO\Request\LoadCreateDTO;
use App\ApiGateway\DTO\RouteDTO;
use App\ApiGateway\DTO\TruckDTO;
use App\ApiGateway\DTO\UnloadingDTO;
use App\Modules\Chat\Infrastructure\Adapter\UserAdapter;
use App\Modules\City\Infrastructure\DTO\CityCoordinatesDTO;
use App\Modules\City\Infrastructure\DTO\CityDTO;
use App\Modules\Load\Application\LoadService\LoadService;
use App\Modules\Load\Domain\Entity\Bid;
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
use App\ValueObject\Point;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class LoadApi
{
    const FUEL_CONSUMPTION = 28;

    const KM_PER_HOUR = 66;

    public function __construct(
        private LoadRepository $loadRepository,
        private CityAdapter $cityAdapter,
        private CompanyAdapter $companyAdapter,
        private UserAdapter $userAdapter,
        private CommentApi $commentApi,
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
        return BodyType::getTransformedTypes();
    }

    /** @return array<int, array{name: string, short_name: string, typeId: int} */
    public function getLoadingTypes(): array
    {
        return LoadingType::getTransformedTypes();
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
        bool           $isAuth = false,
        ?int           $commentUserId = null,
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
            $filter->volumeMax ?? null        );

        $result = $this->loadRepository->getList($apiFilter, $page, $perPage, $orderOption, $byUser);

        /** @var int[] $loadIds */
        $loadIds = [];
        /** @var int[] $companyIds */
        $companyIds = [];
        /** @var int[] $cityIds */
        $cityIds = [];
        /**  @var Load $load */
        foreach($result->list as $load) {
            $loadIds[] = $load->getId();
            $companyIds[] = $load->getCompanyId();
            $cityIds[] = $load->getFromCityId();
            $cityIds[] = $load->getToCityId();
        }
        $companyIds = array_unique($companyIds);


        if ($isAuth) {
            /** @var ArrayCollection<int, CompanyShowDTO> $companyCollection */
            $companyCollection = $this->companyAdapter->getByIds($companyIds);
            /** @var ArrayCollection<int, array> $userCollection */
            $userCollection = $this->userAdapter->getByCompanyIds($companyIds);
            /** @var ArrayCollection<int, CommentShowDTO> $commentCollection */
            $commentCollection = $commentUserId
                ? $this->commentApi->getByLoadIds($loadIds, $commentUserId)
                : new ArrayCollection();
        }

        $cityIds = array_unique($cityIds);
        /** @var ArrayCollection<int, CityDTO> $cityCollection */
        $cityCollection = $this->cityAdapter->getCitiesByIds($cityIds);

        /** @var LoadDTO[] $loads */
        $loads = [];

        /** @var Load $load */
        foreach($result->list as $load) {
            $companyDto = null;
            $comment = null;

            if ($isAuth) {
                /** @var CompanyShowDTO $company */
                $company = $companyCollection->get($load->getCompanyId());
                $companyContacts = $userCollection->get($load->getCompanyId());
                $companyDto = $this->buildCompanyDTO($company, $companyContacts);
                $comment = $commentCollection->get($load->getId());
            }

            $fromCity = $cityCollection->get($load->getFromCityId());
            $toCity = $cityCollection->get($load->getToCityId());

            $loads[] = $this->buildLoadDTO($load, $companyDto, $fromCity, $toCity, $comment);
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

    public function getLoadById(int $id, bool $isAuth = false): ?LoadDTO
    {
        $load = $this->loadRepository->getById($id);

        if (!$load) {
            return null;
        }

        $companyDto = null;
        if ($isAuth) {
            /** @var CompanyShowDTO $company */
            $companyDto = $this->buildCompanyDTOByCompanyId($load->getCompanyId());
        }


        /** @var ArrayCollection<int, CityDTO> $cityCollection */
        $cityCollection = $this->cityAdapter->getCitiesByIds([
            $load->getFromCityId(),
            $load->getToCityId(),
        ]);


        return $this->buildLoadDTO(
            $load,
            $companyDto,
            $cityCollection->get($load->getFromCityId()),
            $cityCollection->get($load->getToCityId()),
        );
    }

    private function buildLoadDTO(
        Load $item,
        ?CompanyWithContactsDTO $companyDto,
        CityDTO $fromCity,
        CityDTO $toCity,
        ?CommentShowDTO $comment = null,
        string $country = 'RUS'
    ): LoadDTO
    {
        $maxBid = 0;
        if ($item->getBids()->count() > 0) {
            $maxBid = max($item->getBids()->map(fn(Bid $bid) => $bid->getBid())->toArray());
        }

        $bids = new BidsDTO(
            $item->getBids()->count(),
            $maxBid,
            $item->getBids()->map(fn(Bid $bid) => new BidDTO($bid->getId(), $bid->getBid())),
        );

        $transitTime = $item->getDistance() / self::KM_PER_HOUR;

        $loadingTime = $item->getLoadingStartTime()?->format('H:i');

        if ($loadingTime && $item->getLoadingEndTime()?->format('H:i')) {
            $loadingTime .= '-' . $item->getLoadingEndTime()->format('H:i');
        }

        $unloadingTime = $item->getUnloadingStartTime()?->format('H:i');

        if ($unloadingTime && $item->getUnloadingEndTime()?->format('H:i')) {
            $unloadingTime .= '-' . $item->getUnloadingEndTime()->format('H:i');
        }

        return new LoadDTO(
            $item->getId(),
            new RouteDTO(
                $country,
                $item->getDistance(),
                0,
                (int)round(($item->getDistance() / 100) * self::FUEL_CONSUMPTION),
                (int)floor($transitTime),
                (int)ceil(($transitTime - floor($transitTime)) * 60)
            ),
            $bids,
            new LoadingDTO(
                new LocationDTO(
                    $fromCity->id,
                    $fromCity->name,
                    $fromCity->region,
                    $item->getFromAddress(),
                    $item->getFromLongitude(),
                    $item->getFromLatitude(),
                ),
                $item->getLoadingType(),
                $this->generateLoadingDate($item),
                $loadingTime,
            ),
            new UnloadingDTO(
                new LocationDTO(
                    $toCity->id,
                    $toCity->name,
                    $toCity->region,
                    $item->getToAddress(),
                    $item->getToLongitude(),
                    $item->getToLatitude(),
                ),
                $item->getUnloadingDate()?->format('d M'),
                $unloadingTime,
            ),
            new TruckDTO(
                $item->getBodyTypeName(),
                $item->getBodyTypeShortNames(),
                $item->getTruckLoadingTypeName(),
                $item->getTruckLoadingTypeShortNames(),
                $item->getTruckUnloadingTypeName(),
                $item->getTruckUnloadingTypeShortNames(),
            ),
            new LoadInnerDTO(
                $item->getCargoTypeName(),
                $item->getWeight(),
                $item->getVolume(),
                $item->getLoadType(),
            ),
            new RateDTO(
                $item->getPriceType(),
                $item->getPriceWithoutTax(),
                $item->getPriceWithTax(),
                $item->getPriceCash(),
                round($item->getPriceWithoutTax() / $item->getDistance(), 2),
                round($item->getPriceWithTax() / $item->getDistance(), 2),
                round($item->getPriceCash() / $item->getDistance(), 2),
            ),
            $item->getNote(),
            $comment,
            $item->getUser()->getId(),
            $item->getCreatedAt()->format('d M'),
            $item->getUpdatedAt()?->format('d M'),
            $companyDto,
        );
    }

    private function generateLoadingDate(Load $item): string
    {
        $loadingDate = $item->getLoadingFirstDate()->format('d');
        if (
            ($item->getLoadingLastDate() && (
                $item->getLoadingFirstDate()->format('M') !== $item->getLoadingLastDate()->format('M') ||
                $item->getLoadingFirstDate()->format('d M') === $item->getLoadingLastDate()->format('d M')
                )
            )
            || !$item->getLoadingLastDate()
        ) {
            $loadingDate .= ' ' . $item->getLoadingFirstDate()->format('M');
        }
        if ($item->getLoadingLastDate() && $item->getLoadingFirstDate()->format('d M') !== $item->getLoadingLastDate()->format('d M')) {
            $loadingDate .= '-' . $item->getLoadingLastDate()->format('d M');
        }

        return $loadingDate;
    }

    public function buildCompanyDTOByCompanyId(int $companyId): CompanyWithContactsDTO
    {
        $company = $this->companyAdapter->getById($companyId);
        /** @var User[] $companyContacts */
        $companyContacts = $this->userAdapter->getByCompanyId($companyId);
        return $this->buildCompanyDTO($company, $companyContacts);
    }

    /**
     * @var CompanyShowDTO $company
     * @var User[] $companyContacts
     * @return CompanyWithContactsDTO
     */
    private function buildCompanyDTO(CompanyShowDTO $company, array $companyContacts): CompanyWithContactsDTO
    {
        $contacts = $this->buildContacts($companyContacts);

        $companyCity = null !== $company->cityId
            ? $this->cityAdapter->getCityById($company->cityId)
            : null;

        return  new CompanyWithContactsDTO(
            $company->id,
            $company->fullName,
            isset($companyCity) ? $companyCity->name : '',
            $company->type,
            new RatingDTO(
                5
            ),
            $contacts,
        );
    }

    /**
     * @var int $companyId
     * @return ContactDTO[]
     */
    public function buildContactsByCompanyId(int $companyId): array
    {
        $companyContacts = $this->userAdapter->getByCompanyId($companyId);
        return $this->buildContacts($companyContacts);
    }

    /**
     * @var User[] $companyContacts
     * @return ContactDTO[]
     */
    protected function buildContacts(array $companyContacts): array
    {
        /** @var ContactDTO[] $contacts */
        $contacts = [];
        /**  @var User $userContact  */
        foreach($companyContacts as $companyContact) {
            $contacts[] = new ContactDTO(
                $companyContact->getId(),
                $companyContact->getName(),
                $companyContact->getPhone()?->getPhone(),
                $companyContact->getPhone()?->getMobilePhone(),
                $companyContact->getEmail(),
            );
        }

        return $contacts;
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

    public function saveLoad(LoadCreateDTO $createDto, UserInterface $user): int
    {
        $load = new Load();
        $load
            ->setCargoType($createDto->loading->cargos->type)
            ->setWeight($createDto->loading->cargos->weight)
            ->setVolume($createDto->loading->cargos->volume)
            ->setLoadType($createDto->truck->loadType)
            ->setLoadTemperatureFrom($createDto->truck->temperatureFrom)
            ->setLoadTemperatureTo($createDto->truck->temperatureTo)
            ->setLoadingType($createDto->loading->dates->type)
            ->setLoadingFirstDate($createDto->loading->dates->firstDate)
            ->setLoadingLastDate($createDto->loading->dates->lastDate)
            ->setLoadingPeriodicity($createDto->loading->dates->periodicity)
            ->setLoadingStartTime($createDto->loading->dates->time->start)
            ->setLoadingEndTime($createDto->loading->dates->time->end)
            ->setUnloadingDate($createDto->unloading->dates->firstDate)
            ->setUnloadingStartTime($createDto->unloading->dates->time->start)
            ->setUnloadingEndTime($createDto->unloading->dates->time->end)
            ->setFromCityId($createDto->loading->location->cityId)
            ->setFromAddress($createDto->loading->location->address)
            ->setToLatitude($createDto->loading->location->coordinates->latitude)
            ->setToLongitude($createDto->loading->location->coordinates->longitude)
            ->setToPoint(
                new Point(
                $createDto->loading->location->coordinates->latitude,
                    $createDto->loading->location->coordinates->longitude
                )
            )
            ->setToCityId($createDto->unloading->location->cityId)
            ->setToAddress($createDto->unloading->location->address)
            ->setToLatitude($createDto->unloading->location->coordinates->latitude)
            ->setToLongitude($createDto->unloading->location->coordinates->longitude)
            ->setToPoint(
                new Point(
                    $createDto->unloading->location->coordinates->latitude,
                    $createDto->unloading->location->coordinates->longitude
                )
            )
            ->setBodyTypes($createDto->truck->bodyTypes)
            ->setTruckLoadingTypes($createDto->truck->loadingTypes)
            ->setTruckUnloadingTypes($createDto->truck->unloadingTypes)
            ->setPriceType($createDto->payment->type)
            ->setPriceWithoutTax((int)$createDto->payment->priceWithoutTax)
            ->setPriceWithTax((int)$createDto->payment->priceWithTax)
            ->setPriceCash((int)$createDto->payment->priceCash)
            ->setPaymentOnCard($createDto->payment->onCard)
            ->setHideCounterOffers($createDto->payment->hideCounterOffers)
            ->setAcceptBidsWithVat($createDto->payment->acceptBidsWithVat)
            ->setAcceptBidsWithoutVat($createDto->payment->acceptBidsWithoutVat)
            ->setContactIds($createDto->contactIds)
            ->setFiles($createDto->files)
            ->setNote($createDto->note)
            ->setUser($user)
            ->setCompanyId($user->getCompanyId())
            ->setCreatedAt()
            ->setUpdatedAt();

//        $routeCities = $this->cityAdapter->getCitiesByIds([
//            $createDto->loading->location->cityId,
//            $createDto->unloading->location->cityId,
//        ]);
//
//        $fromCity = $routeCities->get($createDto->loading->location->cityId);
//        $toCity = $routeCities->get( $createDto->unloading->location->cityId);
//
//        $this->loadService->save($load, $fromCity, $toCity);

        $this->loadRepository->save($load);
        return $load->getId();
    }
}
