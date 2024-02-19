<?php

declare(strict_types=1);

namespace App\Modules\User\Infrastructure\Api;

use App\Modules\User\Domain\Entity\User;
use App\Modules\User\Infrastructure\Repository\UserRepository;

final readonly class UserApi
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function getUserById(int $userId): ?User
    {
        return $this->userRepository->find($userId);
    }

}
