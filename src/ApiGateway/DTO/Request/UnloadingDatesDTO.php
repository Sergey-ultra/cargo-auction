<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO\Request;

use DateTimeInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class UnloadingDatesDTO
{
    public function __construct(
        //#[NotBlank(message: 'Дата загрузки не может быть пустым')]
        //#[DateTime(format:'Y-m-d H:i:s')]
        public ?DateTimeInterface $firstDate,
        public TimeDTO $time,
    )
    {
    }
}
