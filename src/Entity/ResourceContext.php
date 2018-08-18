<?php
namespace App\Entity;

class ResourceContext
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
    
    function __construct($em, \App\Entity\Resource $resource)
    {
	/*
    $prRepository = $em->getRepository(PlanificationResource::Class);
    $this->setPlanificationPeriodsCount($prRepository->getPlanificationPeriodsCount($resource));
	*/
    $this->setPlanificationPeriodsCount(0);
    return $this;
    }
}
