<?php
namespace App\Entity;

class PlanificationPeriodCreateDate
{
	protected $bookingMaxDate;
	protected $date;

	public function setDate($date)
	{
		$this->date = $date;
		return $this;
	}

	public function getDate()
	{
		return $this->date;
	}

	public function setBookingMaxDate($date)
	{
		$this->bookingMaxDate = $date;
		return $this;
	}

	public function getBookingMaxDate()
	{
		return $this->bookingMaxDate;
	}
}
