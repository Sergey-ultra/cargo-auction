<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\OrderRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name:"orders")]
class Order
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column]
    private string $to;
    #[ORM\Column]
    private string $from;
    #[ORM\Column]
    private string $weight;
    #[ORM\Column]
    private string $volume;
    #[ORM\Column]
    private int $cargoType;



    #[ORM\Column(type: "datetime")]
    private DateTimeInterface $created_at;
    #[ORM\Column(type: "datetime")]
    private ?DateTimeInterface $updated_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Order
    {
        $this->id = $id;
        return $this;
    }

    public function getTo(): int
    {
        return $this->to;
    }

    public function setTo(int $to): Order
    {
        $this->to = $to;
        return $this;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeInterface $created_at): Order
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?DateTimeInterface $updated_at): Order
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): Order
    {
        $this->weight = $weight;
        return $this;
    }

    public function getVolume(): int
    {
        return $this->volume;
    }

    public function setVolume(int $volume): Order
    {
        $this->volume = $volume;
        return $this;
    }
}
