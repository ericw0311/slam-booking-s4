<?php
namespace App\Entity;

// NDB = not database
class BookingDateNDB
{
	private $date;
	private $periods;

	public function setDate(\Datetime $date)
	{
		$this->date = $date;
		return $this;
	}
    
    public function getDate()
	{
		return $this->date;
	}
	
    public function __construct(\Datetime $date)
    {
		$this->setDate($date);
		$this->periods = array();
    }
	
	public function addPeriod(\App\Entity\BookingPeriodNDB $period)
	{
		$this->periods[] = $period;
	}
    
    public function getPeriods()
    {
        return $this->periods;
    }
}
