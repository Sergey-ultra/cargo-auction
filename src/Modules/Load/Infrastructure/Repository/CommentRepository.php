<?php

declare(strict_types=1);

namespace App\Modules\Load\Infrastructure\Repository;


use App\Modules\Load\Domain\Entity\Comment;
use App\Modules\Load\Domain\Repository\CommentRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class CommentRepository extends ServiceEntityRepository implements CommentRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function save(Comment $comment): void
    {
        $this->_em->persist($comment);
        $this->_em->flush();
    }

    public function delete(int $id): void
    {
        $em = $this->getEntityManager();
        $em->remove($em->getReference(Comment::class, $id));
        $em->flush();
    }
}
