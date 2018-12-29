<?php
namespace App\Entity;

class Day
{
	private $date;
	private $type; // O = ouvert, C = fermé (closed), X = clôturé
	private $periodType; // O = OK pour réservation, B = before (avant période de réservation), A = after (après période de réservation)
	private $planificationLine;
	private $timetableLines;

	public function setDate(\Datetime $date)
	{
		$this->date = $date;
		return $this;
	}
    
    public function getDate()
	{
		return $this->date;
	}

	public function getType(): ?string
	{
	return $this->type;
	}

	public function setType(string $type): self
	{
	$this->type = $type;
	return $this;
	}

	public function getPeriodType()
	{
	return $this->periodType;
	}

	public function setPeriodType(string $periodType): self
	{
	$this->periodType = $periodType;
	return $this;
	}

	public function getPlanificationLine(): ?PlanificationLine
	{
	return $this->planificationLine;
	}

	public function setPlanificationLine(?PlanificationLine $planificationLine): self
	{
	$this->planificationLine = $planificationLine;
	return $this;
	}

	public function getTimetableLines()
	{
	return $this->timetableLines;
	}

	public function setTimetableLines($timetableLines): self
	{
	$this->timetableLines = $timetableLines;
	return $this;
	}

	public function __construct($em, PlanificationPeriod $planificationPeriod, \Datetime $date, $inPeriod, $periodType)
	{
	$plRepository = $em->getRepository(PlanificationLine::Class);

	$this->setDate($date);
	$this->setPeriodType($periodType);

	if (!$inPeriod) {
		$this->setType('X'); // La journée est cloturée
		$this->setPlanificationLine(null);
	} else {
		$planificationLine = $plRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => strtoupper($this->getDate()->format('D'))));

		if ($planificationLine === null || $planificationLine->getActive() < 1) {
			$this->setType('C'); // La journée est fermée
		} else {
			$this->setType('O'); // La journée est ouverte
		}
		$this->setPlanificationLine($planificationLine);
	}

	if ($this->getType() == 'O') {
		$tlRepository = $em->getRepository(TimetableLine::Class);
		$timetableLines = $tlRepository->getTimetableLines($this->getPlanificationLine()->getTimetable());
		$this->setTimetableLines($timetableLines);
	}
	}
}
