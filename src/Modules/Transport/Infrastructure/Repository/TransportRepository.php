<?php

declare(strict_types=1);

namespace App\Modules\Transport\Infrastructure\Repository;

use App\Modules\Transport\Domain\Entity\Transport;
use App\Modules\Transport\Domain\Repository\TransportRepositoryInterface;
use App\Modules\Transport\Infrastructure\DTO\FilterDTO;
use App\Modules\Transport\Infrastructure\DTO\ListDTO;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Transport>
 *
 * @method Transport|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transport|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transport[]    findAll()
 * @method Transport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransportRepository extends ServiceEntityRepository implements TransportRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transport::class);
    }

    public function save(Transport $transport): void
    {
        $this->_em->persist($transport);
        $this->_em->flush();
    }

    public function getList(
        ?FilterDTO $filter,
        int $page = 1,
        int $perPage = self::PAGINATOR_PER_PAGE,
        string $orderOption = self::CREATED_AT,
        ?UserInterface $byUser = null
    ): ListDTO
    {
        $queryBuilder = $this->createQueryBuilder('c');

        if (null !== $filter) {

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

        return new ListDTO($paginator->getIterator()->getArrayCopy(), $paginator->count());
    }
}
