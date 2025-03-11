<?php

declare(strict_types=1);

namespace App\Modules\Load\Domain\Entity;

class LoadingType
{
    public const LOADING_TYPES = [
        'верхняя',
        'боковая',
        'задняя',
        'с полной растентовкой',
        'со снятием поперечных перекладин',
        'со снятием стоек',
        'без ворот',
        'гидроборт',
        'аппарели',
        'с обрешеткой',
        'с бортами',
        'боковая с 2-х сторон',
        'налив',
        'электрический',
        'гидравлический',
        'пневматический',
        'дизельный компрессор',
    ];

    const TYPES = [
        [
            "dictionary_item_id" => 1,
            "attributes_dictionary" => [
                "name" => "верхняя",
                "short" => "верх."
            ]
        ],
        [
            "dictionary_item_id" => 2,
            "attributes_dictionary" => [
                "name" => "боковая",
                "short" => "бок."
            ]
        ],
        [
            "dictionary_item_id" => 4,
            "attributes_dictionary" => [
                "name" => "задняя",
                "short" => "задн."
            ]
        ],
        [
            "dictionary_item_id" => 8,
            "attributes_dictionary" => [
                "name" => "с полной растентовкой",
                "short" => "с полн.раст."
            ]
        ],
        [
            "dictionary_item_id" => 32,
            "attributes_dictionary" => [
                "name" => "со снятием поперечных перекладин",
                "short" => "сн.поп.перекл."
            ]
        ],
        [
            "dictionary_item_id" => 64,
            "attributes_dictionary" => [
                "name" => "со снятием стоек",
                "short" => "сн.стоек"
            ]
        ],
        [
            "dictionary_item_id" => 128,
            "attributes_dictionary" => [
                "name" => "без ворот",
                "short" => "б.ворот"
            ]
        ],
        [
            "dictionary_item_id" => 256,
            "attributes_dictionary" => [
                "name" => "гидроборт",
                "short" => "гидр.б."
            ]
        ],
        [
            "dictionary_item_id" => 512,
            "attributes_dictionary" => [
                "name" => "аппарели",
                "short" => "апп."
            ]
        ],
        [
            "dictionary_item_id" => 1024,
            "attributes_dictionary" => [
                "name" => "с обрешеткой",
                "short" => "реш."
            ]
        ],
        [
            "dictionary_item_id" => 2048,
            "attributes_dictionary" => [
                "name" => "с бортами",
                "short" => "борт."
            ]
        ],
        [
            "dictionary_item_id" => 4096,
            "attributes_dictionary" => [
                "name" => "боковая с 2-х сторон",
                "short" => "2-бок"
            ]
        ],
        [
            "dictionary_item_id" => 8192,
            "attributes_dictionary" => [
                "name" => "налив",
                "short" => "налив."
            ]
        ],
        [
            "dictionary_item_id" => 16384,
            "attributes_dictionary" => [
                "name" => "электрический",
                "short" => "электр."
            ]
        ],
        [
            "dictionary_item_id" => 32768,
            "attributes_dictionary" => [
                "name" => "гидравлический",
                "short" => "гидравл."
            ]
        ],
        [
            "dictionary_item_id" => 131072,
            "attributes_dictionary" => [
                "name" => "пневматический",
                "short" => "пневм."
            ]
        ],
        [
            "dictionary_item_id" => 262144,
            "attributes_dictionary" => [
                "name" => "дизельный компрессор",
                "short" => "диз.компр."
            ]
        ]
    ];

    /** @return array<int, array{name: string, short_name: string, typeId: int}> */
    public static function getTransformedTypes(): array
    {
        return array_map(
            static function (array $item) {
                return [
                    'name' => $item['attributes_dictionary']['name'],
                    'short_name' => $item['attributes_dictionary']['short'],
                    'typeId' => (int)$item['dictionary_item_id'],
                ];
            },
            self::TYPES
        );
    }

    /** @return array<int, array{name: string, short: string}> */
    public static function getTypesMapById(): array
    {
        $result = [];
        foreach(self::TYPES as $type) {
            $result[$type['dictionary_item_id']] = $type['attributes_dictionary'];
        }

        return $result;
    }
}
