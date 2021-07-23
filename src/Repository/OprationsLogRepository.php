<?php

namespace App\Repository;

use App\Entity\OperationsLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OperationsLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method OperationsLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method OperationsLog[]    findAll()
 * @method OperationsLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OprationsLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OperationsLog::class);
    }

    // /**
    //  * @return OprationsLog[] Returns an array of OprationsLog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OprationsLog
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
