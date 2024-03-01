<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

use DateTimeInterface;
use Doctrine\Common\Collections\Collection;

class LoadDTO
{
    public function __construct(
        public int $id,
        public int $userId,
        public Collection $bids,
        public string $fromAddress,
        public string $toAddress,
        public string $cargoType,
        public string $bodyType,
        public float $weight,
        public float $volume,
        public int $priceWithoutTax,
        public int $priceWithTax,
        public int $priceCash,
        public string $downloadingType,
        public string $unloadingType,
        public DateTimeInterface $createdAt,
        public ?DateTimeInterface $updatedAt,
        public CompanyDTO $company,

    ){
    }
}
