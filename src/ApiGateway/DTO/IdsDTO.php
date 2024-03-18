<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

final readonly class IdsDTO
{
    public function __construct(
        /** @var int[] */
        public array $ids,
    )
    {
    }
}
