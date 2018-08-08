<?php
namespace App\Entity;

class TimetableContext
{
    protected $planificationPeriodsCount;

    public function setPlanificationPeriodsCount($planificationPeriodsCount)
    {
    $this->planificationPeriodsCount = $planificationPeriodsCount;
    return $this;
    }

    public function getPlanificationPeriodsCount()
    {
    return $this->planificationPeriodsCount;
    }

    function __construct($em, \App\Entity\Timetable $timetable)
    {
/* TPRR
    $plRepository = $em->getRepository(PlanificationLine::Class);
    $this->setPlanificationPeriodsCount($plRepository->getPlanificationPeriodsCount($timetable));
*/
    $this->setPlanificationPeriodsCount(0);
    return $this;
    }
}
