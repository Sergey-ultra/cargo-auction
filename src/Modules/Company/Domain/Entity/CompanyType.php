<?php

declare(strict_types=1);

namespace App\Modules\Company\Domain\Entity;

class CompanyType
{
    public const COMPANY_TYPES = [
        1 => 'Перевозчик',
        2 => 'Экспедитор',
        6 => 'Грузовладелец-перевозчик',
    ];
}
