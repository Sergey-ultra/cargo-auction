<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

final readonly class RateDTO
{
    public function __construct(
        public string $priceType,
        public int $priceWithoutTax,
        public int $priceWithTax,
        public int $priceCash,
        public float $priceWithoutTaxPerKm,
        public float $priceWithTaxPerKm,
        public float $priceCashPerKm,
    )
    {
    }
}
