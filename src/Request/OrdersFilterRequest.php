<?php

declare(strict_types=1);

namespace App\Request;

class OrdersFilterRequest
{
    public string $from;
    public string $to;
    public string $weight;
    public string $volume;

    public function toArray(): array
    {
        return (array)$this;
    }
}
