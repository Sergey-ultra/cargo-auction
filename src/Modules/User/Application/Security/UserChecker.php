<?php

declare(strict_types=1);

namespace App\Modules\User\Application\Security;

use App\Modules\User\Domain\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->isVerified()) {
            throw new CustomUserMessageAccountStatusException('Аккаунт не верифицирован.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
    }
}
