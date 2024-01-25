<?php

declare(strict_types=1);

namespace App\Repository;

use App\DTO\LoadFilter;
use App\DTO\LoadList;
use App\Entity\Load;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\AbstractQuery;
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
    public const ORDER_CREATED_AT = 'created_at';
    public const ORDER_UPDATED_AT = 'updated_at';
    public const ORDER_DOWNLOADING_DATE = 'downloading_date';
    public const ORDER_CARGO_TYPE = 'cargo_type';

    public const ORDER_OPTIONS = [
        self::ORDER_CREATED_AT => 'времени добавления',
        self::ORDER_UPDATED_AT => 'времени обновления',
        self::ORDER_DOWNLOADING_DATE => 'дате загрузки',
        self::ORDER_CARGO_TYPE => 'типу груза',
    ];

    private CityRepository $cityRepository;
    public function __construct(ManagerRegistry $registry, CityRepository $cityRepository)
    {
        parent::__construct($registry, Load::class);

        $this->cityRepository = $cityRepository;
    }

    public function getList(
        ?LoadFilter $filter,
        int $page = 1,
        int $perPage = self::PAGINATOR_PER_PAGE,
        string $orderOption = self::ORDER_CREATED_AT,
        ?User $byUser = null
    ): LoadList
    {
        $queryBuilder = $this->createQueryBuilder('c')
        ->select('c, ST_Distance(c.fromPoint, c.toPoint)/1000 distance');

        if (null !== $filter) {
            $filter = array_filter($filter->toArray());
            $filterKeys = array_keys($filter);

            foreach ($filter as $filterKey => $filterParam) {
                if ($filterKey === 'toAddress' && in_array('toRadius', $filterKeys, true)) {
                    $this->addDistanceCondition($queryBuilder, $filter, 'toAddressId', 'toAddress', (int)$filter['toRadius']);

                } else if ($filterKey === 'fromAddress' && in_array('fromRadius', $filterKeys, true)) {
                    $this->addDistanceCondition($queryBuilder, $filter, 'fromAddressId', 'fromAddress', (int)$filter['fromRadius']);

                } else if ($filterKey === 'weightMin') {
                    $queryBuilder
                        ->andWhere("c.weight >= :$filterKey")
                        ->setParameter($filterKey, $filterParam);
                } else if ($filterKey === 'volumeMin') {
                    $queryBuilder
                        ->andWhere("c.volume >= :$filterKey")
                        ->setParameter($filterKey, $filterParam);
                } else if ($filterKey ==='weightMax') {
                    $queryBuilder
                        ->andWhere("c.weight <= :$filterKey")
                        ->setParameter($filterKey, $filterParam);
                } else if ($filterKey ==='volumeMax') {
                    $queryBuilder
                        ->andWhere("c.volume <= :$filterKey")
                        ->setParameter($filterKey, $filterParam);
                }
            }
        }

        if (null !== $byUser) {
            $queryBuilder
                ->andWhere("c.user <= :user")
                ->setParameter('user', $byUser);
        }

        if ($orderOption === self::ORDER_CREATED_AT) {
            $queryBuilder->orderBy('c.createdAt', 'DESC');
        } else if ($orderOption === self::ORDER_UPDATED_AT) {
            $queryBuilder->orderBy('c.updatedAt', 'DESC');
        } else if ($orderOption === self::ORDER_DOWNLOADING_DATE) {
            $queryBuilder->orderBy('c.downloadingDate', 'DESC');
        }

        $query = $queryBuilder
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage)
            ->getQuery();

        $paginator = new Paginator($query);

        $list = [];
       // dd($paginator->getIterator());
        foreach($paginator->getIterator() as $item) {
            //dd($item[0]->getUser());
            ($item[0])->setDistance((int)$item['distance']);
            $list[] = $item[0];
        }

        return new LoadList($list, $paginator->count());
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
