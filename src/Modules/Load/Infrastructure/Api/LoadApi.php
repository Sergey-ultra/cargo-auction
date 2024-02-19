<?php

declare(strict_types=1);

namespace App\Modules\Load\Infrastructure\Api;

use App\Modules\Load\Infrastructure\Repository\LoadRepository;

final readonly class LoadApi
{
    public function __construct(private LoadRepository $loadRepository)
    {
    }

    public function getLoadOption(): array
    {
        return LoadRepository::LOAD_OPTIONS;
    }

    public function getLoadDraftMessageById(int $id): string
    {
        $load = $this->loadRepository->find($id);
        return sprintf(
            "По грузу: %s - %s, %d, %s, %d т., %d м3",
            $load->getFromAddress(),
            $load->getToAddress(),
            34,
            $load->getCargoType(),
            $load->getWeight(),
            $load->getVolume()
        );
    }
}
