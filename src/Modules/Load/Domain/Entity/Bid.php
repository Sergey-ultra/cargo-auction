<?php

declare(strict_types=1);

namespace App\Modules\Load\Domain\Entity;

use App\Modules\Load\Infrastructure\Repository\BidRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BidRepository::class)]
#[ORM\Table(name:"bids")]
class Bid
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[ORM\Column]
    private int $bid;
    #[ORM\ManyToOne(targetEntity: Load::class, inversedBy: 'bids')]
    #[ORM\JoinColumn(nullable: false)]
    private Load $load;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getBid(): int
    {
        return $this->bid;
    }

    public function setBid(int $bid): self
    {
        $this->bid = $bid;
        return $this;
    }

    public function getLoad(): Load
    {
        return $this->load;
    }

    public function setLoad(Load $load): self
    {
        $this->load = $load;
        return $this;
    }
}
