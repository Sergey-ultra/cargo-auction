<?php

namespace App\Modules\Chat\Domain\Repository;

use App\Modules\Chat\Domain\Entity\Message;

interface MessageRepositoryInterface
{
    public function save(Message $message): void;
}
