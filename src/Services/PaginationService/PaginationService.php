<?php

declare(strict_types=1);

namespace App\Services\PaginationService;

class PaginationService
{
    public function getBorders(int $page, int $lastPage): array
    {
//        if ($page < 4) {
//            return [1,  9];
//        } else if ($page > $lastPage - 4) {
//            return [$lastPage - 9 + 1, $lastPage];
//        }
        return [
            max($page - 4 ,1),
            min($page + 4, $lastPage)
        ];

    }
}
