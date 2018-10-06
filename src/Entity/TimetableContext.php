<?php
namespace App\Entity;

class TimetableContext
{
    protected $planificationsCount;
    protected $bookingsCount;

    public function setPlanificationsCount($planificationsCount)
    {
    $this->planificationsCount = $planificationsCount;
    return $this;
    }

    public function setBookingsCount($bookingsCount)
    {
    $this->bookingsCount = $bookingsCount;
    return $this;
    }

    public function getPlanificationsCount()
    {
    return $this->planificationsCount;
    }

    public function getBookingsCount()
    {
    return $this->bookingsCount;
    }

    function __construct($em, \App\Entity\File $file, \App\Entity\Timetable $timetable)
    {
    $pRepository = $em->getRepository(Planification::Class);
    $this->setPlanificationsCount($pRepository->getTimetablePlanificationsCount($file, $timetable));

	$bRepository = $em->getRepository(Booking::Class);
    $this->setBookingsCount($bRepository->getTimetableBookingsCount($file, $timetable));

    return $this;
    }
}
