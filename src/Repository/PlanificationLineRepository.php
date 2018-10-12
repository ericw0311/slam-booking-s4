<?php

namespace App\Repository;

use App\Entity\PlanificationLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PlanificationLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanificationLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanificationLine[]    findAll()
 * @method PlanificationLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanificationLineRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PlanificationLine::class);
    }

	// Recherche les lignes d'une periode de planification
	public function getLines($planificationPeriod)
    {
    $qb = $this->createQueryBuilder('pl');
    $qb->where('pl.planificationPeriod = :p')->setParameter('p', $planificationPeriod);
    $qb->orderBy('pl.oorder', 'ASC');
   
    $query = $qb->getQuery();
    $results = $query->getResult();
    return $results;
    }

	// Compte les periodes de planification d'une grille horaire
    public function getPlanificationPeriodsCount($timetable)
    {
    $qb = $this->createQueryBuilder('pl');
    $qb->select($queryBuilder->expr()->count('pl'));
    $qb->where('pl.timetable = :timetable')->setParameter('timetable', $timetable);

    $query = $qb->getQuery();
    $singleScalar = $query->getSingleScalarResult();
    return $singleScalar;
    }
}
