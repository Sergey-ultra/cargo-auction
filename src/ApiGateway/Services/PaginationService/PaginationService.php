<?php

declare(strict_types=1);

namespace App\ApiGateway\Services\PaginationService;

class PaginationService
{
    public function getBorders(int $page, int $lastPage): array
    {
        return [
            max($page - 4 ,1),
            min($page + 4, $lastPage)
        ];
    }
}
