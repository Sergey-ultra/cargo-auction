<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

readonly class PhoneDTO
{
    public function __construct(
        #[NotBlank(message: 'I dont like this field empty')]
        #[Type('string')]
//        #[Tel(options: ["defaultRegion" => "FR",  "onlyMobile" => true, "onlyEURoaming" => true])]
        public ?string $phone,
        #[NotBlank(message: 'I dont like this field empty')]
        #[Type('string')]
//        #[Tel(options: ["defaultRegion" => "FR",  "onlyMobile" => true, "onlyEURoaming" => true])]
        public ?string $mobilePhone,
    ){}
}
