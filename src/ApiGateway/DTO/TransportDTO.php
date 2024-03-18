<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

use DateTimeInterface;

final readonly class TransportDTO
{
    public function __construct(
        public int $id,
        public string $fromName,
        public string $toName,
        public string $bodyType,
        public float $weight,
        public float $volume,
        public int $priceWithoutTax,
        public int $priceWithTax,
        public int $priceCash,
        public ?CommentShowDTO $comment,
        public DateTimeInterface $createdAt,
        public ?DateTimeInterface $updatedAt,
        public CompanyWithContactsDTO $company,
    ){
    }
}
