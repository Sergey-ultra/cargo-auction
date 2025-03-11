<?php

declare(strict_types=1);

namespace App\ApiGateway\DTO;

class LoadFilter
{
    public ?string $fromAddress;
    public ?int $fromAddressId;
    public ?string $fromRadius;
    public ?string $toAddress;
    public ?int $toAddressId;
    public ?string $toRadius;
    public ?string $weightMin;
    public ?string $weightMax;
    public ?string $volumeMin;
    public ?string $volumeMax;

    /** @return array{
     *     fromAddress: string|null,
     *     fromAddressId: int|null,
     *     fromRadius: string|null,
     *     toAddress: string|null,
     *     toAddressId: int|null,
     *     toRadius: string|null,
     *     weightMin: string|null,
     *     weightMax: string|null,
     *     volumeMin: string|null,
     *     volumeMax: string|null,
     * }
     */
    public function toArray(): array
    {
        return (array)$this;
    }
}
