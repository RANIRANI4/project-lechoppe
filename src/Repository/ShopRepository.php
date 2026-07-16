<?php

namespace App\Repository;

use App\Entity\Shop;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Shop>
 */
class ShopRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shop::class);
    }

    public function findPaginated(int $page = 1, int $limit = 12): array
    {
        $offset = ($page - 1) * $limit;

        $shops = $this->createQueryBuilder('s')
            ->orderBy('s.id', 'ASC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        $total = (int) $this->createQueryBuilder('s')
            ->select('COUNT(s.id)')
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'shops' => $shops,
            'total' => $total,
        ];
    }

    public function findByDistance(float $lat, float $lng, float $radius = 20): array
    {
        $mappingEntity = new ResultSetMappingBuilder($this->getEntityManager());
        $mappingEntity->addRootEntityFromClassMetadata(Shop::class, 's');

        $sql = '
        SELECT ' . $mappingEntity->generateSelectClause(['s' => 's']) . ',
        ST_Distance_Sphere(
            POINT(s.longitude, s.latitude),
            POINT(:lng, :lat)
        ) / 1000 AS distance
        FROM shop s
        WHERE s.latitude IS NOT NULL
        HAVING distance < :radius
        ORDER BY distance ASC
    ';

        $query = $this->getEntityManager()->createNativeQuery($sql, $mappingEntity);
        $query->setParameter('lat', $lat);
        $query->setParameter('lng', $lng);
        $query->setParameter('radius', $radius);
        return $query->getResult();
    }

    //    /**
    //     * @return Shop[] Returns an array of Shop objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('s.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Shop
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
