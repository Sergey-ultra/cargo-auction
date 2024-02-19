<?php

declare(strict_types=1);

namespace App\Modules\Load\Infrastructure\Api;

use App\ApiGateway\DTO\FilterSaveDTO;
use App\Modules\Load\Domain\Entity\Filter;
use App\Modules\Load\Infrastructure\Repository\FilterRepository;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class FilterApi
{
    public function __construct(private FilterRepository $filterRepository)
    {
    }

    public function getFiltersByUser(UserInterface $user): array
    {
        return $this->filterRepository->findBy(['user' => $user]);
    }

    public function save(FilterSaveDTO $filterDto, UserInterface $user): void
    {
        $filter = new Filter();
        $filter
            ->setName($filterDto->name)
            ->setFilter($filterDto->filter->toArray())
            ->setUser($user);

        $this->filterRepository->save($filter);
    }
}
