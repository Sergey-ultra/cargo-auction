<?php

namespace App\Modules\Transport\Domain\Repository;

use App\Modules\Transport\Domain\Entity\Comment;

interface CommentRepositoryInterface
{
    public function save(Comment $comment): void;
}
