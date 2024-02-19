<?php

declare(strict_types=1);

namespace App\Modules\Chat\Infrastructure\Adapter;

use App\Modules\User\Infrastructure\Api\UserApi;
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
}
