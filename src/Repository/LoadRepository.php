<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\LoadFilter;
use App\Entity\Load;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Load>
 *
 * @method Load|null find($id, $lockMode = null, $lockVersion = null)
 * @method Load|null findOneBy(array $criteria, array $orderBy = null)
 * @method Load[]    findAll()
 * @method Load[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoadRepository extends ServiceEntityRepository
{

    public const PAGINATOR_PER_PAGE = 10;

    private CityRepository $cityRepository;
    public function __construct(ManagerRegistry $registry, CityRepository $cityRepository)
    {
        parent::__construct($registry, Load::class);

        $this->cityRepository = $cityRepository;
    }

    public function getPaginator(int $page, ?LoadFilter $filter, ?User $byUser = null): Paginator
    {
        $query = $this->createQueryBuilder('c');

        if (null !== $filter) {
            $filter = array_filter($filter->toArray());
            $filterKeys = array_keys($filter);

            foreach ($filter as $filterKey => $filterParam) {
                if ($filterKey === 'toAddress' && in_array('toRadius', $filterKeys, true)) {
                    $this->addDistanceCondition($query, $filter, 'toAddressId', 'toAddress', (int)$filter['toRadius']);

                } else if ($filterKey === 'fromAddress' && in_array('fromRadius', $filterKeys, true)) {
                    $this->addDistanceCondition($query, $filter, 'fromAddressId', 'fromAddress', (int)$filter['fromRadius']);

                } else if ($filterKey === 'weightMin') {
                    $query
                        ->andWhere("c.weight >= :$filterKey")
                        ->setParameter($filterKey, $filterParam);
                } else if ($filterKey === 'volumeMin') {
                    $query
                        ->andWhere("c.volume >= :$filterKey")
                        ->setParameter($filterKey, $filterParam);
                } else if ($filterKey ==='weightMax') {
                    $query
                        ->andWhere("c.weight <= :$filterKey")
                        ->setParameter($filterKey, $filterParam);
                } else if ($filterKey ==='volumeMax') {
                    $query
                        ->andWhere("c.volume <= :$filterKey")
                        ->setParameter($filterKey, $filterParam);
                }
            }
        }

        if (null !== $byUser) {
            $query
                ->andWhere("c.user <= :user")
                ->setParameter('user', $byUser);
        }

        $query->orderBy('c.createdAt', 'DESC');

        $query
            ->setFirstResult(($page - 1) * self::PAGINATOR_PER_PAGE)
            ->setMaxResults(self::PAGINATOR_PER_PAGE)
            ->getQuery();

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
        //dd($city, $cityIdKey, $filterKeys, $filter[$address]);

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

    public function save(Load $order): void
    {
        $this->_em->persist($order);
        $this->_em->flush();
    }
}
