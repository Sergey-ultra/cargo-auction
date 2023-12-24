<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class OrdersFilter
{
    public ?string $fromAddress;
    public ?int $fromAddressId;
    public ?string $fromRadius;
    public ?string $toAddress;
    public ?int $toAddressId;
    public ?string $toRadius;
    public ?string $weight;
    public ?string $volume;

    public function toArray(): array
    {
        return (array)$this;
    }
}
