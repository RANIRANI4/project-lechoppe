<?php

namespace App\Repository;

use App\Entity\CustomerOrder;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomerOrder>
 */
class CustomerOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerOrder::class);
    }

    /**
     * Retourne toutes les commandes contenant au moins un produit du producteur donné.
     *
     * @return CustomerOrder[]
     */
    public function findByProducer(User $producer): array
    {
        return $this->createQueryBuilder('o')
            ->innerJoin('o.customerOrderItems', 'oi')
            ->innerJoin('oi.product', 'p')
            ->where('p.producer = :producer')
            ->setParameter('producer', $producer)
            ->orderBy('o.createdAt', 'DESC')
            ->groupBy('o.id')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return CustomerOrder[] Returns an array of CustomerOrder objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CustomerOrder
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
