<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
final readonly class LoadCreateDTO
{
    public function __construct(
        #[NotBlank(message: 'Статус загрузки не может быть пустым')]
        public string $downloadingDateStatus,
        #[NotBlank(message: 'Дата загрузки не может быть пустым')]
        //#[DateTime(format:'Y-m-d H:i:s')]
        public  string $downloadingDate,
        #[NotBlank(message: 'Откуда (адрес) не может быть пустым')]
        public int $toCityId,
        #[NotBlank(message: 'От (адрес) не может быть пустым')]
        public string $toAddress,
        #[NotBlank(message: 'Откуда (адрес) не может быть пустым')]
        public int $fromCityId,
        #[NotBlank(message: 'Откуда (адрес) не может быть пустым')]
        public string $fromAddress,
        #[NotBlank(message: 'Вес не может быть пустым')]
        #[Type(type: 'float', message: 'Значение должно быть float')]
        public float $weight,
        #[NotBlank(message: 'Объем не может быть пустым')]
        #[Type(type: 'float', message:'Значение должно быть float')]
        public float $volume,
        #[NotBlank(message: 'Тип груза не может быть пустым')]
        public string $cargoType,
        #[NotBlank(message: 'Кузов не может быть пустым')]
        public string $bodyType,
        #[NotBlank(message: 'Тип загрузки не может быть пустым')]
        #[Type('string')]
        public string $downloadingType,
        #[NotBlank(message: 'Тип выгрузки не может быть пустым')]
        #[Type('string')]
        public string $unloadingType,
        #[NotBlank(message: '')]
        #[Type('string')]
        public string $priceType,
        #[NotBlank(message: 'Цена С НДС, безнал не может быть пустым')]
        #[Type('string')]
        public string $priceWithoutTax,
        #[NotBlank(message: 'Цена Без НДС, безнал не может быть пустым')]
        #[Type('string')]
        public string $priceWithTax,
        #[NotBlank(message: 'Цена(наличные) не может быть пустым')]
        #[Type('string')]
        public string $priceCash,
    ) {
    }
}
