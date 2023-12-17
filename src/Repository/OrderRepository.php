<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\OrdersFilter;
use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 *
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public const PAGINATOR_PER_PAGE = 10;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function getPaginator(int $page, ?OrdersFilter $filter): Paginator
    {
        $query = $this->createQueryBuilder('c');

        if (null !== $filter) {
            foreach($filter->toArray() as $filterKey => $filterParam) {
                $query
                    ->andWhere("c.$filterKey = :$filterKey")
                    ->setParameter($filterKey, $filterParam);
            }
        }


        $query->orderBy('c.createdAt', 'DESC');


        $query
            ->setFirstResult(($page - 1) * self::PAGINATOR_PER_PAGE)
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->getQuery();

        return new Paginator($query);
    }

    public function save(Order $order): void
    {
        $this->_em->persist($order);
        $this->_em->flush();
    }
}
