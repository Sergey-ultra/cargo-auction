<?php

declare(strict_types=1);

namespace App\Request;

class OrderRequest
{
    public string $from;
    public string $to;
    public string $weight;
    public string $volume;
}
