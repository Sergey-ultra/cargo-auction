<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\OrdersFilter;
use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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

    private CityRepository $cityRepository;
    public function __construct(ManagerRegistry $registry, CityRepository $cityRepository)
    {
        parent::__construct($registry, Order::class);

        $this->cityRepository = $cityRepository;
    }

    public function getPaginator(int $page, ?OrdersFilter $filter): Paginator
    {
        $query = $this->createQueryBuilder('c');

        if (null !== $filter) {
            $filter = array_filter($filter->toArray());
            $filterKeys = array_keys($filter);

            foreach ($filter as $filterKey => $filterParam) {
                if ($filterKey === 'toAddress' && in_array('toRadius', $filterKeys, true)) {
                   $this->addDistanceCondition($query, $filter, 'toAddressId', 'toAddress', $filter['toRadius']);

                } else if ($filterKey === 'fromAddress' && in_array('fromRadius', $filterKeys, true)) {
                    $this->addDistanceCondition($query, $filter, 'fromAddressId', 'fromAddress', $filter['fromRadius']);

                } else if (in_array($filterKey, ['weight', 'volume'], true)) {
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

            //dd($query->getDQL());
        }

        return new Paginator($query);
    }

    private function addDistanceCondition(QueryBuilder $query, array $filter, string $cityIdKey, string $address, int $radius): void
    {
        $filterKeys = array_keys($filter);

        if (in_array($cityIdKey, $filterKeys, true)) {
            $city = $this->cityRepository->find($filter[$cityIdKey]);
        } else {
            $city = $this->cityRepository->findOneBy(['name' => $filter[$address]]);
        }

        if (null !== $city) {
            //              SELECT *, ACOS(SIN(latitude) * SIN(Lat)) + COS(latitude) * COS(Lat) * COS(longitude) - (Long)) ) * 6380 AS distance
//              FROM Table_tab
//              WHERE ACOS( SIN(latitude) * SIN(Lat) + COS(latitude) * COS(Lat) * COS(longitude) - Long )) * 6380 < 10
            $query->andWhere(
                $query->expr()->eq(
                    "ST_DWithin(ST_SetSRID(ST_MakePoint(:longitude, :latitude), 4326), c.toPoint, :distance)",
                    $query->expr()->literal(true))
            )
                ->setParameter('longitude', $city->getLon())
                ->setParameter('latitude', $city->getLat())
                ->setParameter('distance', $radius * 1000);

        }
    }

    public function save(Order $order): void
    {
        $this->_em->persist($order);
        $this->_em->flush();
    }
}
