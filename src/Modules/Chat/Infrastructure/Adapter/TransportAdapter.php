<?php

declare(strict_types=1);

namespace App\Modules\Chat\Infrastructure\Adapter;

use App\Modules\Transport\Infrastructure\Api\TransportApi;

final readonly class TransportAdapter
{
    public function __construct(private TransportApi $transportApi)
    {
    }

    public function getLoadDraftMessageById(int $transportId): string
    {
        return $this->transportApi->getLoadDraftMessageById($transportId);
    }
}
