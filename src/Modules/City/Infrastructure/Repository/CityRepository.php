<?php

declare(strict_types=1);

namespace App\Modules\City\Infrastructure\Repository;

use App\Modules\City\Domain\Entity\City;
use App\Modules\City\Domain\Repository\CityRepositoryInterface;
use App\Utils\BulkInsert\BulkInsert;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<City>
 *
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository implements CityRepositoryInterface
{
    public const MAIN_CITIES_IDS = [
        155074,
        142168,
        155208,
        154835,
        97364,
        148459,
        109764,
        155179,
        154735,
        97584
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    /** @return City[] */
    public function getMainCities(): array
    {
        $list = $this->findBy(['id' => self::MAIN_CITIES_IDS]);

        $result = [];
        foreach (self::MAIN_CITIES_IDS as $id) {
            foreach ($list as $key => $city) {
                if ($city->getId() === $id) {
                    $result[] = $city;
                    unset($list[$key]);
                }
            }
        }

        return $result;
    }

    /** @return City[] */
    public function searchByName(string $name): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.name LIKE :name')
            ->setParameter('name', '%' .$name. '%')
            ->getQuery()
            ->setMaxResults(10)
            ->execute();
    }

    /**
     * @param string[] $cities
     * @return City[]
     */
    public function searchByNames(array $cities): array
    {
         $query = $this->createQueryBuilder('c');

         foreach($cities as $key => $city) {
             $query
                 ->orWhere("c.name LIKE :name$key")
                 ->setParameter("name$key", '%' . $city. '%');
         }

        return $query->getQuery()->execute();
    }

    public function truncate(): void
    {
        $cmd = $this->_em->getClassMetadata(City::class);
        $connection = $this->_em->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->beginTransaction();
        try {
            //$connection->executeQuery('SET FOREIGN_KEY_CHECKS=0');
            $q = $platform->getTruncateTableSql($cmd->getTableName());
            $connection->executeStatement($q);
            //$connection->executeQuery('SET FOREIGN_KEY_CHECKS=1');
            $connection->commit();
        }
        catch (\Exception $e) {
            echo $e->getMessage();
            $connection->rollback();
        }
    }

    public function bulkInserts(iterable $cities, int $batchSize = 1000): void
    {
        ini_set('memory_limit', '2G');
        foreach ($cities as $key => $cityInfo) {
            $city = (new City())
                ->setName($cityInfo[0])
                ->setOtherName($cityInfo[1])
                ->setRegionName($cityInfo[2])
                ->setDistrict($cityInfo[3])
                ->setLon((float)$cityInfo[4])
                ->setLat((float)$cityInfo[5])
                ->setPopulation((int)$cityInfo[6])
                ->setApprox((bool)$cityInfo[7]);

            $this->_em->persist($city);

            if (($key + 1) % $batchSize === 0) {
                echo 'UnitOfWork после создания категорий ' . $this->_em->getUnitOfWork()->size();
                echo 'Использование памяти ' . (memory_get_usage(true) / 1024 / 1024) .' ';
                //$this->_em->flush();
                $this->_em->clear();
                echo 'UnitOfWork после очистки ' . $this->_em->getUnitOfWork()->size();
                echo 'Использование памяти ' . (memory_get_usage(true) / 1024 / 1024) . \PHP_EOL;
            }
        }

       // $this->_em->flush();
        $this->_em->clear();
    }

    public function patchInsert(iterable $cities, int $batchSize = 1000): void
    {
        ini_set('memory_limit', '2G');
        $tableName = $this->_em->getClassMetadata(City::class)->getTableName();

        $bulkInsertService = new BulkInsert($this->_em->getConnection());

        $bulk = [];
        try {
            foreach ($cities as $key => $cityInfo) {
                $bulk[] = [
                    'id' => $key + 1,
                    'name' => $cityInfo[0],
                    'other_name' => $cityInfo[1],
                    'region_name' => $cityInfo[2],
                    'district' => $cityInfo[3],
                    'lon' => $cityInfo[4],
                    'lat' => $cityInfo[5],
                    'population' => (int)$cityInfo[6],
                    'approx' => (bool)$cityInfo[6],
                ];

                if (($key + 1) % $batchSize === 0) {
                    echo 'Использование памяти ' . (memory_get_usage(true) / 1024 / 1024) . \PHP_EOL;
                    $bulkInsertService->execute($tableName, $bulk);
                    $bulk = [];
                }
            }

            $bulkInsertService->execute($tableName, $bulk);
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
    }
}
