<?php

declare(strict_types=1);

namespace App\Modules\Transport\Infrastructure\Api;

use App\ApiGateway\DTO\CommentDTO;
use App\ApiGateway\DTO\CommentShowDTO;
use App\Modules\Transport\Domain\Entity\Comment;
use App\Modules\Transport\Infrastructure\Repository\CommentRepository;
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
        $list = $this->commentRepository->findBy(['transportId' => $ids, 'userId' => $userId]);

        $collection = new ArrayCollection();

        foreach($list as $comment) {
            $collection->set(
                $comment->getTransportId(),
                new CommentShowDTO(
                    $comment->getId(),
                    $comment->getComment(),
                    $comment->getTransportId(),
                )
            );
        }

        return $collection;
    }

    public function saveComment(CommentDTO $commentDto): Comment
    {
        $comment = (new Comment())
            ->setComment($commentDto->comment)
            ->setUserId($commentDto->userId)
            ->setTransportId($commentDto->entityId)
            ->setCreatedAt();

        $this->commentRepository->save($comment);

        return $comment;
    }
}
