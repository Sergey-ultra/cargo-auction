<?php

declare(strict_types=1);

namespace App\Modules\Load\Domain\Entity;

class BodyType
{
//    public const BODY_TYPES = [
//        ['Name' => '20\' танк-контейнер', 'TypeId' => 57000],
//        ['Name' => '40\' танк-контейнер', 'TypeId' => 57500],
//        ['Name' => 'автобус', 'TypeId' => 10800],
//        ['Name' => 'автовоз', 'TpeId' => 20300],
//        ['Name' => 'автовышка', 'TypeId' => 20350],
//        ['Name' => 'автотранспортер', 'TypeId' => 10100],
//        ['Name' => 'автоцистерна', 'TypeId' => 10200],
//        ['Name' => 'балковоз(негабарит)', 'TypeId' => 20560],
//        ['Name' => 'бензовоз', 'TypeId' => 20700],
//        ['Name' => 'бетоновоз', 'TypeId' => 20500],
//        ['Name' => 'битумовоз', 'TypeId' => 20550],
//        ['Name' => 'бортовой', 'TypeId' => 1100],
//        ['Name' => 'вездеход', 'TypeId' => 20750],
//        ['Name' => 'все закр.+изотерм', 'TypeId' => 30000],
//        ['Name' => 'все открытые', 'TypeId' => 20000],
//        ['Name' => 'газовоз', 'TypeId' => 10600],
//        ['Name' => 'грузопассажирский', 'TypeId' => 55000],
//        ['Name' => 'допельшток', 'TypeId' => 58500],
//        ['Name' => 'зерновоз', 'TypeId' => 40000],
//        ['Name' => 'изотермический', 'TypeId' => 400],
//        ['Name' => 'клюшковоз', 'TypeId' => 55500],
//        ['Name' => 'коневоз', 'TypeId' => 1280],
//        ['Name' => 'конт.площадка'],
//        ['Name' => 'контейнер', 'TypeId' => 100],
//        ['Name' => 'контейнеровоз', 'TypeId' => 1300],
//        ['Name' => 'кормовоз', 'TypeId' => 1250],
//        ['Name' => 'кран', 'TypeId' => 10000],
//        ['Name' => 'лесовоз', 'TypeId' => 10300],
//        ['Name' => 'ломовоз', 'TypeId' => 10330],
//        ['Name' => 'манипулятор', 'TypeId' => 1350],
//        ['Name' => 'мега фура', 'TypeId' => 58000],
//        ['Name' => 'микроавтобус', 'TypeId' => 600],
//        ['Name' => 'муковоз', 'TypeId' => 20200],
//        ['Name' => 'мусоровоз', 'TypeId' => 56000],
//        ['Name' => 'негабарит', 'TypeId' => 5000],
//        ['Name' => 'низкорам.платф.', 'TypeId' => 10550],
//        ['Name' => 'низкорамный', 'TypeId' => 10500],
//        ['Name' => 'открытый конт.', 'TypeId' => 1150],
//        ['Name' => 'панелевоз', 'TypeId' => 10320],
//        ['Name' => 'пикап', 'TypeId' => 1170],
//        ['Name' => 'пирамида', 'TypeId' => 20850],
//        ['Name' => 'площадка без бортов', 'TypeId' => 1355],
//        ['Name' => 'пухтовоз', 'TypeId' => 20860],
//        ['Name' => 'Раздвижной полуприцеп 20\'/40\'', 'TypeId' => 59000],
//        ['Name' => 'реф. мультирежимный', 'TypeId' => 312],
//        ['Name' => 'реф. с перегородкой', 'TypeId' => 310],
//        ['Name' => 'реф.+изотерм', 'TypeId' => 50000],
//        ['Name' => 'реф.-тушевоз', 'TypeId' => 20800],
//        ['Name' => 'рефрижератор', 'TypeId' => 300],
//        ['Name' => 'рефрижератор', 'TypeId' => 300],
//        ['Name' => 'рулоновоз', 'TypeId' => 20870],
//        ['Name' => 'самосвал', 'TypeId' => 1200],
//        ['Name' => 'седельный тягач', 'TypeId' => 10400],
//        ['Name' => 'скотовоз', 'TypeId' => 10900],
//        ['Name' => 'стекловоз', 'TypeId' => 10950],
//        ['Name' => 'телескопический', 'TypeId' => 10570],
//        ['Name' => 'тентованный', 'TypeId' => 200],
//        ['Name' => 'трал', 'TypeId' => 10700],
//        ['Name' => 'трубовоз', 'TypeId' => 10350],
//        ['Name' => 'фургон', 'TypeId' => 500],
//        ['Name' => 'цельнометалл.', 'TypeId' => 700],
//        ['Name' => 'цементовоз', 'TypeId' => 20100],
//        ['Name' => 'шаланда', 'TypeId' => 1400],
//        ['Name' => 'щеповоз', 'TypeId' => 20150],
//        ['Name' => 'эвакуатор', 'TypeId' => 20600],
//    ];

    const TYPES = [
        [
            "dictionary_item_id" => 100,
            "attributes_dictionary" =>
                [
                    "car_type" => "контейнер",
                    "short" => "конт.",
                    "base_type" => "30000",
                    "type_order" => "50",
                    "attribs" => "4",
                    "mask" => "2"
                ]
        ],
        [
            "dictionary_item_id" => 200,
            "attributes_dictionary" =>
                [
                    "car_type" => "тентованный",
                    "short" => "тент.",
                    "base_type" => "30000",
                    "type_order" => "20",
                    "attribs" => "4",
                    "mask" => "1"
                ]
        ],
        [
            "dictionary_item_id" => 300,
            "attributes_dictionary" =>
                [
                    "car_type" => "рефрижератор",
                    "short" => "реф.",
                    "base_type" => "50000",
                    "type_order" => "85",
                    "attribs" => "5",
                    "mask" => "4"
                ]
        ],
        [
            "dictionary_item_id" => 310,
            "attributes_dictionary" => [
                "car_type" => "реф. с перегородкой",
                "short" => "реф.с перег.",
                "base_type" => "50000",
                "type_order" => "87",
                "attribs" => "5",
                "mask" => "281474976710656"
            ]
        ],
        [
            "dictionary_item_id" => 312,
            "attributes_dictionary" => [
                "car_type" => "реф. мультирежимный",
                "short" => "реф.мульт.",
                "base_type" => "50000",
                "type_order" => "86",
                "attribs" => "5",
                "mask" => "562949953421312"
            ]
        ],
        [
            "dictionary_item_id" => 400,
            "attributes_dictionary" => [
                "car_type" => "изотермический",
                "short" => "изотерм",
                "base_type" => "0",
                "type_order" => "75",
                "attribs" => "7",
                "mask" => "8"
            ]
        ],
        [
            "dictionary_item_id" => 500,
            "attributes_dictionary" => [
                "car_type" => "фургон",
                "short" => "фург.",
                "base_type" => "30000",
                "type_order" => "60",
                "attribs" => "4",
                "mask" => "16"
            ]
        ],
        [
            "dictionary_item_id" => 600,
            "attributes_dictionary" => [
                "car_type" => "микроавтобус",
                "short" => "микр.",
                "base_type" => "0",
                "type_order" => "286",
                "mask" => "32"
            ]
        ],
        [
            "dictionary_item_id" => 700,
            "attributes_dictionary" => [
                "car_type" => "цельнометалл.",
                "short" => "цмет.",
                "base_type" => "30000",
                "type_order" => "70",
                "mask" => "64"
            ]
        ],
        [
            "dictionary_item_id" => 1100,
            "attributes_dictionary" => [
                "car_type" => "бортовой",
                "short" => "борт.",
                "base_type" => "20000",
                "type_order" => "100",
                "attribs" => "12",
                "mask" => "128"
            ]
        ],
        [
            "dictionary_item_id" => 1150,
            "attributes_dictionary" =>
                [
                    "car_type" => "открытый конт.",
                    "short" => "откр.конт.",
                    "base_type" => "20000",
                    "type_order" => "120",
                    "attribs" => "4",
                    "mask" => "1024"
                ]
        ], [
            "dictionary_item_id" => 1170,
            "attributes_dictionary" => [
                "car_type" => "пикап",
                "short" => "пикап",
                "base_type" => "0",
                "type_order" => "295",
                "attribs" => "8",
                "mask" => "68719476736"
            ]
        ],
        [
            "dictionary_item_id" => 1200,
            "attributes_dictionary" => [
                "car_type" => "самосвал",
                "short" => "ссвл.",
                "base_type" => "20000",
                "type_order" => "130",
                "attribs" => "4",
                "mask" => "4096"
            ]
        ],
        [
            "dictionary_item_id" => 1250,
            "attributes_dictionary" => [
                "car_type" => "кормовоз",
                "short" => "корм.",
                "base_type" => "0",
                "type_order" => "265",
                "attribs" => "0",
                "mask" => "4194304"
            ]
        ],
        [
            "dictionary_item_id" => 1280,
            "attributes_dictionary" => [
                "car_type" => "коневоз",
                "short" => "кони.",
                "base_type" => "0",
                "type_order" => "255",
                "attribs" => "0",
                "mask" => "137438953472"
            ]
        ],
        [
            "dictionary_item_id" => 1300,
            "attributes_dictionary" => [
                "car_type" => "контейнеровоз",
                "short" => "конт-воз",
                "base_type" => "0",
                "type_order" => "260",
                "attribs" => "12",
                "mask" => "2097152"
            ]
        ],
        [
            "dictionary_item_id" => 1350,
            "attributes_dictionary" => [
                "car_type" => "манипулятор",
                "short" => "манип",
                "base_type" => "0",
                "type_order" => "283",
                "attribs" => "8",
                "mask" => "256"
            ]
        ],
        [
            "dictionary_item_id" => 1355,
            "attributes_dictionary" =>
                [
                    "car_type" => "площадка без бортов",
                    "short" => "безборт.",
                    "base_type" => "20000",
                    "type_order" => "123",
                    "attribs" => "12",
                    "mask" => "70368744177664"]],
        [
            "dictionary_item_id" => 1400,
            "attributes_dictionary" => [
                "car_type" => "шаланда",
                "short" => "шал.",
                "base_type" => "20000",
                "type_order" => "140",
                "attribs" => "8",
                "mask" => "8192"
            ]
        ],
        [
            "dictionary_item_id" => 5000,
            "attributes_dictionary" => [
                "car_type" => "негабарит",
                "short" => "негаб.",
                "base_type" => "0",
                "type_order" => "180",
                "attribs" => "6",
                "mask" => "18726594281984"
            ]
        ],
        [
            "dictionary_item_id" => 10000,
            "attributes_dictionary" => [
                "car_type" => "кран",
                "short" => "кран",
                "base_type" => "0",
                "type_order" => "270",
                "attribs" => "8",
                "mask" => "8388608"
            ]
        ],
        [
            "dictionary_item_id" => 10100,
            "attributes_dictionary" => [
                "car_type" => "автотранспортер",
                "short" => "автт.",
                "base_type" => "0",
                "type_order" => "220",
                "attribs" => "8",
                "mask" => "131072"
            ]
        ],
        [
            "dictionary_item_id" => 10200,
            "attributes_dictionary" => [
                "car_type" => "автоцистерна",
                "short" => "автоцист.",
                "base_type" => "0",
                "type_order" => "370",
                "attribs" => "4",
                "mask" => "8589934592"
            ]
        ],
        [
            "dictionary_item_id" => 10300,
            "attributes_dictionary" => [
                "car_type" => "лесовоз",
                "short" => "лесв.",
                "base_type" => "0",
                "type_order" => "280",
                "attribs" => "8",
                "mask" => "16777216"
            ]
        ],
        [
            "dictionary_item_id" => 10320,
            "attributes_dictionary" => [
                "car_type" => "панелевоз",
                "short" => "панв.",
                "base_type" => "0",
                "type_order" => "294",
                "attribs" => "12",
                "mask" => "2048"
            ]
        ],
        [
            "dictionary_item_id" => 10330,
            "attributes_dictionary" => [
                "car_type" => "ломовоз",
                "short" => "лом.",
                "base_type" => "0",
                "type_order" => "282",
                "attribs" => "8",
                "mask" => "1125899906842624"
            ]
        ],
        [
            "dictionary_item_id" => 10350,
            "attributes_dictionary" => [
                "car_type" => "трубовоз",
                "short" => "труб.",
                "base_type" => "0",
                "type_order" => "350",
                "attribs" => "12",
                "mask" => "1073741824"
            ]
        ],
        [
            "dictionary_item_id" => 10400,
            "attributes_dictionary" => [
                "car_type" => "седельный тягач",
                "short" => "тягач",
                "base_type" => "0",
                "type_order" => "310",
                "attribs" => "8",
                "mask" => "67108864"
            ]
        ],
        [
            "dictionary_item_id" => 10500,
            "attributes_dictionary" => [
                "car_type" => "низкорамный",
                "short" => "рамн.",
                "base_type" => "5000",
                "type_order" => "185",
                "attribs" => "8",
                "mask" => "512"
            ]
        ],
        [
            "dictionary_item_id" => 10550,
            "attributes_dictionary" => [
                "car_type" => "низкорам.платф.",
                "short" => "нпл.",
                "base_type" => "5000",
                "type_order" => "190",
                "attribs" => "8",
                "mask" => "34359738368"
            ]
        ],
        [
            "dictionary_item_id" => 10570,
            "attributes_dictionary" => [
                "car_type" => "телескопический",
                "short" => "телскп.",
                "base_type" => "5000",
                "type_order" => "195",
                "attribs" => "8",
                "mask" => "1099511627776"
            ]
        ],
        [
            "dictionary_item_id" => 10600,
            "attributes_dictionary" => [
                "car_type" => "газовоз",
                "short" => "газ.",
                "base_type" => "0",
                "type_order" => "240",
                "attribs" => "1",
                "mask" => "524288"
            ]
        ],
        [
            "dictionary_item_id" => 10700,
            "attributes_dictionary" => [
                "car_type" => "трал",
                "short" => "трал",
                "base_type" => "5000",
                "type_order" => "200",
                "attribs" => "8",
                "mask" => "536870912"
            ]
        ],
        [
            "dictionary_item_id" => 10800,
            "attributes_dictionary" => [
                "car_type" => "автобус",
                "short" => "авт.",
                "base_type" => "0",
                "type_order" => "205",
                "attribs" => "4",
                "mask" => "16384"
            ]
        ],
        [
            "dictionary_item_id" => 10900,
            "attributes_dictionary" => [
                "car_type" => "скотовоз",
                "short" => "скот.",
                "base_type" => "0",
                "type_order" => "320",
                "mask" => "134217728"
            ]
        ],
        [
            "dictionary_item_id" => 10950,
            "attributes_dictionary" => [
                "car_type" => "стекловоз",
                "short" => "сткл.",
                "base_type" => "0",
                "type_order" => "330",
                "mask" => "268435456"
            ]
        ],
        [
            "dictionary_item_id" => 20000,
            "attributes_dictionary" => [
                "car_type" => "все открытые",
                "short" => "откр.",
                "base_type" => "0",
                "type_order" => "90",
                "attribs" => "6",
                "mask" => "70368744191104"
            ]
        ],
        [
            "dictionary_item_id" => 20100,
            "attributes_dictionary" => [
                "car_type" => "цементовоз",
                "short" => "цем.",
                "base_type" => "0",
                "type_order" => "360",
                "mask" => "2147483648"
            ]
        ],
        [
            "dictionary_item_id" => 20150,
            "attributes_dictionary" => [
                "car_type" => "щеповоз",
                "short" => "щеп.",
                "base_type" => "0",
                "type_order" => "375",
                "attribs" => "0",
                "mask" => "4294967296"
            ]
        ],
        [
            "dictionary_item_id" => 20200,
            "attributes_dictionary" => [
                "car_type" => "муковоз",
                "short" => "мук.",
                "base_type" => "0",
                "type_order" => "290",
                "mask" => "33554432"
            ]
        ],
        [
            "dictionary_item_id" => 20300,
            "attributes_dictionary" => [
                "car_type" => "автовоз",
                "short" => "автв.",
                "base_type" => "0",
                "type_order" => "210",
                "attribs" => "8",
                "mask" => "32768"
            ]
        ],
        [
            "dictionary_item_id" => 20350,
            "attributes_dictionary" => [
                "car_type" => "автовышка",
                "short" => "вышк.",
                "base_type" => "0",
                "type_order" => "215",
                "attribs" => "8",
                "mask" => "65536"
            ]
        ],
        [
            "dictionary_item_id" => 20500,
            "attributes_dictionary" => [
                "car_type" => "бетоновоз",
                "short" => "бет.",
                "base_type" => "0",
                "type_order" => "230",
                "mask" => "262144"
            ]
        ],
        [
            "dictionary_item_id" => 20550,
            "attributes_dictionary" => [
                "car_type" => "битумовоз",
                "short" => "битум",
                "base_type" => "0",
                "type_order" => "232",
                "attribs" => "0",
                "mask" => "2199023255552"
            ]
        ],
        [
            "dictionary_item_id" => 20560,
            "attributes_dictionary" => [
                 "car_type" => "балковоз(негабарит)",
                "short" => "балк.",
                "base_type" => "5000",
                "type_order" => "203",
                "attribs" => "8",
                "mask" => "17592186044416"
            ]
        ],
        [
            "dictionary_item_id" => 20600,
            "attributes_dictionary" => [
                "car_type" => "эвакуатор",
                "short" => "эвак.",
                "base_type" => "0",
                "type_order" => "400",
                "attribs" => "8",
                "mask" => "17179869184"
            ]
        ],
        [
            "dictionary_item_id" => 20700,
            "attributes_dictionary" => [
                "car_type" => "бензовоз",
                "short" => "бенз.",
                "base_type" => "0",
                "type_order" => "235",
                "attribs" => "4",
                "mask" => "274877906944"
            ]
        ],
        [
            "dictionary_item_id" => 20750,
            "attributes_dictionary" => [
                "car_type" => "вездеход",
                "short" => "вздхд.",
                "base_type" => "0",
                "type_order" => "237",
                "attribs" => "0",
                "mask" => "549755813888"
            ]
        ],
        [
            "dictionary_item_id" => 20800,
            "attributes_dictionary" => [
                "car_type" => "реф.-тушевоз",
                "short" => "р-туш.",
                "base_type" => "0",
                "type_order" => "89",
                "attribs" => "5",
                "mask" => "4398046511104"
            ]
        ],
        [
            "dictionary_item_id" => 20850,
            "attributes_dictionary" => [
                "car_type" => "пирамида",
                "short" => "пирам.",
                "base_type" => "0",
                "type_order" => "297",
                "attribs" => "8",
                "mask" => "8796093022208"
            ]
        ],
        [
            "dictionary_item_id" => 20860,
            "attributes_dictionary" => [
                "car_type" => "пухтовоз",
                "short" => "пухта",
                "base_type" => "0",
                "type_order" => "296",
                "attribs" => "8",
                "mask" => "140737488355328"
            ],
        ],
        [
            "dictionary_item_id" => 20870,
            "attributes_dictionary" => [
                "car_type" => "рулоновоз",
                "short" => "рул.",
                "base_type" => "0",
                "type_order" => "300",
                "attribs" => "8",
                "mask" => "35184372088832"
            ]
        ],
        [
            "dictionary_item_id" => 30000,
            "attributes_dictionary" => [
                "car_type" => "все закр.+изотерм",
                "short" => "закр.+терм.",
                "base_type" => "0",
                "type_order" => "10",
                "attribs" => "6",
                "mask" => "91"]
        ],
        [
            "dictionary_item_id" => 40000,
            "attributes_dictionary" => [
                "car_type" => "зерновоз",
                "short" => "зерн.",
                "base_type" => "0",
                "type_order" => "250",
                "mask" => "1048576"
            ]
        ],
        [
            "dictionary_item_id" => 50000,
            "attributes_dictionary" => [
                "car_type" => "реф.+изотерм",
                "short" => "реф.+терм.",
                "base_type" => "0",
                "type_order" => "80",
                "attribs" => "6",
                "mask" => "844424930131980"
            ]
        ],
        [
            "dictionary_item_id" => 55000,
            "attributes_dictionary" => [
                "car_type" => "грузопассажирский",
                "short" => "грузпас.",
                "base_type" => "0",
                "type_order" => "410",
                "attribs" => "0",
                "mask" => "2251799813685248"
            ]
        ],
        [
            "dictionary_item_id" => 55500,
            "attributes_dictionary" => [
            "car_type" => "клюшковоз",
                "short" => "клюшк.",
                "base_type" => "0",
                "type_order" => "420",
                "attribs" => "0",
                "mask" => "4503599627370496"
            ]
        ],
        [
            "dictionary_item_id" => 56000,
            "attributes_dictionary" => [
                "car_type" => "мусоровоз",
                "short" => "мусор.",
                "base_type" => "0",
                "type_order" => "430",
                "attribs" => "0",
                "mask" => "9007199254740992"
            ]
        ],
        [
            "dictionary_item_id" => 56500,
            "attributes_dictionary" => [
                "car_type" => "jumbo",
                "short" => "jumbo",
                "base_type" => "0",
                "type_order" => "440",
                "attribs" => "0",
                "mask" => "18014398509481984"
            ]
        ],
        [
            "dictionary_item_id" => 57000,
            "attributes_dictionary" => [
                "car_type" => "20' танк-контейнер",
                "short" => "20' танк-конт.",
                "base_type" => "0",
                "type_order" => "450",
                "attribs" => "0",
                "mask" => "36028797018963968"
            ]
        ],
        [
            "dictionary_item_id" => 57500,
            "attributes_dictionary" => [
                "car_type" => "40' танк-контейнер",
                "short" => "40' танк-конт.",
                "base_type" => "0",
                "type_order" => "460",
                "attribs" => "0",
                "mask" => "72057594037927936"
            ]
        ],
        [
            "dictionary_item_id" => 58000,
            "attributes_dictionary" => [
                "car_type" => "мега фура",
                "short" => "мега",
                "base_type" => "0",
                "type_order" => "470",
                "attribs" => "0",
                "mask" => "144115188075855872"
            ]
        ],
        [
            "dictionary_item_id" => 58500,
            "attributes_dictionary" => [
                "car_type" => "допельшток",
                "short" => "допельшток",
                "base_type" => "0",
                "type_order" => "480",
                "attribs" => "0",
                "mask" => "288230376151711744"
            ]
        ],
        [
            "dictionary_item_id" => 59000,
            "attributes_dictionary" => [
                "car_type" => "раздвижной полуприцеп 20'/40'",
                "short" => "раздв. полу. 20'/40'",
                "base_type" => "0",
                "type_order" => "490",
                "attribs" => "0",
                "mask" => "576460752303423488"
            ]
        ]
    ];

    public static function getTransformedTypes(): array
    {
        return array_map(
            static function (array $item) {
                $result = [
                    'id' => $item['attributes_dictionary']['mask'],
                    'attribs' => $item['attributes_dictionary']['attribs'] ?? null,
                    'name' => $item['attributes_dictionary']['car_type'],
                    'position' => (int)$item['attributes_dictionary']['type_order'],
                    'short_name' => $item['attributes_dictionary']['short'],
                    'typeId' => (int)$item['dictionary_item_id'],
                    'parentTypeId' => (int)$item['attributes_dictionary']['base_type'],
                ];

                if (in_array($item['dictionary_item_id'], [400, 30000, 50000] ,true)) {
                    $result['hasIsoterm'] = in_array($item['dictionary_item_id'], [30000, 50000],true);
                }

                return $result;
            },
            self::TYPES
        );
    }

    public static function getSortedItems(): array
    {
        $sortOrderArray = array_column(array_column(self::TYPES, 'attributes_dictionary'), 'type_order');

        $result = self::TYPES;
        array_multisort( $sortOrderArray, $result);
        return $result;
    }

    public static function getTypesMapByMask(): array
    {
        $attributesCollection = array_column(self::TYPES, 'attributes_dictionary');
        return array_column($attributesCollection, null, 'mask');
    }

    public static function getTypesMapById(): array
    {
        $result = [];
        foreach(self::TYPES as $type) {
            $result[$type['dictionary_item_id']] = $type['attributes_dictionary'];
        }

        return $result;
    }
}
