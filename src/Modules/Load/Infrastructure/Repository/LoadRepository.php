<?php

declare(strict_types=1);

namespace App\Modules\Load\Infrastructure\Repository;

use App\Modules\Load\Domain\Entity\Load;
use App\Modules\Load\Domain\Repository\LoadRepositoryInterface;
use App\Modules\Load\Infrastructure\DTO\FilterDTO;
use App\Modules\Load\Infrastructure\DTO\LoadListDTO;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Load>
 *
 * @method Load|null find($id, $lockMode = null, $lockVersion = null)
 * @method Load|null findOneBy(array $criteria, array $orderBy = null)
 * @method Load[]    findAll()
 * @method Load[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LoadRepository extends ServiceEntityRepository implements LoadRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Load::class);
    }

    public function getById(int $id): ?Load
    {
        $item = $this->createQueryBuilder('c')
            ->select('c, ST_Distance(c.fromPoint, c.toPoint)/1000 distance')
            ->where("c.id = :id")
            ->setParameter("id", $id)
            ->getQuery()
            ->getSingleResult();

        if (!$item) {
            return null;
        }

        ($item[0])->setDistance((int)$item['distance']);
        return $item[0];
    }

    public function getList(
        ?FilterDTO     $filter,
        int            $page = 1,
        int            $perPage = self::PAGINATOR_PER_PAGE,
        string         $orderOption = self::CREATED_AT,
        ?UserInterface $byUser = null
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

        if ($orderOption === self::CREATED_AT) {
            $queryBuilder->orderBy('c.createdAt', 'DESC');
        } else if ($orderOption === self::UPDATED_AT) {
            $queryBuilder->orderBy('c.updatedAt', 'DESC');
        } else if ($orderOption === self::DOWNLOADING_DATE) {
            $queryBuilder->orderBy('c.downloadingDate', 'DESC');
        } else if ($orderOption === self::CARGO_TYPE) {
            $queryBuilder->orderBy('c.cargoType', 'DESC');
        }

        $query = $queryBuilder
            ->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage)
            ->getQuery();

        $paginator = new Paginator($query);

        $list = [];
        /** @var array{0: Load, distance: string} $item */
        foreach($paginator->getIterator() as $item) {
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

    public function save(Load $load): void
    {
        $this->_em->persist($load);
        $this->_em->flush();
    }
}
