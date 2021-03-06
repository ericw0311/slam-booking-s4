<?php

namespace App\Repository;

use App\Entity\BookingLine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BookingLine|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookingLine|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookingLine[]    findAll()
 * @method BookingLine[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingLineRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BookingLine::class);
    }

	public function getBookingLines(\App\Entity\Booking $booking)
	{
	$qb = $this->createQueryBuilder('bl');
	$qb->select('bl.ddate date');
	$qb->addSelect('p.id planificationID');
	$qb->addSelect('pp.id planificationPeriodID');
	$qb->addSelect('pl.id planificationLineID');
	$qb->addSelect('t.id timetableID');
	$qb->addSelect('tl.id timetableLineID');
	$qb->where('bl.booking = :booking')->setParameter('booking', $booking);
	$qb->innerJoin('bl.planification', 'p');
	$qb->innerJoin('bl.planificationPeriod', 'pp');
	$qb->innerJoin('bl.planificationLine', 'pl');
	$qb->innerJoin('bl.timetable', 't');
	$qb->innerJoin('bl.timetableLine', 'tl');
	$qb->orderBy('bl.ddate', 'ASC');
	$qb->addOrderBy('p.id', 'ASC');
	$qb->addOrderBy('pp.id', 'ASC');
	$qb->addOrderBy('pl.id', 'ASC');
	$qb->addOrderBy('t.id', 'ASC');
	$qb->addOrderBy('tl.id', 'ASC');
	$query = $qb->getQuery();
	$results = $query->getResult();
	return $results;
	}

	// Date maximum parmi les réservations d'une planification
	public function getLastPlanificationBookingLine(\App\Entity\File $file, \App\Entity\Planification $planification)
	{
	$qb = $this->createQueryBuilder('bl');
	$qb->where('bl.planification = :p')->setParameter('p', $planification);
	$qb->innerJoin('bl.booking', 'b', Expr\Join::WITH, $qb->expr()->eq('b.file', '?1'));
    $qb->setParameter(1, $file);
	$qb->orderBy('bl.ddate', 'DESC');
	$qb->setMaxResults(1);

	$query = $qb->getQuery();
	$results = $query->getOneOrNullResult();
	return $results;
	}

	// Première ligne d'une réservation
	public function getFirstBookingLine(\App\Entity\Booking $booking)
	{
	$qb = $this->createQueryBuilder('bl');
	$qb->where('bl.booking = :b')->setParameter('b', $booking);
	$qb->orderBy('bl.id', 'ASC');
	$qb->setMaxResults(1);

	$query = $qb->getQuery();
	$results = $query->getOneOrNullResult();
	return $results;
	}

	// Dernière ligne d'une réservation
	public function getLastBookingLine(\App\Entity\Booking $booking)
	{
	$qb = $this->createQueryBuilder('bl');
	$qb->where('bl.booking = :b')->setParameter('b', $booking);
	$qb->orderBy('bl.id', 'DESC');
	$qb->setMaxResults(1);

	$query = $qb->getQuery();
	$results = $query->getOneOrNullResult();
	return $results;
	}
}
