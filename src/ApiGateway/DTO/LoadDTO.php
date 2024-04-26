<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

use App\Modules\Load\Domain\Entity\Bid;
use Doctrine\Common\Collections\Collection;

class LoadDTO
{
    public function __construct(
        public int $id,
        public RouteDTO $route,
        public BidsDTO $bids,
        public LoadingDTO $loading,
        public UnloadingDTO $unloading,
        public string $cargoType,
        public string $bodyType,
        public float $weight,
        public float $volume,
        public RateDTO $rate,
        public ?string $note,
        public ?CommentShowDTO $comment,
        public int $userId,
        public string $createdAt,
        public ?string $updatedAt,
        public ?CompanyWithContactsDTO $company,
    ){
    }
}
