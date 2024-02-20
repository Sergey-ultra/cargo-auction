<?php

namespace App\Modules\Filter\Domain\Enum;

enum FilterType: string
{
    case LOAD = 'load';
    case TRANSPORT = 'transport';
}
