<?php

declare(strict_types=1);

namespace App\Modules\City\Domain\Entity;

use App\Modules\City\Infrastructure\Repository\CityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
#[ORM\Table(name:"cities")]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column]
    private string $name;
    #[ORM\Column(length: 500)]
    private ?string $otherName;
    #[ORM\Column]
    private string $regionName;
    #[ORM\Column]
    private string $district;
    #[ORM\Column(type: 'float', nullable: true)]
    private float $lon;
    #[ORM\Column(type: 'float', nullable: true)]
    private float $lat;
    #[ORM\Column(type: 'integer')]
    private int $population;
    #[ORM\Column(type: 'boolean')]
    private bool $approx;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getOtherName(): ?string
    {
        return $this->otherName;
    }

    public function setOtherName(?string $otherName): self
    {
        $this->otherName = $otherName;
        return $this;
    }

    public function getRegionName(): string
    {
        return $this->regionName;
    }

    public function setRegionName(string $regionName): self
    {
        $this->regionName = $regionName;
        return $this;
    }

    public function getDistrict(): string
    {
        return $this->district;
    }

    public function setDistrict(string $district): self
    {
        $this->district = $district;
        return $this;
    }

    public function getLon(): float
    {
        return $this->lon;
    }

    public function setLon(float $lon): self
    {
        $this->lon = $lon;
        return $this;
    }

    public function getLat(): float
    {
        return $this->lat;
    }

    public function setLat(float $lat): self
    {
        $this->lat = $lat;
        return $this;
    }

    public function getPopulation(): int
    {
        return $this->population;
    }

    public function setPopulation(int $population): self
    {
        $this->population = $population;
        return $this;
    }

    public function isApprox(): bool
    {
        return $this->approx;
    }

    public function setApprox(bool $approx): self
    {
        $this->approx = $approx;
        return $this;
    }
}
