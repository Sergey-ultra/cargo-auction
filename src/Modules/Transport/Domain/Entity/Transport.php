<?php

declare(strict_types=1);

namespace App\Modules\Transport\Domain\Entity;

use App\Modules\Load\Domain\Entity\BodyType;
use App\Modules\Transport\Infrastructure\Repository\TransportRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TransportRepository::class)]
#[ORM\Table(name:"transports")]
class Transport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(nullable: true)]
    private ?int $fromCityId;
    #[ORM\Column(length: 200, nullable: true)]
    private ?string $fromName;
    #[ORM\Column(nullable: true)]
    private ?int $toCityId;
    #[ORM\Column(length: 200, nullable: true)]
    private ?string $toName;
    #[ORM\Column]
    private int $bodyType;
    #[ORM\Column]
    private float $weight;
    #[ORM\Column]
    private float $volume;
    #[ORM\Column(nullable: true)]
    private ?int $priceWithoutTax;
    #[ORM\Column(nullable: true)]
    private ?int $priceWithTax;
    #[ORM\Column(nullable: true)]
    private ?int $priceCash;
    #[ORM\Column(nullable: true)]
    private ?int $downloadingType;
    #[ORM\Column]
    private int $companyId;
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
    public function getCompanyId(): int
    {
        return $this->companyId;
    }

    public function setCompanyId(int $companyId): self
    {
        $this->companyId = $companyId;
        return $this;
    }

    public function getFromCityId(): ?int
    {
        return $this->fromCityId;
    }

    public function setFromCityId(?int $fromCityId): self
    {
        $this->fromCityId = $fromCityId;
        return $this;
    }

    public function getToCityId(): ?int
    {
        return $this->toCityId;
    }

    public function setToCityId(?int $toCityId): self
    {
        $this->toCityId = $toCityId;
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

    public function getBodyTypeName(): string
    {
        $typesMapById = BodyType::getTypesMapById();
        return $typesMapById[$this->bodyType]['car_type'];
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

    public function getDownloadingType(): ?int
    {
        return $this->downloadingType;
    }

    public function setDownloadingType(?int $downloadingType): self
    {
        $this->downloadingType = $downloadingType;
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

    public function getFromName(): ?string
    {
        return $this->fromName;
    }

    public function setFromName(?string $fromName): self
    {
        $this->fromName = $fromName;
        return $this;
    }

    public function getToName(): ?string
    {
        return $this->toName;
    }

    public function setToName(?string $toName): self
    {
        $this->toName = $toName;
        return $this;
    }
}
