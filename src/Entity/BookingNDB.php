<?php
namespace App\Entity;

// NDB = not database
class BookingNDB
{
	private $id;
	private $type;
	private $numberTimetableLines;
	private $cellClass;
	private $userNamesString;
	private $labelNamesString;
	private $note;

	public function setId($id)
	{
	$this->id = $id;
	return $this;
	}

	public function getId()
	{
	return $this->id;
	}

	public function setType($type)
	{
	$this->type = $type;
	return $this;
	}

	public function getType()
	{
	return $this->type;
	}

	public function setNumberTimetableLines($numberTimetableLines)
	{
	$this->numberTimetableLines = $numberTimetableLines;
	return $this;
	}

	public function getNumberTimetableLines()
	{
	return $this->numberTimetableLines;
	}

	public function setCellClass($cellClass)
	{
	$this->cellClass = $cellClass;
	return $this;
	}

	public function getCellClass()
	{
	return $this->cellClass;
	}

	public function setUserNamesString($userNamesString)
	{
	$this->userNamesString = $userNamesString;
	return $this;
	}

	public function getUserNamesString()
	{
	return $this->userNamesString;
	}
	
	public function setLabelNamesString($labelNamesString)
	{
	$this->labelNamesString = $labelNamesString;
	return $this;
	}

	public function getLabelNamesString()
	{
	return $this->labelNamesString;
	}
	
	public function getLabel() // Indique si au moins une étiquette a été attachée à la réservation
	{
	return (!empty($this->labelNamesString));
	}

	public function setNote($note)
	{
	$this->note = $note;
	return $this;
	}

	public function getNote()
	{
	return $this->note;
	}
	
	public function __construct($id, $type, $cellClass)
	{
	$this->setId($id);
	$this->setType($type);
	$this->setNumberTimetableLines(0);
	$this->setCellClass($cellClass);
	$this->setUserNamesString(null);
	$this->setLabelNamesString(null);
	$this->setNote(null);
	}

	public function getNoteExists()
	{
	return (!empty($this->note));
	}
	
	public function getMultiLigne() // Une réservation est affichée en multiligne si elle est multi créneaux horaires et qu'elle a une ou plusieurs étiquettes
	{
	return ($this->getNumberTimetableLines() > 1 && $this->getLabel());
	}
}
