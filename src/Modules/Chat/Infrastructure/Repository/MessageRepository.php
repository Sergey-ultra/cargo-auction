<?php

declare(strict_types=1);

namespace  App\Modules\Chat\Infrastructure\Repository;

use App\Modules\Chat\Domain\Entity\Message;
use App\Modules\Chat\Domain\Repository\MessageRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MessageRepository extends ServiceEntityRepository implements MessageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }
    public function findByChatId(int $chatId)
    {
        return $this->createQueryBuilder('m')
            ->where('IDENTITY(m.chat) = :chatId')
            ->setParameter('chatId', $chatId)
            ->getQuery()
            ->getResult();
    }

    public function save(Message $message): void
    {
        $this->_em->persist($message);
        $this->_em->flush();
    }
}
