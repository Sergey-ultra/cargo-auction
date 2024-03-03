<?php

declare(strict_types=1);

namespace  App\Modules\Chat\Domain\Entity;

use App\Modules\Chat\Infrastructure\Repository\ChatRepository;
use App\Modules\User\Domain\Entity\User;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChatRepository::class)]
#[ORM\Table(name:"chats")]
class Chat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column]
    private string $name;
    #[ORM\Column]
    private string $description;
    #[ORM\Column]
    private string $draft;
    #[ORM\Column(length: 100)]
    private string $type = 'dialog';
    #[ORM\Column]
    private bool $unread = false;
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'chats')]
    #[ORM\JoinColumn(nullable: false)]
    private User $owner;
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'chats')]
    #[ORM\JoinColumn(nullable: false)]
    private User $partner;
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

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDraft(): string
    {
        return $this->draft;
    }

    public function setDraft(string $draft): self
    {
        $this->draft = $draft;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isUnread(): bool
    {
        return $this->unread;
    }

    public function setUnread(bool $unread): self
    {
        $this->unread = $unread;
        return $this;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): self
    {
        $this->owner = $owner;
        return $this;
    }

    public function getPartner(): User
    {
        return $this->partner;
    }

    public function setPartner(User $partner): self
    {
        $this->partner = $partner;
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
