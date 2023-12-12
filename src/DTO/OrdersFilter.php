<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class OrdersFilter
{
    public ?string $from;
    public ?string $to;
    public ?string $weight;
    public ?string $volume;

    public function toArray(): array
    {
        return (array)$this;
    }
}
