<?php
namespace App\Entity;

class PlanificationContext
{
	protected $previousPlanificationPeriod;
	protected $nextPlanificationPeriod;
    protected $bookingsCount;
    
    public function setPreviousPlanificationPeriod($previousPlanificationPeriod)
    {
    $this->previousPlanificationPeriod = $previousPlanificationPeriod;
    return $this;
    }

    public function getPreviousPlanificationPeriod()
    {
    return $this->previousPlanificationPeriod;
    }

    public function getPreviousPP()
    {
    return ($this->previousPlanificationPeriod !== null);
    }

    public function setNextPlanificationPeriod($nextPlanificationPeriod)
    {
    $this->nextPlanificationPeriod = $nextPlanificationPeriod;
    return $this;
    }

    public function getNextPlanificationPeriod()
    {
    return $this->nextPlanificationPeriod;
    }

    public function getNextPP()
    {
    return ($this->nextPlanificationPeriod !== null);
    }

    public function setBookingsCount($bookingsCount)
    {
    $this->bookingsCount = $bookingsCount;
    return $this;
    }

    public function getBookingsCount()
    {
    return $this->bookingsCount;
    }

    function __construct($em, \App\Entity\File $file, \App\Entity\Planification $planification, \App\Entity\PlanificationPeriod $planificationPeriod)
    {
    $ppRepository = $em->getRepository(PlanificationPeriod::Class);
    $this->setPreviousPlanificationPeriod($ppRepository->getPreviousPlanificationPeriod($planification, $planificationPeriod->getID()));
    $this->setNextPlanificationPeriod($ppRepository->getNextPlanificationPeriod($planification, $planificationPeriod->getID()));

	$bRepository = $em->getRepository(Booking::Class);
    $this->setBookingsCount($bRepository->getPlanificationPeriodBookingsCount($file, $planification, $planificationPeriod));
    return $this;
    }
}
