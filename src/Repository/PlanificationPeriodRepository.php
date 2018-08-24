<?php

namespace App\Repository;

use App\Entity\PlanificationPeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PlanificationPeriod|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanificationPeriod|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanificationPeriod[]    findAll()
 * @method PlanificationPeriod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanificationPeriodRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PlanificationPeriod::class);
    }

//    /**
//     * @return PlanificationPeriod[] Returns an array of PlanificationPeriod objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PlanificationPeriod
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
