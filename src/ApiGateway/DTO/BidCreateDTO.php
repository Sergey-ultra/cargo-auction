<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

readonly class BidCreateDTO
{
    public function __construct(
        #[NotBlank(message: 'field empty')]
        #[Positive]
        public int $bid
    ){}
}
