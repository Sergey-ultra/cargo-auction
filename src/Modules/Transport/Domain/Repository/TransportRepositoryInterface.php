<?php

namespace App\Modules\Transport\Domain\Repository;

use App\Modules\Transport\Domain\Entity\Transport;
use App\Modules\Transport\Infrastructure\DTO\FilterDTO;
use App\Modules\Transport\Infrastructure\DTO\ListDTO;
use Symfony\Component\Security\Core\User\UserInterface;

interface TransportRepositoryInterface
{
    public const PAGINATOR_PER_PAGE = 10;
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';
    public const DOWNLOADING_DATE = 'downloading_date';
    public const CARGO_TYPE = 'cargo_type';

    public const OPTIONS = [
        self::CREATED_AT => 'времени добавления',
        self::UPDATED_AT => 'времени обновления',
        self::DOWNLOADING_DATE => 'дате загрузки',
        self::CARGO_TYPE => 'типу груза',
    ];
    public function getList(
        ?FilterDTO     $filter,
        int            $page = 1,
        int            $perPage = self::PAGINATOR_PER_PAGE,
        string         $orderOption = self::CREATED_AT,
        ?UserInterface $byUser = null
    ): ListDTO;

    public function save(Transport $transport): void;
}
