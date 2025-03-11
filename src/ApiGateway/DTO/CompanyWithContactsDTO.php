<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

final readonly class CompanyWithContactsDTO
{
    public function __construct(
        public int $id,
        public string $fullName,
        public string $cityName,
        public string $type,
        public RatingDTO $rating,
        /** @var ContactDTO[] */
        public array $contacts,
    )
    {
    }
}
