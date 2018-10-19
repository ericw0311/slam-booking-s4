<?php
namespace App\Entity;

class PlanningContext
{
	private $numberLines; // Nombre de lignes pouvant etre affichees sur une page
	private $numberColumns; // Nombre de colonnes pouvant etre affichees sur une page
	private $days;

	function __construct($em, \App\Entity\User $user, PlanificationPeriod $planificationPeriod, \Datetime $date)
	{
	$upRepository = $em->getRepository(UserParameter::class);
	$userParameter = $upRepository->findOneBy(array('user' => $user, 'parameterGroup' => 'planning.number.lines.columns', 'parameter' => 'number.lines'));
	if ($userParameter != null) { $this->numberLines = $userParameter->getIntegerValue(); } else { $this->numberLines =  constant(Constants::class.'::PLANNING_DEFAULT_NUMBER_LINES'); }
	$userParameter = $upRepository->findOneBy(array('user' => $user, 'parameterGroup' => 'planning.number.lines.columns', 'parameter' => 'number.columns'));
	if ($userParameter != null) { $this->numberColumns = $userParameter->getIntegerValue(); } else { $this->numberColumns = constant(Constants::class.'::PLANNING_DEFAULT_NUMBER_COLUMNS'); }

	$this->days = array();

	for($i = 1; $i <= $this->getNumberLines(); $i++) {
		for($j = 1; $j <= $this->getNumberColumns(); $j++) {
			$dayKey = $i.'-'.$j;
			$dayNum = ($i-1)*$this->getNumberColumns() + ($j-1);
			$dayDate = clone $date;
			if ($dayNum > 0) {
				$dayDate->add(new \DateInterval('P'.$dayNum.'D'));
			}
			$this->days[$dayKey] = new Day($em, $planificationPeriod, $dayDate);
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

	// Nombre de jours affichés
	public function displayDate()
	{
	return ($this->getNumberDays() > 1);
	}

	// Dernière date de la période
	public function getLastDate()
	{
	return ($this->getDay($this->getNumberLines().'-'.$this->getNumberColumns())->getDate());
	}
}
