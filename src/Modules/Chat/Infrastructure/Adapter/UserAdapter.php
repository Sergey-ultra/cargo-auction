<?php

declare(strict_types=1);

namespace App\Modules\Chat\Infrastructure\Adapter;

use App\Modules\User\Domain\Entity\User;
use App\Modules\User\Infrastructure\Api\UserApi;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;

final readonly class UserAdapter
{
    public function __construct(private UserApi $userApi)
    {
    }

    public function getUserById(int $id): ?UserInterface
    {
        return $this->userApi->getUserById($id);
    }

    /**
     * @param int $id
     * @return User[]
     */
    public function getByCompanyId(int $id): array
    {
        return $this->userApi->getByCompanyId($id);
    }

    /**
     * @param int[] $ids
     * @return ArrayCollection<int, User[]>
     */
    public function getByCompanyIds(array $ids): ArrayCollection
    {
        return $this->userApi->getByCompanyIds($ids);
    }
}
