<?php

declare(strict_types=1);

namespace App\Modules\Load\Infrastructure\Api;

use App\ApiGateway\DTO\CommentDTO;
use App\ApiGateway\DTO\CommentShowDTO;
use App\Modules\Load\Domain\Entity\Comment;
use App\Modules\Load\Infrastructure\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;

class CommentApi
{
    public function __construct(
        private CommentRepository $commentRepository,
    )
    {
    }

    /**
     * @param int[] $ids
     * @param int $userId
     * @return ArrayCollection<int, CommentShowDTO>
     */
    public function getByLoadIds(array $ids, int $userId): ArrayCollection
    {
        $list = $this->commentRepository->findBy(['loadId' => $ids, 'userId' => $userId]);

        $collection = new ArrayCollection();

        foreach($list as $comment) {
            $collection->set(
                $comment->getLoadId(),
                new CommentShowDTO(
                    $comment->getId(),
                    $comment->getComment(),
                    $comment->getLoadId(),
                )
            );
        }

        return $collection;
    }

    public function saveComment(CommentDTO $commentDto): Comment
    {
        $comment = (new Comment());
        if ($commentDto->id) {
            $comment = $this->commentRepository->find($commentDto->id);
        }

        $comment
            ->setComment($commentDto->comment)
            ->setUserId($commentDto->userId)
            ->setLoadId($commentDto->entityId)
            ->setCreatedAt();

        $this->commentRepository->save($comment);

        return $comment;
    }

    public function deleteComment(int $id): void
    {
        $this->commentRepository->delete($id);
    }
}
