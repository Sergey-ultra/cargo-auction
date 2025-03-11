<?php

declare(strict_types=1);

namespace App\Modules\Filter\Domain\Entity;

use App\Modules\Filter\Domain\Enum\FilterType;
use App\Modules\Filter\Infrastructure\Repository\FilterRepository;
use App\Modules\User\Domain\Entity\User;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FilterRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[ORM\Table(name:"filters")]
class Filter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column(length: 255)]
    private string $name;
    #[ORM\Column(name: 'type', type: 'string', nullable: false, enumType: FilterType::class)]
    private FilterType $type;
    #[ORM\Column(type: 'json')]
    private array $filter;
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'filters')]
    #[ORM\JoinColumn(nullable: false)]
    private User $user;
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

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getType(): FilterType
    {
        return $this->type;
    }

    public function setType(FilterType $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getFilter(): array
    {
        return $this->filter;
    }

    public function setFilter(array $filter): self
    {
        $this->filter = $filter;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
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
}
