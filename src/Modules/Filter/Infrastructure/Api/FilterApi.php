<?php

declare(strict_types=1);

namespace App\Modules\Filter\Infrastructure\Api;

use App\ApiGateway\DTO\FilterSaveDTO;
use App\ApiGateway\Enum\FilterType;
use App\Modules\Filter\Domain\Enum\FilterType as ModuleFilterType;
use App\Modules\Filter\Domain\Entity\Filter;
use App\Modules\Filter\Infrastructure\Repository\FilterRepository;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class FilterApi
{
    public function __construct(private FilterRepository $filterRepository)
    {
    }

    public function getFiltersByUser(UserInterface $user, FilterType $type): array
    {
        return $this->filterRepository->findBy(['user' => $user, 'type' => $type->value]);
    }

    public function save(FilterSaveDTO $filterDto, UserInterface $user): void
    {
        $filter = new Filter();
        $filter
            ->setName($filterDto->name)
            ->setType(ModuleFilterType::from($filterDto->type->value))
            ->setFilter($filterDto->filter->toArray())
            ->setUser($user);

        $this->filterRepository->save($filter);
    }
}
