<?php

declare(strict_types=1);

namespace App\Modules\Load\Domain\Entity;

use App\Modules\Load\Infrastructure\Repository\LoadRepository;
use App\Modules\User\Domain\Entity\User;
use App\ValueObject\Point;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: LoadRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[ORM\Table(name:"loads")]
class Load
{
    public const DOWNLOADING_DATE_FROM_DATE_STATUS = 'from-date';
    public const DOWNLOADING_DATE_PERMANENT_STATUS = 'permanently';
    public const DOWNLOADING_DATE_REQUEST_STATUS = 'request';

    public const DOWNLOADING_DATE_TITLES = [
        self::DOWNLOADING_DATE_FROM_DATE_STATUS => 'Готов к загрузке',
        self::DOWNLOADING_DATE_PERMANENT_STATUS => 'Постоянно',
        self::DOWNLOADING_DATE_REQUEST_STATUS => 'Груза  нет, запрос ставки',
    ];

    public const PRICE_TYPE = [
        'negotiable' => 'Возможен торг',
        'fix' => 'Без торга',
        'request' => 'Запрос',
        'auction' =>  'Торги',
    ];
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['load'])]
    private ?int $id = null;
    #[ORM\Column]
    private int $companyId;
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['load'])]
    private ?User $user;
    #[ORM\Column(length: 100)]
    #[Groups(['load'])]
    private string $loadingType;
    #[ORM\Column(type: 'datetime')]
    #[Groups(['load'])]
    private DateTimeInterface $loadingFirstDate;
    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $loadingLastDate;
    #[ORM\Column(type: 'time')]
    private ?DateTimeInterface $loadingStartTime;
    #[ORM\Column(type: 'time')]
    private ?DateTimeInterface $loadingEndTime;
    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $unloadingDate;
    #[ORM\Column]
    private ?string $periodicity;
    #[ORM\Column(type: 'time')]
    private ?DateTimeInterface $unloadingStartTime;
    #[ORM\Column(type: 'time')]
    private ?DateTimeInterface $unloadingEndTime;
    #[ORM\Column]
    private int $fromCityId;
    #[ORM\Column]
    #[Groups(['load'])]
    private string $fromAddress;
    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups(['load'])]
    private float $fromLatitude;
    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups(['load'])]
    private float $fromLongitude;
    #[ORM\Column(name: 'from_point', type: 'point',  nullable: true)]
    #[Groups(['load'])]
    private ?Point $fromPoint;
    #[ORM\Column]
    private int $toCityId;
    #[ORM\Column]
    #[Groups(['load'])]
    private string $toAddress;
    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups(['load'])]
    private float $toLatitude;
    #[ORM\Column(type: 'float', nullable: true)]
    #[Groups(['load'])]
    private float $toLongitude;
    #[ORM\Column(name: 'to_point', type: 'point',  nullable: true)]
    #[Groups(['load'])]
    private ?Point $toPoint;

    #[Groups(['load'])]
    private ?int $distance;
    #[ORM\Column]
    #[Groups(['load'])]
    private float $weight;
    #[ORM\Column]
    #[Groups(['load'])]
    private float $volume;
    #[ORM\Column(length: 50, options: ['default' => 'ftl'])]
    private string $loadType = 'ftl';
    #[ORM\Column]
    private ?int $temperatureFrom;
    #[ORM\Column]
    private ?int $temperatureTo;
    #[ORM\Column(length: 50)]
    #[Groups(['load'])]
    private string $priceType;
    #[ORM\Column(nullable: true)]
    #[Groups(['load'])]
    private ?int $priceWithoutTax;
    #[ORM\Column(nullable: true)]
    #[Groups(['load'])]
    private ?int $priceWithTax;
    #[ORM\Column(nullable: true)]
    #[Groups(['load'])]
    private ?int $priceCash;
    #[ORM\Column]
    private bool $paymentOnCard = false;
    #[ORM\Column]
    private bool $hideCounterOffers = false;
    #[ORM\Column]
    private bool $acceptBidsWithVat = false;
    #[ORM\Column]
    private bool $acceptBidsWithoutVat = false;
    #[ORM\Column]
    #[Groups(['load'])]
    private int $cargoType;
    #[ORM\Column]
    #[Groups(['load'])]
    private array $bodyTypes = [];
    #[ORM\Column]
    #[Groups(['load'])]
    private array $truckLoadingTypes;
    #[ORM\Column]
    #[Groups(['load'])]
    private array $truckUnloadingTypes;
    #[ORM\Column]
    private array $contactIds;
    #[ORM\Column]
    private array $files = [];
    #[ORM\Column]
    private ?string $note;
    #[ORM\Column(name: 'created_at', type: 'datetime', options: ['default' => "CURRENT_TIMESTAMP"])]
    #[Groups(['load'])]
    private DateTimeInterface $createdAt;
    #[ORM\Column(name: 'updated_at', type: "datetime", nullable: true)]
    #[Groups(['load'])]
    private ?DateTimeInterface $updatedAt;

    #[ORM\OneToMany(mappedBy: 'load', targetEntity: Bid::class)]
    private PersistentCollection $bids;

//    public function __toString(): string
//    {
//        return json_encode($this);
//    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getLoadingType(): string
    {
        return $this->loadingType;
    }

    public function setLoadingType(string $loadingType): self
    {
        $this->loadingType = $loadingType;
        return $this;
    }

    public function getLoadingFirstDate(): DateTimeInterface
    {
        return $this->loadingFirstDate;
    }

    public function setLoadingFirstDate(DateTimeInterface $loadingFirstDate): self
    {
        $this->loadingFirstDate = $loadingFirstDate;
        return $this;
    }

    public function getLoadingLastDate(): ?DateTimeInterface
    {
        return $this->loadingLastDate;
    }

    public function setLoadingLastDate(?DateTimeInterface $loadingLastDate): self
    {
        $this->loadingLastDate = $loadingLastDate;
        return $this;
    }

    public function getLoadingPeriodicity(): ?string
    {
        return $this->periodicity;
    }

    public function setLoadingPeriodicity(?string $periodicity): self
    {
        $this->periodicity = $periodicity;

        return $this;
    }

    public function getLoadingStartTime(): ?DateTimeInterface
    {
        return $this->loadingStartTime;
    }

    public function setLoadingStartTime(?DateTimeInterface $time): self
    {
        $this->loadingStartTime = $time;

        return $this;
    }

    public function getLoadingEndTime(): ?DateTimeInterface
    {
        return $this->loadingEndTime;
    }

    public function setLoadingEndTime(?DateTimeInterface $time): self
    {
        $this->loadingEndTime = $time;

        return $this;
    }

    public function getUnloadingDate(): ?DateTimeInterface
    {
        return $this->unloadingDate;
    }

    public function setUnloadingDate(?DateTimeInterface $unloadingDate): self
    {
        $this->unloadingDate = $unloadingDate;

        return $this;
    }

    public function getUnloadingStartTime(): ?DateTimeInterface
    {
        return $this->unloadingStartTime;
    }

    public function setUnloadingStartTime(?DateTimeInterface $time): self
    {
        $this->unloadingStartTime = $time;

        return $this;
    }

    public function getUnloadingEndTime(): ?DateTimeInterface
    {
        return $this->unloadingEndTime;
    }

    public function setUnloadingEndTime(?DateTimeInterface $time): self
    {
        $this->unloadingEndTime = $time;

        return $this;
    }

    public function getFromCityId(): int
    {
        return $this->fromCityId;
    }

    public function setFromCityId(int $fromCityId): self
    {
        $this->fromCityId = $fromCityId;
        return $this;
    }

    public function getFromAddress(): string
    {
        return $this->fromAddress;
    }

    public function setFromAddress(string $fromAddress): self
    {
        $this->fromAddress = $fromAddress;
        return $this;
    }

    public function getFromLongitude(): float
    {
        return $this->fromLongitude;
    }

    public function setFromLongitude(float $fromLongitude): self
    {
        $this->fromLongitude = $fromLongitude;
        return $this;
    }

    public function getFromLatitude(): float
    {
        return $this->fromLatitude;
    }

    public function setFromLatitude(float $fromLatitude): self
    {
        $this->fromLatitude = $fromLatitude;
        return $this;
    }

    public function getFromPoint(): Point
    {
        return $this->fromPoint;
    }

    public function setFromPoint(Point $fromPoint): self
    {
        $this->fromPoint = $fromPoint;
        return $this;
    }

    public function getToCityId(): int
    {
        return $this->toCityId;
    }

    public function setToCityId(int $toCityId): self
    {
        $this->toCityId = $toCityId;
        return $this;
    }

    public function getToAddress(): string
    {
        return $this->toAddress;
    }

    public function setToAddress(string $toAddress): self
    {
        $this->toAddress = $toAddress;
        return $this;
    }

    public function getToLongitude(): float
    {
        return $this->toLongitude;
    }

    public function setToLongitude(float $toLongitude): self
    {
        $this->toLongitude = $toLongitude;
        return $this;
    }

    public function getToLatitude(): float
    {
        return $this->toLatitude;
    }

    public function setToLatitude(float $toLatitude): self
    {
        $this->toLatitude = $toLatitude;
        return $this;
    }

    public function getToPoint(): Point
    {
        return $this->toPoint;
    }

    public function setToPoint(Point $toPoint): self
    {
        $this->toPoint = $toPoint;
        return $this;
    }

    public function getDistance(): ?int
    {
        return $this->distance;
    }

    public function setDistance(?int $distance): self
    {
        $this->distance = $distance;
        return $this;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function setWeight(float $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    public function getVolume(): float
    {
        return $this->volume;
    }

    public function setVolume(float $volume): self
    {
        $this->volume = $volume;
        return $this;
    }

    public function getLoadType(): string
    {
        return $this->loadType;
    }

    public function setLoadType(string $loadType): self
    {
        $this->loadType = $loadType;

        return $this;
    }

    public function getLoadTemperatureFrom(): ?int
    {
        return $this->temperatureFrom;
    }

    public function setLoadTemperatureFrom(?int $temperature): self
    {
        $this->temperatureFrom = $temperature;
        return $this;
    }

    public function getLoadTemperatureTo(): ?int
    {
        return $this->temperatureTo;
    }

    public function setLoadTemperatureTo(?int $temperatureTo): self
    {
        $this->temperatureTo = $temperatureTo;

        return $this;
    }

    public function getPriceType(): string
    {
        return $this->priceType;
    }

    public function setPriceType(string $priceType): self
    {
        $this->priceType = $priceType;
        return $this;
    }

    public function getPriceWithoutTax(): ?int
    {
        return $this->priceWithoutTax;
    }

    public function setPriceWithoutTax(?int $priceWithoutTax): self
    {
        $this->priceWithoutTax = $priceWithoutTax;
        return $this;
    }

    public function getPriceWithTax(): ?int
    {
        return $this->priceWithTax;
    }

    public function setPriceWithTax(?int $priceWithTax): self
    {
        $this->priceWithTax = $priceWithTax;
        return $this;
    }

    public function getPriceCash(): ?int
    {
        return $this->priceCash;
    }

    public function setPriceCash(?int $priceCash): self
    {
        $this->priceCash = $priceCash;
        return $this;
    }

    public function getPaymentOnCard(): bool
    {
        return $this->paymentOnCard;
    }

    public function setPaymentOnCard(bool $onCard): self
    {
        $this->paymentOnCard = $onCard;
        return $this;
    }

    public function getHideCounterOffers(): bool
    {
        return $this->hideCounterOffers;
    }

    public function setHideCounterOffers(bool $hideCounterOffers): self
    {
        $this->hideCounterOffers = $hideCounterOffers;
        return $this;
    }

    public function getAcceptBidsWithVat(): bool
    {
        return $this->acceptBidsWithVat;
    }

    public function setAcceptBidsWithVat(bool $acceptBidsWithVat): self
    {
       $this->acceptBidsWithVat = $acceptBidsWithVat;

       return $this;
    }

    public function getAcceptBidsWithoutVat(): bool
    {
        return $this->acceptBidsWithoutVat;
    }

    public function setAcceptBidsWithoutVat(bool $acceptBidsWithoutVat): self
    {
        $this->acceptBidsWithoutVat = $acceptBidsWithoutVat;

        return $this;
    }

    public function getCargoType(): int
    {
        return $this->cargoType;
    }

    public function setCargoType(int $cargoType): self
    {
        $this->cargoType = $cargoType;
        return $this;
    }

    public function getBodyTypes(): array
    {
        return $this->bodyTypes;
    }

    public function setBodyTypes(array $bodyTypes): self
    {
        $this->bodyTypes = $bodyTypes;
        return $this;
    }

    public function getTruckLoadingType(): array
    {
        return $this->truckLoadingTypes;
    }

    public function getTruckLoadingTypeName(): string
    {
        $typesMapById = LoadingType::getTypesMapById();
        $names = array_map(fn(int $id) => $typesMapById[$id]['name'], $this->truckLoadingTypes);

        return implode(', ', $names);
    }

    public function getTruckLoadingTypeShortNames(): string
    {
        $typesMapById = LoadingType::getTypesMapById();
        $names = array_map(fn(int $id) => $typesMapById[$id]['short'], $this->truckLoadingTypes);

        return implode(', ', $names);
    }

    public function setTruckLoadingTypes(array $truckLoadingTypes): self
    {
        $this->truckLoadingTypes = $truckLoadingTypes;

        return $this;
    }

    public function getTruckUnloadingTypes(): array
    {
        return $this->truckUnloadingTypes;
    }

    public function getTruckUnloadingTypeName(): string
    {
        $typesMapById = LoadingType::getTypesMapById();
        $names = array_map(fn(int $id) => $typesMapById[$id]['name'], $this->truckUnloadingTypes);

        return implode(', ', $names);
    }

    public function getTruckUnloadingTypeShortNames(): string
    {
        $typesMapById = LoadingType::getTypesMapById();
        $names = array_map(fn(int $id) => $typesMapById[$id]['short'], $this->truckUnloadingTypes);

        return implode(', ', $names);
    }

    public function setTruckUnloadingTypes(array $truckUnloadingTypes): self
    {
        $this->truckUnloadingTypes = $truckUnloadingTypes;
        return $this;
    }
    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    public function setCompanyId(int $companyId): self
    {
        $this->companyId = $companyId;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getContactIds(): array
    {
        return $this->contactIds;
    }

    public function setContactIds(array $contactIds): self
    {
        $this->contactIds = $contactIds;

        return $this;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function setFiles(array $files): self
    {
        $this->files = $files;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;
        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): self
    {
        $this->createdAt = new \DateTime();
        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    #[ORM\PrePersist]
    public function setUpdatedAt(): self
    {
        $this->updatedAt = null;
        return $this;
    }

    public function getCargoTypeName(): string
    {
        return CargoType::CARGO_TYPES[$this->cargoType];
    }

    public function getBodyTypeName(): string
    {
        $typesMapById = BodyType::getTypesMapById();
        $names = array_map(fn(int $id) => $typesMapById[$id]['car_type'], $this->bodyTypes);

        return implode(', ', $names);
    }

    public function getBodyTypeShortNames(): string
    {
        $typesMapById = BodyType::getTypesMapById();
        $names = array_map(fn(int $id) => $typesMapById[$id]['short'], $this->bodyTypes);

        return implode(', ', $names);
    }

    /**
     * @return PersistentCollection<Bid>
     */
    public function getBids(): PersistentCollection
    {
        return $this->bids;
    }

    public function addBid(Bid $bid): self
    {
        if (!$this->bids->contains($bid)) {
            $this->bids[] = $bid;
            $bid->setLoad($this);
        }
        return $this;
    }

    public function removeBid(Bid $bid): self
    {
        if ($this->bids->contains($bid)) {
            $this->bids->removeElement($bid);

//            if ($bid->getLoad() === $this) {
//                $bid->setLoad(null);
//            }
        }
        return $this;
    }
}
