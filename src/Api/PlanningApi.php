<?php
namespace App\Api;

use App\Entity\Planification;
use App\Entity\UserParameter;

class PlanningApi
{
    // Retourne la planification en cours d'un utilisateur
    static function getCurrentCalendarPlanification($em, \App\Entity\User $user)
    {
    $upRepository = $em->getRepository(UserParameter::Class);
    return $upRepository->findOneBy(array('user' => $user, 'parameterGroup' => 'calendar', 'parameter' => 'current.planification'));
    }

    // Retourne l'ID de la planification en cours d'un utilisateur
    static function getCurrentCalendarPlanificationID($em, \App\Entity\User $user)
    {
    $upRepository = $em->getRepository(UserParameter::Class);
    $userParameter = $upRepository->findOneBy(array('user' => $user, 'parameterGroup' => 'calendar', 'parameter' => 'current.planification'));
    if ($userParameter === null) {
        return 0;
    } else {
        return $userParameter->getIntegerValue();
    }
    }

    // Positionne la planification comme planification en cours
    static function setCurrentCalendarPlanification($em, \App\Entity\User $user, \App\Entity\Planification $planification)
    {
    // Recherche de la planification en cours
    $userParameter = PlanningApi::getCurrentCalendarPlanification($em, $user);
    if ($userParameter === null) {
        $userParameter = new UserParameter($user, 'calendar', 'current.planification');
        $userParameter->setSDIntegerValue($planification->getId());
        $em->persist($userParameter);
	} else {
        $userParameter->setSDIntegerValue($planification->getId());
	}
	$em->flush();
    }

    // Positionne la planification comme planification en cours (idem setCurrentCalendarPlanification mais directement à partir de l'ID de la planification)
    static function setCurrentCalendarPlanificationID($em, \App\Entity\User $user, $planificationID)
    {
    // Recherche de la planification en cours
    $userParameter = PlanningApi::getCurrentCalendarPlanification($em, $user);
    if ($userParameter === null) {
        $userParameter = new UserParameter($user, 'calendar', 'current.planification');
        $userParameter->setSDIntegerValue($planificationID);
        $em->persist($userParameter);
    } else {
        $userParameter->setSDIntegerValue($planificationID);
    }
    $em->flush();
	}
	
    // Retourne la planification en cours d'un utilisateur
    static function getCurrentCalendarMany($em, \App\Entity\User $user)
    {
    $upRepository = $em->getRepository(UserParameter::Class);
    return $upRepository->findOneBy(array('user' => $user, 'parameterGroup' => 'calendar', 'parameter' => 'current.many'));
    }

    // Retourne l'indicateur many (affichage du planning) d'un utilisateur
    static function getCurrentCalendarManyValue($em, \App\Entity\User $user)
    {
    $upRepository = $em->getRepository(UserParameter::Class);
    $userParameter = $upRepository->findOneBy(array('user' => $user, 'parameterGroup' => 'calendar', 'parameter' => 'current.many'));
    if ($userParameter === null) {
        return false;
    } else {
        return $userParameter->getBooleanValue();
    }
    }

    // Positionne l'indicateur many de l'affichage du calendrier
    static function setCurrentCalendarMany($em, \App\Entity\User $user, $many)
    {
    // Recherche de la planification en cours
    $userParameter = PlanningApi::getCurrentCalendarMany($em, $user);
    if ($userParameter === null) {
        $userParameter = new UserParameter($user, 'calendar', 'current.many');
		$userParameter->setSDBooleanValue(($many > 0));
        $em->persist($userParameter);
	} else {
		$userParameter->setSDBooleanValue(($many > 0));
	}
	$em->flush();
    }

    // Retourne le nombre de planifications d'un dossier actives à la date du jour
	static function getNumberOfPlanifications($em, \App\Entity\File $file)
    {
    $pRepository = $em->getRepository(Planification::Class);
	$planifications = $pRepository->getPlanningPlanifications($file, new \DateTime());
	return count($planifications);
	}
}
