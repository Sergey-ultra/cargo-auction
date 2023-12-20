<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[ORM\Table(name:"orders")]
class Order
{
    public const CARGO_TYPES = [
            'продукты питания',
            'ТНП непродовольственные',
            'оборудование и запчасти',
            'строймариалы',
            'металл',
            'пиломатериалы',
            'пустая тара и упаковка',
            'картон, бумага, макулатура',
            'химия',
            'топливо и смазки',
            'контейнер',
            'транспортные средства',
            'с/х сырье и продукция',
            'личные вещи, переезд',
            'сборный груз',
            'другой',
        ];

    public const PACKAGE_TYPES = [
        [
            'value' => 'упаковка',
            'children' => [
                "bigbag" => 'биг бэги',
                "pallet" => 'паллеты',
                "box" => 'коробки',
                "case" => 'ящики',
                "barrel" => 'бочки',
                "bag" => 'мешки/сетки',
                "pack" => 'пачки',
            ],
        ],
        [
            'value' => "без упаковки",
            'children' => [
                "in_bulk" => 'навалом/насыпью',
                "fill" => 'наливной груз',
                "other" => 'другая'
            ],
        ],
    ];

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column]
    private string $toAddress;
    #[ORM\Column(type: 'float', nullable: true)]
    private float $toLatitude;
    #[ORM\Column(type: 'float', nullable: true)]
    private float $toLongitude;
    #[ORM\Column]
    private string $fromAddress;
    #[ORM\Column(type: 'float', nullable: true)]
    private float $fromLatitude;
    #[ORM\Column(type: 'float', nullable: true)]
    private float $fromLongitude;
    #[ORM\Column]
    private string $weight;
    #[ORM\Column]
    private string $volume;
    #[ORM\Column(options: ['default' => false])]
    private bool $isAgreedPrice;
    #[ORM\Column(nullable: true)]
    private ?int $price;
    #[ORM\Column]
    private int $cargoType;
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

    public function getToAddress(): string
    {
        return $this->toAddress;
    }

    public function setToAddress(string $toAddress): self
    {
        $this->toAddress = $toAddress;
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
    public function isAgreedPrice(): bool
    {
        return $this->isAgreedPrice;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(?int $price): self
    {
        $this->price = $price;
        return $this;
    }

    public function setIsAgreedPrice(bool $isAgreedPrice): self
    {
        $this->isAgreedPrice = $isAgreedPrice;
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
        $this->updatedAt = new \DateTime();
        return $this;
    }

    public function getCargoTypeName(): string
    {
        return self::CARGO_TYPES[$this->cargoType];
    }
}
