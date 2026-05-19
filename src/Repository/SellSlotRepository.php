<?php

namespace App\Repository;

use App\Entity\SellSlot;
use App\Entity\User;
use App\Enum\EnumState;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SellSlot>
 */
class SellSlotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SellSlot::class);
    }

    public function findActiveByUser(User $user): QueryBuilder
    {
        return $this->createQueryBuilder('ss')
            ->join('ss.shop', 'shop')
            ->where('shop.producer = :user')
            ->andWhere('ss.state = :state')
            ->setParameter('user', $user)
            ->setParameter('state', EnumState::Active);
    }

    //    /**
    //     * @return SellSlot[] Returns an array of SellSlot objects
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

    //    public function findOneBySomeField($value): ?SellSlot
    //    {
    //        return $this->createQueryBuilder('s')
    //            ->andWhere('s.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
