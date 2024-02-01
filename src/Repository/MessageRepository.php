<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }
    public function findByBrandId(int $chatId)
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
