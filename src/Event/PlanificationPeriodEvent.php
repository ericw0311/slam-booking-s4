<?php
namespace App\Event;

use App\Entity\PlanificationLine;
use App\Entity\Constants;

class PlanificationPeriodEvent
{
	static function postPersist($em, \App\Entity\User $user, \App\Entity\PlanificationPeriod $planificationPeriod)
    {
	PlanificationPeriodEvent::createPlanificationLine($em, $user, $planificationPeriod);
    }
    
    // Initialise les lignes de planification
	static function createPlanificationLine($em, \App\Entity\User $user, \App\Entity\PlanificationPeriod $planificationPeriod)
	{
	foreach (Constants::WEEK_DAY_CODE as $dayOrder => $dayCode) {
		$planificationLine = new PlanificationLine($user, $planificationPeriod, $dayCode, $dayOrder);
		$em->persist($planificationLine);
	}
	$em->flush();
	}
}
