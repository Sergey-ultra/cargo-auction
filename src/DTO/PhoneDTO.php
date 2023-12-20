<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class PhoneDTO
{
    public function __construct(
        #[NotBlank(message: 'I dont like this field empty')]
        #[Type('string')]
//        #[Tel(options: ["defaultRegion" => "FR",  "onlyMobile" => true, "onlyEURoaming" => true])]
        public readonly string $phone,
    ){}
}
