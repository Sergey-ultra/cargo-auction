<?php

declare(strict_types=1);

namespace App\Modules\Chat\Infrastructure\Adapter;

use App\Modules\Load\Infrastructure\Api\LoadApi;

class LoadAdapter
{
    public function __construct(private LoadApi $loadApi)
    {
    }

    public function getLoadDraftMessageById(int $loadId): string
    {
        return $this->loadApi->getLoadDraftMessageById($loadId);
    }
}
