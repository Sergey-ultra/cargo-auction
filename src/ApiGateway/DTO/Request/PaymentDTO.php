<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final readonly class PaymentDTO
{
    public function __construct(
        public string $type,
        #[NotBlank(message: 'Цена С НДС, безнал не может быть пустым')]
        #[Type('string')]
        public string $priceWithoutTax,
        #[NotBlank(message: 'Цена Без НДС, безнал не может быть пустым')]
        #[Type('string')]
        public string $priceWithTax,
        #[NotBlank(message: 'Цена(наличные) не может быть пустым')]
        #[Type('string')]
        public string $priceCash,
        #[Type('bool')]
        public bool $onCard,
        #[Type('bool')]
        public bool $hideCounterOffers,
        #[Type('bool')]
        public bool $acceptBidsWithVat,
        #[Type('bool')]
        public bool $acceptBidsWithoutVat,
        public int $vatPercents,
    )
    {
    }
}
