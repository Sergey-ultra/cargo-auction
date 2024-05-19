<?php

declare(strict_types=1);

namespace App\Modules\Company\Domain\Entity;

use App\Modules\Company\Infrastructure\Repository\CompanyRepository;
use App\Modules\User\Domain\Entity\User;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[ORM\Table(name:"companies")]
class Company
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column]
    private ?int $cityId;
    #[ORM\Column(length: 255)]
    private string $name;
    #[ORM\Column]
    private int $ownershipId;
    #[ORM\Column]
    private int $typeId;

    #[ORM\Column(nullable: true)]
    private ?string $description;
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'companies')]
    #[ORM\JoinColumn(nullable: true)]
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

    public function getCityId(): ?int
    {
        return $this->cityId;
    }

    public function setCityId(?int $cityId): self
    {
        $this->cityId = $cityId;
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

    public function getOwnershipId(): int
    {
        return $this->ownershipId;
    }

    public function getOwnershipName(): string
    {
        return Ownership::OWNERSHIP_NAMES[$this->ownershipId];
    }

    public function setOwnershipId(int $ownershipId): self
    {
        $this->ownershipId = $ownershipId;
        return $this;
    }

    public function getTypeId(): int
    {
        return $this->typeId;
    }

    public function getTypeName(): string
    {
        return CompanyType::COMPANY_TYPES[$this->typeId] ?? '';
    }

    public function setTypeId(int $typeId): self
    {
        $this->typeId = $typeId;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getUser(): ?User
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
}
