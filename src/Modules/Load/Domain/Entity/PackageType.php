<?php

declare(strict_types=1);

namespace App\Modules\Load\Domain\Entity;

class PackageType
{
    public const PACKAGE_TYPES = [
        [
            'value' => 'упаковка',
            'children' => [
                "bigbag" => 'биг бэги',
                "pallet" => 'паллеты',
                "box" => 'коробки',
                "case" => 'ящики',
                "barrel" => 'бочки',
                "bag" => 'мешки/сетки',
                "pack" => 'пачки',
            ],
        ],
        [
            'value' => "без упаковки",
            'children' => [
                "in_bulk" => 'навалом/насыпью',
                "fill" => 'наливной груз',
                "other" => 'другая'
            ],
        ],
    ];
}
