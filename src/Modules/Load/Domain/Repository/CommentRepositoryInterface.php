<?php

namespace App\Modules\Load\Domain\Repository;

use App\Modules\Load\Domain\Entity\Comment;

interface CommentRepositoryInterface
{
    public function save(Comment $comment): void;
}
