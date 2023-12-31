<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\LoadRepository;
use App\ValueObject\Point;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;

#[ORM\Entity(repositoryClass: LoadRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[ORM\Table(name:"orders")]
class Load
{
    public const DOWNLOADING_DATE_STATUSES = [
        'ready',
        'permanently',
        'request',
    ];

    public const DOWNLOADING_DATE_TITLES = [
        'ready' => 'Готов к загрузке',
        'permanently' => 'Постоянно',
        'request' => 'Груза  нет, запрос ставки',
    ];

    public const PRICE_TYPE = [
        'negotiable',
        'fix',
        'request',
        'auction',
    ];
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column]
    private string $downloadingDateStatus;
    #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $downloadingDate;
    #[ORM\Column]
    private string $fromAddress;
    #[ORM\Column(type: 'float', nullable: true)]
    private float $fromLatitude;
    #[ORM\Column(type: 'float', nullable: true)]
    private float $fromLongitude;
    #[Column(name: 'from_point', type: 'point',  nullable: true)]
    private ?Point $fromPoint;
    #[ORM\Column]
    private string $toAddress;
    #[ORM\Column(type: 'float', nullable: true)]
    private float $toLatitude;
    #[ORM\Column(type: 'float', nullable: true)]
    private float $toLongitude;
    #[Column(name: 'to_point', type: 'point',  nullable: true)]
    private ?Point $toPoint;
    private ?int $distance;
    #[ORM\Column]
    private string $weight;
    #[ORM\Column]
    private string $volume;
    #[ORM\Column]
    private string $priceType;
    #[ORM\Column(nullable: true)]
    private ?int $priceWithoutTax;
    #[ORM\Column(nullable: true)]
    private ?int $priceWithTax;
    #[ORM\Column(nullable: true)]
    private ?int $priceCash;
    #[ORM\Column]
    private int $cargoType;
    #[ORM\Column]
    private int $bodyType;
    #[ORM\Column]
    private int $downloadingType;
    #[ORM\Column]
    private int $unloadingType;
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user;
    #[ORM\Column(name: 'created_at', type: 'datetime', options: ['default' => "CURRENT_TIMESTAMP"])]
    private DateTimeInterface $createdAt;
    #[ORM\Column(name: 'updated_at', type: "datetime", nullable: true)]
    private ?DateTimeInterface $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getDownloadingDateStatus(): string
    {
        return $this->downloadingDateStatus;
    }

    public function setDownloadingDateStatus(string $downloadingDateStatus): self
    {
        $this->downloadingDateStatus = $downloadingDateStatus;
        return $this;
    }

    public function getDownloadingDate(): ?DateTimeInterface
    {
        return $this->downloadingDate;
    }

    public function setDownloadingDate(?DateTimeInterface $downloadingDate): self
    {
        $this->downloadingDate = $downloadingDate;
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

    public function getWeight(): string
    {
        return $this->weight;
    }

    public function setWeight(string $weight): self
    {
        $this->weight = $weight;
        return $this;
    }

    public function getVolume(): string
    {
        return $this->volume;
    }

    public function setVolume(string $volume): self
    {
        $this->volume = $volume;
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

    public function getCargoType(): int
    {
        return $this->cargoType;
    }

    public function setCargoType(int $cargoType): self
    {
        $this->cargoType = $cargoType;
        return $this;
    }

    public function getBodyType(): int
    {
        return $this->bodyType;
    }

    public function setBodyType(int $bodyType): self
    {
        $this->bodyType = $bodyType;
        return $this;
    }

    public function getDownloadingType(): int
    {
        return $this->downloadingType;
    }

    public function setDownloadingType(int $downloadingType): self
    {
        $this->downloadingType = $downloadingType;
        return $this;
    }

    public function getUnloadingType(): int
    {
        return $this->unloadingType;
    }

    public function setUnloadingType(int $unloadingType): self
    {
        $this->unloadingType = $unloadingType;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
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
        return BodyType::BODY_TYPES[$this->bodyType];
    }
}
