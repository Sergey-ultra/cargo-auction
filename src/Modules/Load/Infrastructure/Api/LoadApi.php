<?php

declare(strict_types=1);

namespace App\Modules\Load\Infrastructure\Api;

use App\ApiGateway\DTO\BidDTO;
use App\ApiGateway\DTO\BidsDTO;
use App\ApiGateway\DTO\CommentShowDTO;
use App\ApiGateway\DTO\CompanyShowDTO;
use App\ApiGateway\DTO\ContactDTO;
use App\ApiGateway\DTO\ListDTO;
use App\ApiGateway\DTO\CompanyWithContactsDTO;
use App\ApiGateway\DTO\LoadCreateDTO;
use App\ApiGateway\DTO\LoadDTO;
use App\ApiGateway\DTO\LoadFilter;
use App\ApiGateway\DTO\LoadingDTO;
use App\ApiGateway\DTO\LocationDTO;
use App\ApiGateway\DTO\RateDTO;
use App\ApiGateway\DTO\RatingDTO;
use App\ApiGateway\DTO\RouteDTO;
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
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class LoadApi
{
    const FUEL_CONSUMPTION = 28;

    const KM_PER_HOUR = 66;

    public function __construct(
        private LoadRepository $loadRepository,
        private LoadService $loadService,
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
            $filter->volumeMax ?? null
        );

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
        $cityIds = array_unique($cityIds);

        /** @var ArrayCollection<int, CommentShowDTO> $commentCollection */
        $commentCollection = $commentUserId
            ? $this->commentApi->getByLoadIds($loadIds, $commentUserId)
            : new ArrayCollection();
        /** @var ArrayCollection<int, CompanyShowDTO> $companyCollection */
        $companyCollection = $this->companyAdapter->getByIds($companyIds);
        $userCollection = $this->userAdapter->getByCompanyIds($companyIds);
        /** @var ArrayCollection<int, CityDTO> $cityCollection */
        $cityCollection = $this->cityAdapter->getCitiesByIds($cityIds);

        /** @var LoadDTO[] $loads */
        $loads = [];

        /**  @var Load $load */
        foreach($result->list as $load) {
            /**  @var CompanyShowDTO $company */
            $company = $companyCollection->get($load->getCompanyId());
            $userContacts = $userCollection->get($load->getCompanyId());
            $fromCity = $cityCollection->get($load->getFromCityId());
            $toCity = $cityCollection->get($load->getToCityId());
            $comment = $commentCollection->get($load->getId());

            $loads[] = $this->buildLoadDTO($load, $company, $userContacts, $fromCity, $toCity, $comment);
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

    public function getLoadById(int $id): ?LoadDTO
    {
        $load = $this->loadRepository->getById($id);

        if (!$load) {
            return null;
        }

        /**  @var CompanyShowDTO $company */
        $company = $this->companyAdapter->getById($load->getCompanyId());
        /** @var User[] $companyContacts */
        $companyContacts = $this->userAdapter->getByCompanyId($load->getCompanyId());
        /** @var ArrayCollection<int, CityDTO> $cityCollection */
        $cityCollection = $this->cityAdapter->getCitiesByIds([
            $load->getFromCityId(),
            $load->getToCityId(),
        ]);

        return $this->buildLoadDTO(
            $load,
            $company,
            $companyContacts,
            $cityCollection->get($load->getFromCityId()),
            $cityCollection->get($load->getToCityId()),
        );
    }

    private function buildLoadDTO(
        Load $item,
        CompanyShowDTO $company,
        array $companyContacts,
        CityDTO $fromCity,
        CityDTO $toCity,
        CommentShowDTO $comment = null,
        string $country = 'RUS'
    ): LoadDTO
    {
        /** @var ContactDTO[] $contacts */
        $contacts = [];
        /**  @var User $userContact  */
        foreach($companyContacts as $companyContact) {
            $contacts[] = new ContactDTO(
                $companyContact->getId(),
                $companyContact->getName(),
                $companyContact->getPhone()->getPhone(),
                $companyContact->getPhone()->getMobilePhone(),
                $companyContact->getEmail(),
            );
        }

        $companyCity = null !== $company->cityId
            ? $this->cityAdapter->getCityById($company->cityId)
            : null;

        $maxBid = 0;
        if ($item->getBids()->count() > 0) {
            $maxBid = max($item->getBids()->map(fn(Bid $bid) => $bid->getBid())->toArray());
        }

        $bids =  new BidsDTO(
            $item->getBids()->count(),
            $maxBid,
            $item->getBids()->map(fn(Bid $bid) => new BidDTO($bid->getId(), $bid->getBid())),
        );

        $transitTime = $item->getDistance() / self::KM_PER_HOUR;

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
                $item->getDownloadingDateStatus(),
                $item->getDownloadingDate()->format('d M'),
                $item->getDownloadingTypeName(),
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
                $item->getUnloadingTypeName(),
            ),
            $item->getCargoTypeName(),
            $item->getBodyTypeName(),
            $item->getWeight(),
            $item->getVolume(),
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
            new CompanyWithContactsDTO(
                $company->id,
                $company->fullName,
                isset($companyCity) ? $companyCity->name : '',
                $company->type,
                new RatingDTO(
                    5
                ),
                $contacts,
            )
        );
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
            ->setDownloadingDateStatus($createDto->downloadingDateStatus)
            ->setDownloadingDate(new DateTime($createDto->downloadingDate))
            ->setFromCityId($createDto->fromCityId)
            ->setFromAddress($createDto->fromAddress)
            ->setToCityId($createDto->toCityId)
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
            ->setCompanyId($user->getCompanyId())
            ->setCreatedAt()
            ->setUpdatedAt();

        $routeCities = $this->cityAdapter->getCitiesByIds([$createDto->fromCityId, $createDto->toCityId]);

        $fromCity = $routeCities->get($createDto->fromCityId);
        $toCity = $routeCities->get($createDto->toCityId);

        $this->loadService->save($load, $fromCity, $toCity);
        return $load->getId();
    }
}
