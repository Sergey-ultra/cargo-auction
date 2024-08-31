<?php

declare(strict_types=1);

namespace App\Modules\Transport\Infrastructure\Adapter;

use App\ApiGateway\DTO\CompanyShowDTO;
use App\Modules\Company\Domain\Entity\Company;
use App\Modules\Company\Infrastructure\Api\CompanyApi;
use Doctrine\Common\Collections\ArrayCollection;

readonly class CompanyAdapter
{
    public function __construct(private CompanyApi $companyApi)
    {
    }

    public function getById(int $id): ?CompanyShowDTO
    {
        return $this->companyApi->getById($id);
    }

    /**
     * @param int[] $ids
     * @return ArrayCollection<int, CompanyShowDTO>
     */
    public function getByIds(array $ids): ArrayCollection
    {
        return $this->companyApi->getByIds($ids);
    }
}
