<?php
namespace App\Entity;

use App\Api\AdministrationApi;
use App\Api\PlanningApi;

class PlanningContext
{
	private $numberLines; // Nombre de lignes pouvant etre affichees sur une page
	private $numberColumns; // Nombre de colonnes pouvant etre affichees sur une page
	private $days;

	private $before; // Appliquer une restriction avant la date du jour
	private $after; // Appliquer une restriction après la date du jour
	private $firstBookingDate; // Première date de réservation autorisée si indicateur before est vrai
	private $lastBookingDate; // Dernière date de réservation autorisée si indicateur after est vrai

	function __construct($em, \App\Entity\User $user, \App\Entity\File $file, PlanificationPeriod $planificationPeriod, \Datetime $date)
	{
	$upRepository = $em->getRepository(UserParameter::class);
	$userParameter = $upRepository->findOneBy(array('user' => $user, 'parameterGroup' => 'planning.number.lines.columns', 'parameter' => 'number.lines'));
	if ($userParameter != null) { $this->numberLines = $userParameter->getIntegerValue(); } else { $this->numberLines =  constant(Constants::class.'::PLANNING_DEFAULT_NUMBER_LINES'); }
	$userParameter = $upRepository->findOneBy(array('user' => $user, 'parameterGroup' => 'planning.number.lines.columns', 'parameter' => 'number.columns'));
	if ($userParameter != null) { $this->numberColumns = $userParameter->getIntegerValue(); } else { $this->numberColumns = constant(Constants::class.'::PLANNING_DEFAULT_NUMBER_COLUMNS'); }

	$this->before = AdministrationApi::getFileBookingPeriodBefore($em, $file);
	$beforeType = AdministrationApi::getFileBookingPeriodBeforeType($em, $file);
	$beforeNumber = AdministrationApi::getFileBookingPeriodBeforeNumber($em, $file);

	$this->after = AdministrationApi::getFileBookingPeriodAfter($em, $file);
	$afterType = AdministrationApi::getFileBookingPeriodAfterType($em, $file);
	$afterNumber = AdministrationApi::getFileBookingPeriodAfterNumber($em, $file);

	if ($this->before) {
		$this->firstBookingDate = PlanningApi::getFirstBookingDate($beforeType, $beforeNumber);
	} else {
		$this->firstBookingDate = new \DateTime();
	}
	if ($this->after) {
		$this->lastBookingDate = PlanningApi::getLastBookingDate($afterType, $afterNumber);
	} else {
		$this->lastBookingDate = new \DateTime();
	}

	$this->days = array();

	for($j = 1; $j <= $this->getNumberColumns(); $j++) {
		for($i = 1; $i <= $this->getNumberLines(); $i++) {
			$dayKey = $i.'-'.$j;
			$dayNum = ($j-1)*$this->getNumberLines() + ($i-1);
			$dayDate = clone $date;
			if ($dayNum > 0) {
				$dayDate->add(new \DateInterval('P'.$dayNum.'D'));
			}
			$beforeSign = '+';
			if ($this->before) {
				$interval = $this->firstBookingDate->diff($dayDate);
				$beforeSign = $interval->format('%R');
			}
			$afterSign = '+';
			if ($this->after) {
				$interval = $dayDate->diff($this->lastBookingDate);
				$afterSign = $interval->format('%R');
			}
			$periodType = 'O'; // OK pour réservation
			if ($beforeSign == '-') { $periodType = 'B'; } // Avant période de réservation
			if ($afterSign == '-') { $periodType = 'A'; } // Après période de réservation

			$this->days[$dayKey] = new Day($em, $planificationPeriod, $dayDate, $periodType);
		}
	}

	return $this;
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

	// Indique si on affiche la date pour chaque jour
	public function displayDate()
	{
	return ($this->getNumberDays() > 1);
	}

	// Dernière date de la période
	public function getLastDate()
	{
	return ($this->getDay($this->getNumberLines().'-'.$this->getNumberColumns())->getDate());
	}

	public function getBefore()
	{
	return $this->before;
	}

	public function getAfter()
	{
	return $this->after;
	}

    public function getFirstBookingDate()
    {
    return $this->firstBookingDate;
    }

    public function getLastBookingDate()
    {
    return $this->lastBookingDate;
    }
}
