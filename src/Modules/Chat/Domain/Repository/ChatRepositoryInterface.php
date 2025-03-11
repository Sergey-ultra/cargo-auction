<?php

namespace App\Modules\Chat\Domain\Repository;

use App\Modules\Chat\Domain\Entity\Chat;
use Symfony\Component\Security\Core\User\UserInterface;

interface ChatRepositoryInterface
{
    /** @return Chat[] */
    public function getMyChats(UserInterface $owner, int $perPage): array;
    public function getByUserId(UserInterface $owner, UserInterface $partner, string $draftMessage): Chat;
}
