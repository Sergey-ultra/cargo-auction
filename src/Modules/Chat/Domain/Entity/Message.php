<?php

declare(strict_types=1);

namespace App\Modules\Chat\Domain\Entity;

use App\Modules\Chat\Infrastructure\Repository\MessageRepository;
use App\Modules\User\Domain\Entity\User;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\HasLifecycleCallbacks()]
#[ORM\Table(name:"messages")]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column]
    private string $message;
    #[ORM\ManyToOne(targetEntity: Chat::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private Chat $chat;
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private User $fromUser;
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'messages')]
    #[ORM\JoinColumn(nullable: false)]
    private User $toUser;
    #[ORM\Column(name: 'created_at', type: 'datetime', options: ['default' => "CURRENT_TIMESTAMP"])]
    #[Groups(['load'])]
    private DateTimeInterface $createdAt;
    #[ORM\Column(name: 'updated_at', type: "datetime", nullable: true)]
    #[Groups(['load'])]
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

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getChat(): Chat
    {
        return $this->chat;
    }

    public function setChat(Chat $chat): self
    {
        $this->chat = $chat;
        return $this;
    }

    public function getFromUser(): User
    {
        return $this->fromUser;
    }

    public function setFromUser(User $fromUser): self
    {
        $this->fromUser = $fromUser;
        return $this;
    }

    public function getToUser(): User
    {
        return $this->toUser;
    }

    public function setToUser(User $toUser): self
    {
        $this->toUser = $toUser;
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
