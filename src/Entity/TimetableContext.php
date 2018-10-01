<?php
namespace App\Entity;

class TimetableContext
{
    protected $planificationsCount;

    public function setPlanificationsCount($planificationsCount)
    {
    $this->planificationsCount = $planificationsCount;
    return $this;
    }

    public function getPlanificationsCount()
    {
    return $this->planificationsCount;
    }

    function __construct($em, \App\Entity\File $file, \App\Entity\Timetable $timetable)
    {
    $pRepository = $em->getRepository(Planification::Class);
    $this->setPlanificationsCount($pRepository->getTimetablePlanificationsCount($file, $timetable));
    return $this;
    }
}
