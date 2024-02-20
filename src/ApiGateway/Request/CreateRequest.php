<?php

declare(strict_types=1);

namespace App\ApiGateway\Request;

use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class CreateRequest extends AbstractRequest
{
    #[NotBlank(message: 'Статус загрузки не может быть пустым')]
    #[Type('string')]
    readonly public string $downloadingDateStatus;
    #[NotBlank(message: 'Дата загрузки не может быть пустым')]
    #[DateTime('Y-m-d H:i:s')]
    readonly public string $downloadingDate;
    #[NotBlank(message: 'От (адрес) не может быть пустым')]
    #[Type('string')]
    readonly public string $toAddress;
    #[NotBlank(message: 'Откуда (адрес) не может быть пустым')]
    #[Type('string')]
    readonly public string $fromAddress;
    #[NotBlank(message: 'Вес не может быть пустым')]
    #[Type('string')]
    readonly public string $weight;
    #[NotBlank(message: 'Объем не может быть пустым')]
    #[Type('string')]
    readonly public string $volume;
    #[NotBlank(message: 'Тип груза не может быть пустым')]
    #[Type('string')]
    readonly public string $cargoType;
    #[NotBlank(message: 'Кузов не может быть пустым')]
    #[Type('string')]
    readonly public string $bodyType;
    #[NotBlank(message: 'Тип загрузки не может быть пустым')]
    #[Type('string')]
    readonly public string $downloadingType;
    #[NotBlank(message: 'Тип выгрузки не может быть пустым')]
    #[Type('string')]
    readonly public string $unloadingType;
    #[NotBlank(message: '')]
    #[Type('string')]
    readonly public string $priceType;
    #[NotBlank(message: 'Цена С НДС, безнал не может быть пустым')]
    #[Type('string')]
    readonly public string $priceWithoutTax;
    #[NotBlank(message: 'Цена Без НДС, безнал не может быть пустым')]
    #[Type('string')]
    readonly public string $priceWithTax;
    #[NotBlank(message: 'Цена(наличные) не может быть пустым')]
    #[Type('string')]
    readonly public string $priceCash;
}
