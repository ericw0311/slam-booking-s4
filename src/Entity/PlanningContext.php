<?php
namespace App\Entity;

use App\Api\AdministrationApi;
use App\Api\PlanningApi;
class PlanningContext
{
	private $planningType; // Type de planning: P = Planning, D = Dupplication de réservation
	private $numberLines; // Nombre de lignes pouvant etre affichees sur une page
	private $numberColumns; // Nombre de colonnes pouvant etre affichees sur une page
	private $days;
	private $before; // Appliquer une restriction avant la date du jour
	private $after; // Appliquer une restriction après la date du jour
	private $firstAllowedBookingDate; // Première date de réservation autorisée si indicateur before est vrai
	private $lastAllowedBookingDate; // Dernière date de réservation autorisée si indicateur after est vrai

	// newBookingBeginningDate et numberDays ne sont utilisés que pour le type Duplication
	function __construct($em, \App\Entity\User $user, \App\Entity\File $file, PlanificationPeriod $planificationPeriod, $planningType, \Datetime $beginningDate,
		\Datetime $newBookingBeginningDate, $numberDays)
	{
	$this->planningType = $planningType;

	if ($this->getPlanningType() == 'D') { // En duplication, le nombre de colonnes est 1 et le nombre de lignes, le nombre de jours sur de la réservation à dupliquer
		$this->numberColumns =  1;
		$this->numberLines =  $numberDays+1;
	} else {
		$this->numberColumns = PlanningApi::getNumberColumns($em, $user);
		$this->numberLines = PlanningApi::getNumberLines($em, $user);
	}

	$this->before = AdministrationApi::getFileBookingPeriodBefore($em, $file);
	$beforeType = AdministrationApi::getFileBookingPeriodBeforeType($em, $file);
	$beforeNumber = AdministrationApi::getFileBookingPeriodBeforeNumber($em, $file);
	$this->after = AdministrationApi::getFileBookingPeriodAfter($em, $file);
	$afterType = AdministrationApi::getFileBookingPeriodAfterType($em, $file);
	$afterNumber = AdministrationApi::getFileBookingPeriodAfterNumber($em, $file);
	if ($this->before) {
		$this->firstAllowedBookingDate = PlanningApi::getFirstDate($beforeType, $beforeNumber);
	} else {
		$this->firstAllowedBookingDate = new \DateTime();
	}
	if ($this->after) {
		$this->lastAllowedBookingDate = PlanningApi::getLastDate($afterType, $afterNumber);
	} else {
		$this->lastAllowedBookingDate = new \DateTime();
	}

	$this->days = array();
	$this->initDays($em, $planificationPeriod, 1, $beginningDate);

	if ($this->getPlanningType() == 'D') { // En duplication, on traite les jours de la réservation à créer.
		$this->initDays($em, $planificationPeriod, 2, $newBookingBeginningDate);
	}
	return $this;
	}

	// Planning: keyPrefix = 1
	// Duplication: keyPrefix = 1 pour la réservation origine et keyPrefix = 2 pour la réservation à créer
	public function initDays($em, PlanificationPeriod $planificationPeriod, $keyPrefix, \Datetime $beginningDate)
	{
	for($j = 1; $j <= $this->getNumberColumns(); $j++) {
		for($i = 1; $i <= $this->getNumberLines(); $i++) {
			$dayKey = $keyPrefix.'-'.$i.'-'.$j;
			$dayNum = ($j-1)*$this->getNumberLines() + ($i-1);
			$dayDate = clone $beginningDate;
			if ($dayNum > 0) {
				$dayDate->add(new \DateInterval('P'.$dayNum.'D'));
			}
			// En duplication, on ne contrôle la date que pour le premier jour de la réservation à créer
			$ctrlBefore = ($this->before and ($this->getPlanningType() != 'D' or ($keyPrefix == 2 and $i == 1 and $j == 1)));
			$ctrlAfter = ($this->after and ($this->getPlanningType() != 'D' or ($keyPrefix == 2 and $i == 1 and $j == 1)));

			$beforeSign = '+';
			if ($ctrlBefore) {
				$interval = $this->firstAllowedBookingDate->diff($dayDate);
				$beforeSign = $interval->format('%R');
			}
			$afterSign = '+';
			if ($ctrlAfter) {
				$interval = $dayDate->diff($this->lastAllowedBookingDate);
				$afterSign = $interval->format('%R');
			}
			$periodType = 'O'; // OK pour réservation
			if ($beforeSign == '-') { $periodType = 'B'; } // Avant période de réservation
			if ($afterSign == '-') { $periodType = 'A'; } // Après période de réservation
			$this->days[$dayKey] = new Day($em, $planificationPeriod, $dayDate, $periodType);
		}
	}
	}

	public function getPlanningType()
	{
	return $this->planningType;
	}

	public function getNumberLines()
	{
	return $this->numberLines;
	}

	public function getNumberColumns()
	{
	return $this->numberColumns;
	}

	// Jour
	public function getDay($dayKey)
	{
	return $this->days[$dayKey];
	}

	// Nombre de jours affichés
	public function getNumberDays()
	{
	return $this->getNumberLines() * $this->getNumberColumns();
	}

	// Indique si on affiche la date pour chaque jour: systématiquement en duplication et si plusieurs jours affichés sur le planning
	public function displayDate()
	{
	return (($this->getPlanningType() == 'D') or ($this->getNumberDays() > 1));
	}

	// Première date de la période
	public function getFirstDate($keyPrefix)
	{
	return ($this->getDay($keyPrefix.'-1-1')->getDate());
	}

	// Première date de dupplication
	public function getFirstDuplicateDate()
	{
	return ($this->getFirstDate(2));
	}

	// Dernière date de la période
	public function getLastDate($keyPrefix)
	{
	return ($this->getDay($keyPrefix.'-'.$this->getNumberLines().'-'.$this->getNumberColumns())->getDate());
	}

	public function getBefore()
	{
	return $this->before;
	}

	public function getAfter()
	{
	return $this->after;
	}

    public function getFirstAllowedBookingDate()
    {
    return $this->firstAllowedBookingDate;
    }

	public function getLastAllowedBookingDate()
    {
    return $this->lastAllowedBookingDate;
    }

	// Affichage des boutons dans le planning (pas pour la duplication)
	public function getDisplayButtons()
	{
	return ($this->getPlanningType() == 'P');
	}
}
