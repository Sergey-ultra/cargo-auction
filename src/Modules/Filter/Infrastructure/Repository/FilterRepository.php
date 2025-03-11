<?php

declare(strict_types=1);

namespace App\Modules\Filter\Infrastructure\Repository;

use App\Modules\Filter\Domain\Entity\Filter;
use App\Modules\Filter\Domain\Repository\FilterRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Filter>
 *
 * @method Filter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Filter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Filter[]    findAll()
 * @method Filter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilterRepository extends ServiceEntityRepository implements FilterRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Filter::class);
    }

    public function save(Filter $filter): void
    {
        $this->_em->persist($filter);
        $this->_em->flush();
    }
}
