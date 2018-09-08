<?php

namespace App\Repository;

use App\Entity\BookingLabel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BookingLabel|null find($id, $lockMode = null, $lockVersion = null)
 * @method BookingLabel|null findOneBy(array $criteria, array $orderBy = null)
 * @method BookingLabel[]    findAll()
 * @method BookingLabel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingLabelRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BookingLabel::class);
    }

	public function getBookingLabels(\App\Entity\Booking $booking)
	{
	$qb = $this->createQueryBuilder('bl');
	$qb->select('l.id labelID');
	$qb->addSelect('bl.oorder');
	$qb->where('bl.booking = :booking')->setParameter('booking', $booking);
	$qb->innerJoin('bl.label', 'l');
	$qb->orderBy('bl.oorder', 'ASC');
	$query = $qb->getQuery();
	$results = $query->getResult();
	return $results;
	}
}
