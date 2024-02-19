<?php

declare(strict_types=1);

namespace App\Modules\Load\Domain\Entity;

class LoadingType
{
    public const LOADING_TYPES = [
        'аппарели',
        'без ворот',
        'боковая',
        'боковая с 2-х сторон',
        'верхняя',
        'гидроборт',
        'задняя',
        'с бортами',
        'с обрешеткой',
        'с полной растентовкой',
        'со снятием поперечных перекладин',
        'со снятием стоек',
    ];
}
