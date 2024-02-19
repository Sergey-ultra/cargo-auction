<?php

declare(strict_types=1);

namespace App\Modules\Load\Infrastructure\Repository;

use App\Modules\City\Domain\Entity\City;
use App\Modules\City\Infrastructure\Repository\CityRepository;
use App\Modules\Load\Domain\Entity\Load;
use App\Modules\Load\Infrastructure\DTO\LoadFilterDTO;
use App\Modules\Load\Infrastructure\DTO\LoadListDTO;
use App\Modules\User\Domain\Entity\User;
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
    public const LOAD_CREATED_AT = 'created_at';
    public const LOAD_UPDATED_AT = 'updated_at';
    public const LOAD_DOWNLOADING_DATE = 'downloading_date';
    public const LOAD_CARGO_TYPE = 'cargo_type';

    public const LOAD_OPTIONS = [
        self::LOAD_CREATED_AT => 'времени добавления',
        self::LOAD_UPDATED_AT => 'времени обновления',
        self::LOAD_DOWNLOADING_DATE => 'дате загрузки',
        self::LOAD_CARGO_TYPE => 'типу груза',
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Load::class);
    }

    public function getList(
        ?LoadFilterDTO $filter,
        int $page = 1,
        int $perPage = self::PAGINATOR_PER_PAGE,
        string $orderOption = self::LOAD_CREATED_AT,
        ?User $byUser = null
    ): LoadListDTO
    {
        $queryBuilder = $this->createQueryBuilder('c')
        ->select('c, ST_Distance(c.fromPoint, c.toPoint)/1000 distance');

        if (null !== $filter) {
            if (isset($filter->fromLongitude) && isset($filter->fromLangitude) && isset($filter->fromRadius)) {
                $this->addDistanceCondition(
                    $queryBuilder,
                    $filter->fromLongitude,
                    $filter->fromLangitude,
                    (int)$filter->fromRadius
                );
            }

            if (isset($filter->toLongitude) && isset($filter->toLatitude) && isset($filter->toRadius)) {
                $this->addDistanceCondition(
                    $queryBuilder,
                    $filter->toLongitude,
                    $filter->toLatitude,
                    (int)$filter->toRadius
                );
            }

            if ($filter->weightMin) {
                $queryBuilder
                    ->andWhere("c.weight >= :weightMin")
                    ->setParameter('weightMin', $filter->weightMin);
            }

            if ($filter->volumeMin) {
                $queryBuilder
                    ->andWhere("c.volume >= :volumeMin")
                    ->setParameter('volumeMin', $filter->volumeMin);
            }

            if ($filter->weightMax) {
                $queryBuilder
                    ->andWhere("c.weight <= :weightMax")
                    ->setParameter('weightMax', $filter->weightMax);
            }

            if ($filter->volumeMax) {
                $queryBuilder
                    ->andWhere("c.volume <= :volumeMax")
                    ->setParameter('volumeMax', $filter->volumeMax);
            }
        }

        if (null !== $byUser) {
            $queryBuilder
                ->andWhere("c.user <= :user")
                ->setParameter('user', $byUser);
        }

        if ($orderOption === self::LOAD_CREATED_AT) {
            $queryBuilder->orderBy('c.createdAt', 'DESC');
        } else if ($orderOption === self::LOAD_UPDATED_AT) {
            $queryBuilder->orderBy('c.updatedAt', 'DESC');
        } else if ($orderOption === self::LOAD_DOWNLOADING_DATE) {
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

        return new LoadListDTO($list, $paginator->count());
    }



    private function addDistanceCondition(QueryBuilder $query, float $longitude, float $latitude, int $radius): void
    {
        $query
            ->andWhere(
                $query->expr()->eq(
                    "ST_DWithin(ST_SetSRID(ST_MakePoint(:longitude, :latitude), 4326), c.toPoint, :distance)",
                    $query->expr()->literal(true))
            )
            ->setParameter('longitude', $longitude)
            ->setParameter('latitude', $latitude)
            ->setParameter('distance', $radius * 1000);
    }

    public function save(Load $order): void
    {
        $this->_em->persist($order);
        $this->_em->flush();
    }
}
