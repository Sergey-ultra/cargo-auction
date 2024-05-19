<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO\Request;

use DateTimeInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class DatesDTO
{
    public function __construct(
        #[NotBlank(message: 'Статус загрузки не может быть пустым')]
        public string $type,
        //#[NotBlank(message: 'Дата загрузки не может быть пустым')]
                      //#[DateTime(format:'Y-m-d H:i:s')]
        public ?DateTimeInterface $firstDate,
        public ?DateTimeInterface $lastDate,
        public ?string $periodicity,
        public TimeDTO $time,
    )
    {
    }
}
