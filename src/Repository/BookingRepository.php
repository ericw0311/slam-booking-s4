<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

use Doctrine\ORM\Query\Expr;

use App\Entity\Booking;
use App\Api\DateFormatFunctionDQL;

/**
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Booking::class);
    }

	// Affichage des réservations dans la grille horaire journalière
	public function getTimetableBookings(\App\Entity\File $file, \Datetime $date, \App\Entity\Planification $planification, \App\Entity\PlanificationPeriod $planificationPeriod)
	{
	$qb = $this->createQueryBuilder('b');
    $qb->select('b.id bookingID');
	$qb->addSelect('bl.ddate date');
    $qb->addSelect('p.id planificationID');
    $qb->addSelect('pp.id planificationPeriodID');
	$qb->addSelect('pl.id planificationLineID');
	$qb->addSelect('r.id resourceID');
	$qb->addSelect('t.id timetableID');
	$qb->addSelect('tl.id timetableLineID');
	$qb->where('b.file = :file')->setParameter('file', $file);
	$qb->andWhere("DATE_FORMAT(bl.ddate,'%Y%m%d') = :date")->setParameter('date', $date->format('Ymd'));
	$qb->andWhere('bl.planification = :planification')->setParameter('planification', $planification);
	$qb->andWhere('bl.planificationPeriod = :planificationPeriod')->setParameter('planificationPeriod', $planificationPeriod);
	$qb->innerJoin('b.bookingLines', 'bl');
	$qb->innerJoin('bl.planification', 'p');
	$qb->innerJoin('bl.planificationPeriod', 'pp');
	$qb->innerJoin('bl.planificationLine', 'pl');
	$qb->innerJoin('bl.resource', 'r');
	$qb->innerJoin('bl.timetable', 't');
	$qb->innerJoin('bl.timetableLine', 'tl');
	$qb->orderBy('bl.ddate', 'ASC');
	$qb->addOrderBy('p.id', 'ASC');
	$qb->addOrderBy('pp.id', 'ASC');
	$qb->addOrderBy('pl.id', 'ASC');
	$qb->addOrderBy('r.id', 'ASC');
	$qb->addOrderBy('t.id', 'ASC');
	$qb->addOrderBy('tl.id', 'ASC');
	$query = $qb->getQuery();
	$results = $query->getResult();
	return $results;
	}
	
	// Toutes les réservations d'un dossier
	public function getAllBookingsCount(\App\Entity\File $file)
    {
    $qb = $this->createQueryBuilder('b');
	$qb->select($qb->expr()->count('b'));
    $qb->where('b.file = :file')->setParameter('file', $file);
    $query = $qb->getQuery();
    $singleScalar = $query->getSingleScalarResult();
    return $singleScalar;
    }
    
	public function getAllBookings(\App\Entity\File $file, $firstRecordIndex, $maxRecord)
    {
	$qb = $this->createQueryBuilder('b');
	$this->getListSelect($qb);
	$qb->where('b.file = :file')->setParameter('file', $file);
	$this->getListJoin_1($qb);
	$this->getListSort($qb);
	$qb->setFirstResult($firstRecordIndex);
	$qb->setMaxResults($maxRecord);
	$query = $qb->getQuery();
	$results = $query->getResult();
	return $results;
    }
    
	// Les réservations d'un dossier au delà d'une date
	public function getFromDatetimeBookingsCount(\App\Entity\File $file, \Datetime $dateTime)
	{
	$qb = $this->createQueryBuilder('b');
	$qb->select($qb->expr()->count('b'));
	$qb->where('b.file = :file')->setParameter('file', $file);
	$qb->andWhere("DATE_FORMAT(b.endDate,'%Y%m%d%H%i') >= :dateTime")->setParameter('dateTime', $dateTime->format('YmdHi'));
	$query = $qb->getQuery();
	$singleScalar = $query->getSingleScalarResult();
	return $singleScalar;
	}
	
	public function getFromDatetimeBookings(\App\Entity\File $file, \Datetime $dateTime, $firstRecordIndex, $maxRecord)
    {
	$qb = $this->createQueryBuilder('b');
	$this->getListSelect($qb);
	$qb->where('b.file = :file')->setParameter('file', $file);
	$qb->andWhere("DATE_FORMAT(b.endDate,'%Y%m%d%H%i') >= :dateTime")->setParameter('dateTime', $dateTime->format('YmdHi'));
	$this->getListJoin_1($qb);
	$this->getListSort($qb);
	$qb->setFirstResult($firstRecordIndex);
	$qb->setMaxResults($maxRecord);
	$query = $qb->getQuery();
	$results = $query->getResult();
	return $results;
    }
	
	// Les réservations d'un dossier et d'un utilisateur
	public function getUserFileBookingsCount(\App\Entity\File $file, \App\Entity\UserFile $userFile)
	{
	$qb = $this->createQueryBuilder('b');
	$qb->select($qb->expr()->count('b'));
	$qb->where('b.file = :file')->setParameter('file', $file);
	$this->getUserFileJoin($qb, $userFile);
	$query = $qb->getQuery();
	$singleScalar = $query->getSingleScalarResult();
	return $singleScalar;
	}
	
	public function getUserFileBookings(\App\Entity\File $file, \App\Entity\UserFile $userFile, $firstRecordIndex, $maxRecord)
	{
	$qb = $this->createQueryBuilder('b');
	$this->getListSelect($qb);
	$qb->where('b.file = :file')->setParameter('file', $file);
	$this->getListJoin_2($qb, $userFile);
	$this->getListSort($qb);
    $qb->setFirstResult($firstRecordIndex);
    $qb->setMaxResults($maxRecord);
    $query = $qb->getQuery();
    $results = $query->getResult();
    return $results;
	}
	
	// Les réservations d'un dossier et d'un utilisateur au delà d'une date
	public function getUserFileFromDatetimeBookingsCount(\App\Entity\File $file, \App\Entity\UserFile $userFile, \Datetime $dateTime)
	{
	$qb = $this->createQueryBuilder('b');
	$qb->select($qb->expr()->count('b'));
	$qb->where('b.file = :file')->setParameter('file', $file);
	$qb->andWhere("DATE_FORMAT(b.endDate,'%Y%m%d%H%i') >= :dateTime")->setParameter('dateTime', $dateTime->format('YmdHi'));
	$this->getUserFileJoin($qb, $userFile);
	$query = $qb->getQuery();
	$singleScalar = $query->getSingleScalarResult();
	return $singleScalar;
	}
	
	public function getUserFileFromDatetimeBookings(\App\Entity\File $file, \App\Entity\UserFile $userFile, \Datetime $dateTime, $firstRecordIndex, $maxRecord)
	{
	$qb = $this->createQueryBuilder('b');
	$this->getListSelect($qb);
	$qb->where('b.file = :file')->setParameter('file', $file);
	$qb->andWhere("DATE_FORMAT(b.endDate,'%Y%m%d%H%i') >= :dateTime")->setParameter('dateTime', $dateTime->format('YmdHi'));
	$this->getListJoin_2($qb, $userFile);
	$this->getListSort($qb);
    $qb->setFirstResult($firstRecordIndex);
    $qb->setMaxResults($maxRecord);
    $query = $qb->getQuery();
    $results = $query->getResult();
    return $results;
	}

	// Les réservations d'un dossier et d'une ressource
	public function getResourceBookingsCount(\App\Entity\File $file, \App\Entity\Resource $resource)
	{
	$qb = $this->createQueryBuilder('b');
	$qb->select($qb->expr()->count('b'));
	$qb->where('b.file = :file')->setParameter('file', $file);
	$qb->andWhere('b.resource = :resource')->setParameter('resource', $resource);
	$query = $qb->getQuery();
	$singleScalar = $query->getSingleScalarResult();
	return $singleScalar;
	}
	
	public function getResourceBookings(\App\Entity\File $file, \App\Entity\Resource $resource, $firstRecordIndex, $maxRecord)
	{
	$qb = $this->createQueryBuilder('b');
	$this->getListSelect($qb);
	$qb->where('b.file = :file')->setParameter('file', $file);
	$qb->andWhere('b.resource = :resource')->setParameter('resource', $resource);
	$this->getListJoin_1($qb);
	$this->getListSort($qb);
    $qb->setFirstResult($firstRecordIndex);
    $qb->setMaxResults($maxRecord);
    $query = $qb->getQuery();
    $results = $query->getResult();
    return $results;
	}

	// Listes de réservations: partie Select
	public function getListSelect($qb)
	{
	$qb->select('b.id');
	$qb->addSelect('b.beginningDate');
	$qb->addSelect('b.endDate');
	$qb->addSelect('p.id planificationID');
	$qb->addSelect('r.name resource_name');
	$qb->addSelect('r.code resource_code');
	$qb->addSelect('r.type resource_type');
	$qb->addSelect('r.internal resource_internal');
	$qb->addSelect('uf.firstName user_first_name');
	$qb->addSelect('uf.lastName user_last_name');
	$qb->addSelect('uf.administrator administrator');
	}
	
	// Listes de réservations: partie Jointure avec sélection de l'utilisateur d'ordre 1
	public function getListJoin_1($qb)
	{
	$qb->innerJoin('b.planification', 'p');
	$qb->innerJoin('b.resource', 'r');
	$qb->innerJoin('b.bookingUsers', 'bu', Expr\Join::WITH, $qb->expr()->eq('bu.oorder', ':order'))->setParameter('order', 1);
	$qb->innerJoin('bu.userFile', 'uf');
	}
	
	// Jointure pour sélection de l'utilisateur transmis
	public function getUserFileJoin($qb, \App\Entity\UserFile $userFile)
	{
	$qb->innerJoin('b.bookingUsers', 'bu', Expr\Join::WITH, $qb->expr()->eq('bu.userFile', ':userFile'))->setParameter('userFile', $userFile);
	}
	
	// Listes de réservations: partie Jointure avec sélection de l'utilisateur transmis
	public function getListJoin_2($qb, \App\Entity\UserFile $userFile)
	{
	$qb->innerJoin('b.planification', 'p');
	$qb->innerJoin('b.resource', 'r');
	$this->getUserFileJoin($qb, $userFile);
	$qb->innerJoin('bu.userFile', 'uf');
	}
	
	// Listes de réservations: partie Tri
	public function getListSort($qb)
	{
	$qb->orderBy('b.beginningDate', 'ASC');
	}

	// PLUS UTILISE: Affichage des réservations dans le calendrier
	public function getCalendarBookings(\App\Entity\File $file, \App\Entity\Planification $planification)
    {
    $qb = $this->createQueryBuilder('b');
    $qb->where('b.file = :file')->setParameter('file', $file);
	$qb->andWhere('b.planification = :planification')->setParameter('planification', $planification);
	$qb->orderBy('b.resource', 'ASC');
	$qb->addOrderBy('b.beginningDate', 'ASC');
    $query = $qb->getQuery();
    $results = $query->getResult();
    return $results;
    }
}
