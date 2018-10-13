<?php
namespace App\Entity;

class PlanificationContext
{
	protected $previousPlanificationPeriod;
	protected $nextPlanificationPeriod;
    protected $bookingsCount;
    protected $periodBookingsCount;
    
    public function setPreviousPlanificationPeriod($previousPlanificationPeriod)
    {
    $this->previousPlanificationPeriod = $previousPlanificationPeriod;
    return $this;
    }

    public function getPreviousPlanificationPeriod()
    {
    return $this->previousPlanificationPeriod;
    }

    public function isFirstPeriod()
    {
    return ($this->previousPlanificationPeriod === null);
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

    public function isLastPeriod()
    {
    return ($this->nextPlanificationPeriod === null);
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

    public function setPeriodBookingsCount($periodBookingsCount)
    {
    $this->periodBookingsCount = $periodBookingsCount;
    return $this;
    }

    public function getPeriodBookingsCount()
    {
    return $this->periodBookingsCount;
    }

    function __construct($em, \App\Entity\File $file, \App\Entity\Planification $planification, \App\Entity\PlanificationPeriod $planificationPeriod)
    {
    $ppRepository = $em->getRepository(PlanificationPeriod::Class);
    $this->setPreviousPlanificationPeriod($ppRepository->getPreviousPlanificationPeriod($planification, $planificationPeriod->getID()));
    $this->setNextPlanificationPeriod($ppRepository->getNextPlanificationPeriod($planification, $planificationPeriod->getID()));

	$bRepository = $em->getRepository(Booking::Class);
    $this->setBookingsCount($bRepository->getPlanificationBookingsCount($file, $planification));
    $this->setPeriodBookingsCount($bRepository->getPlanificationPeriodBookingsCount($file, $planification, $planificationPeriod));
    return $this;
    }

	// Afficher le bouton de création de période
    public function displayCreatePeriod()
    {
    return ($this->isLastPeriod() and $this->getPeriodBookingsCount() > 0);
    }

	// Afficher le bouton de suppression de la période affichée (si c'est la dernière et qu'aucune réservation n'est saisie)
    public function displayDeletePeriod()
    {
    return ($this->isLastPeriod() and $this->getPeriodBookingsCount() <= 0);
    }
}
