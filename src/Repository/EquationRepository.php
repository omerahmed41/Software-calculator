<?php

namespace App\Repository;

use App\Entity\EquationLog;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EquationLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method EquationLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method EquationLog[]    findAll()
 * @method EquationLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EquationLog::class);
    }
    public function getTop($value = 2)
    {
        return $this->createQueryBuilder('e')
            ->orderBy('e.id', 'Desc')
            ->setMaxResults($value)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Equation
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
