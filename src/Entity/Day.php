<?php
namespace App\Entity;

use Psr\Log\LoggerInterface;

class Day
{
	private $date;
	private $type; // O = ouvert, B = before (ouvert mais avant période de réservation), A = after (ouvert mais après période de réservation), C = fermé (closed), X = clôturé
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


	public function __construct(LoggerInterface $logger, $em, PlanificationPeriod $planificationPeriod, \Datetime $date, $ctrlBefore, \Datetime $firstAllowedBookingDate, $ctrlAfter, \Datetime $lastAllowedBookingDate)
	{
	$plRepository = $em->getRepository(PlanificationLine::Class);
	$this->setDate($date);

	$inPeriod = true;
	if (!$planificationPeriod->isEndDateNull()) { // La periode de planification est cloturée
		$interval = $this->getDate()->diff($planificationPeriod->getEndDate());
		$periodSign = $interval->format('%R');
		$logger->info('Day.construct DBG 1 _'.$planificationPeriod->getEndDate()->format('Y-m-d H:i:s').'_'.$periodSign.'_');

		if ($periodSign == '-') { $inPeriod = false; } // La date affichée est après la date de cloture de la période
	}


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

	$beforeSign = '+';
	$afterSign = '+';

	if ($this->getType() == 'O') {
		if ($ctrlBefore) {
			$interval = $firstAllowedBookingDate->diff($this->getDate());
			$beforeSign = $interval->format('%R');
		}
		if ($ctrlAfter) {
			$interval = $this->getDate()->diff($lastAllowedBookingDate);
			$afterSign = $interval->format('%R');
		}
	}

	if ($beforeSign == '-') { $this->setType('B'); } // Avant période de réservation
	if ($afterSign == '-') { $this->setType('A'); } // Après période de réservation

	if ($this->getType() == 'O') {
		$tlRepository = $em->getRepository(TimetableLine::Class);
		$timetableLines = $tlRepository->getTimetableLines($this->getPlanificationLine()->getTimetable());
		$this->setTimetableLines($timetableLines);
	}
	}
}
