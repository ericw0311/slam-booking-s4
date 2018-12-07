<?php
namespace App\Api;

use App\Entity\UserFile;
use App\Entity\Resource;
use App\Entity\Label;
use App\Entity\TimetableLine;
use App\Entity\PlanificationPeriod;
use App\Entity\PlanificationLine;
use App\Entity\Booking;
use App\Entity\BookingLine;
use App\Entity\BookingUser;
use App\Entity\BookingLabel;
use App\Entity\BookingDateNDB;
use App\Entity\BookingPeriodNDB;
use App\Entity\BookingNDB;
use App\Entity\BookingDuplication;
use App\Entity\SelectedEntity;
use App\Entity\AddEntity;
use App\Entity\Constants;

use App\Api\ResourceApi;

class BookingApi
{
	// firstDateNumber: Premiere date affichee
	// bookingID: Identifient de la réservation mise à jour (0 si création de réservation)
	static function getEndPeriods($em, PlanificationPeriod $planificationPeriod, Resource $resource, \Datetime $beginningDate, TimetableLine $beginningTimetableLine, $bookingID, $firstDateNumber, &$nextFirstDateNumber)
	{
	$plRepository = $em->getRepository(PlanificationLine::Class);
	$tlRepository = $em->getRepository(TimetableLine::Class);
	$blRepository = $em->getRepository(BookingLine::Class);
	$endPeriods = array();
	$dateIndex = 0;
	$numberDates = 0;
	$numberPeriods = 0;
	$continue = true;
	while ($continue) {
		$date = clone $beginningDate;
		$date->add(new \DateInterval('P'.$dateIndex.'D'));
		$planificationLine = $plRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => strtoupper($date->format('D'))));
		if ($planificationLine !== null && $planificationLine->getActive() > 0) {
			$numberDates++;
			$endDate = new BookingDateNDB($date);
			if ($dateIndex > 0) {
				$timetableLines = $tlRepository->getTimetableLines($planificationLine->getTimetable());
			} else {
				$timetableLines = $tlRepository->getCurrentAndNextTimetableLines($planificationLine->getTimetable(), $beginningTimetableLine->getID());
			}
			$dateTimetableLinesList = $date->format('Ymd').'+'.$planificationLine->getTimetable()->getID();
			$firstDatePeriod = true; // Premiere période de la date
			foreach ($timetableLines as $timetableLine) {
				if ($continue) {
	$dateTimetableLinesList = ($firstDatePeriod) ? ($dateTimetableLinesList.'+'.$timetableLine->getID()) : ($dateTimetableLinesList.'*'.$timetableLine->getID());
	$periodTimetableLinesList = ($numberDates <= 1) ? $dateTimetableLinesList : ($timetableLinesList.'-'.$dateTimetableLinesList);
	
	// Recherche d'une ligne de réservation existante.
	$bookingLineDB = $blRepository->findOneBy(array('resource' => $resource, 'ddate' => $date, 'timetable' => $timetableLine->getTimetable(), 'timetableLine' => $timetableLine));
	if ($bookingLineDB === null || $bookingLineDB->getBooking()->getID() == $bookingID) { // La ressource n'est pas réservée pour le créneau (ou bien on est en mise à jour de réservation et le créneau est réservé pour la réservation à mettre à jour).
					$status = "OK";
					$numberPeriods++;
	} else { // Une réservation existe sur ce créneau (ou une autre réservation que celle à mettre à jour)
					$status = "KO";
					$continue = false;
	}
					$endPeriod = new BookingPeriodNDB($timetableLine, $periodTimetableLinesList, $status);
					$endDate->addPeriod($endPeriod);
				}
			
				$firstDatePeriod = false;
				if ($numberPeriods >= Constants::MAXIMUM_NUMBER_BOOKING_LINES) { $continue = false; } // Nombre maximum de periodes pour une reservation atteint
			}
			
			if ($numberDates >= $firstDateNumber) { $endPeriods[] = $endDate; }
			
			$timetableLinesList = ($numberDates <= 1) ? $dateTimetableLinesList : ($timetableLinesList.'-'.$dateTimetableLinesList);
			if ($numberDates >= ($firstDateNumber - 1 + Constants::MAXIMUM_NUMBER_BOOKING_DATES_DISPLAYED)) { $continue = false; } // Nombre maximum de dates affichées atteint
		}
		$dateIndex++;
	}
	// Premiere date affichee suivante: 0 si on a atteint le nombre de periodes de réservation maximum, calculée sinon
	$nextFirstDateNumber = ($numberPeriods >= Constants::MAXIMUM_NUMBER_BOOKING_LINES) ? 0 : ($firstDateNumber + Constants::MAXIMUM_NUMBER_BOOKING_DATES_DISPLAYED);
	return $endPeriods;
    }
    
	// Gestion des utilisateurs des réservations
	// Retourne un tableau des utilisateurs sélectionnés
	// resourceIDList: Liste des ID des utilisateurs sélectionnés
	static function getSelectedUserFiles($em, $userFileIDList)
	{
	$userFileIDArray = explode('-', $userFileIDList);
    $ufRepository = $em->getRepository(UserFile::Class);
	$selectedUserFiles = array();
	$i = 0;
    foreach ($userFileIDArray as $userFileID) {
		$userFileDB = $ufRepository->find($userFileID);
		if ($userFileDB !== null) {
			$userFile = new SelectedEntity(); // classe générique des entités sélectionnées
			$userFile->setId($userFileDB->getId());
			$userFile->setName($userFileDB->getFirstAndLastName());
			$userFile->setImageName($userFileDB->getAdministrator() ? "administrator-32.png" : "user-32.png");
			$userFileIDArray_tprr = $userFileIDArray;
			unset($userFileIDArray_tprr[$i]);
			$userFile->setEntityIDList_unselect(implode('-', $userFileIDArray_tprr)); // Liste des utilisateurs sélectionnés si l'utilisateur désélectionne l'utilisateur
			if (count($userFileIDArray) > 1) {
				if ($i > 0) {
					$userFileIDArray_tprr = $userFileIDArray;
					$userFileIDArray_tprr[$i] = $userFileIDArray_tprr[$i-1];
					$userFileIDArray_tprr[$i-1] = $userFileID;
					$userFile->setEntityIDList_sortBefore(implode('-', $userFileIDArray_tprr)); // Liste des utilisateurs sélectionnés si l'utilisateur remonte l'utilisateur dans l'ordre de tri
				}
				if ($i < count($userFileIDArray)-1) {
					$userFileIDArray_tprr = $userFileIDArray;
					$userFileIDArray_tprr[$i] = $userFileIDArray_tprr[$i+1];
					$userFileIDArray_tprr[$i+1] = $userFileID;
					$userFile->setEntityIDList_sortAfter(implode('-', $userFileIDArray_tprr)); // Liste des utilisateurs sélectionnés si l'utilisateur redescend l'utilisateur dans l'ordre de tri
				}
			}
			$i++;
			array_push($selectedUserFiles, $userFile);
		}
	}
	return $selectedUserFiles;
    }

	// Retourne un tableau des utilisateurs pouvant être ajoutés à une réservation
	static function getAvailableUserFiles($userFilesDB, $selectedUserFileIDList)
    {
	$selectedUserFileIDArray = explode('-', $selectedUserFileIDList);
	$availableUserFiles = array();
    foreach ($userFilesDB as $userFileDB) {
		if (array_search($userFileDB->getId(), $selectedUserFileIDArray) === false) {
			$userFile = new AddEntity(); // classe générique des entités pouvant être ajoutées à la sélection
			$userFile->setId($userFileDB->getId());
			$userFile->setName($userFileDB->getFirstAndLastName());
			$userFile->setImageName($userFileDB->getAdministrator() ? "administrator-32.png" : "user-32.png");
			$userFile->setEntityIDList_select(($selectedUserFileIDList == '') ? $userFileDB->getId() : ($selectedUserFileIDList.'-'.$userFileDB->getId())); // Liste des utilisateurs sélectionnés si l'utilisateur sélectionne l'utilisateur
			array_push($availableUserFiles, $userFile);
		}
	}
	return $availableUserFiles;
    }

	// Retourne un tableau d'utilisateurs à partir d'une liste d'ID
	static function getUserFiles($em, $userFileIDList)
	{
	$userFileIDArray = explode("-", $userFileIDList);
	$userFiles = array();
	$ufRepository = $em->getRepository(UserFile::Class);
	foreach ($userFileIDArray as $userFileID) {
		$userFile = $ufRepository->find($userFileID);
		if ($userFile !== null) {
			$userFiles[] = $userFile;
		}
	}
	return $userFiles;
	}

	// Retourne un tableau des ressources à planifier (initialisation de planification)
	static function initAvailableUserFiles($em, \App\Entity\File $file, $selectedUserFileIDList)
	{
	$ufRepository = $em->getRepository(UserFile::Class);
	$userFilesDB = $ufRepository->getUserFiles($file);
	return BookingApi::getAvailableUserFiles($userFilesDB, $selectedUserFileIDList);
	}

	// Retourne une chaine correspondant à la liste des utilisateurs d'une réservation
	static function getBookingUsersUrl($em, \App\Entity\Booking $booking)
	{
	$buRepository = $em->getRepository(BookingUser::Class);
	$bookingUsersDB = $buRepository->getBookingUsers($booking);
	if (count($bookingUsersDB) <= 0) {
		return '';
	}
	$premier = true;
	foreach ($bookingUsersDB as $bookingUser) {
		if ($premier) {
			$url = $bookingUser['userFileID'];
		} else {
			$url .= '-'.$bookingUser['userFileID'];
		}
		$premier = false;
	}
	return $url;
	}

	// Retourne la liste des noms des utilisateurs d'une réservation
	static function getBookingUserPlanningInfo($em, \App\Entity\Booking $booking, \App\Entity\UserFile $currentUserFile, &$numberUsers)
	{
	$buRepository = $em->getRepository(BookingUser::Class);
	$bookingUsers = $buRepository->findBy(array('booking' => $booking), array('oorder' => 'asc'));
	if (count($bookingUsers) <= 0) { // Ce cas ne doit pas arriver. Toute réservation a au moins un utilisateur. Mais si cela arrive, on initialise la liste des utilisateurs avec l'utilisateur courant
		$numberUsers = 1;
		return $currentUserFile->getFirstAndLastName();
	}
	
	$numberUsers = count($bookingUsers);
	return $bookingUsers[0]->getUserFile()->getFirstAndLastName();
	}

	// Gestion des étiquettes des réservations
	// Retourne un tableau des étiquettes sélectionnées
	// labelIDList: Liste des ID des étiquettes sélectionnées
	static function getSelectedLabels($em, $labelIDList)
	{
	$labelIDArray = array();
	if (strcmp($labelIDList, "0") != 0) { // La chaine '0' équivaut à une chaine vide
		$labelIDArray = explode("-", $labelIDList);
	}
    $lRepository = $em->getRepository(Label::Class);
	$selectedLabels = array();
	$i = 0;
    foreach ($labelIDArray as $labelID) {
		$labelDB = $lRepository->find($labelID);
		if ($labelDB !== null) {
			$label = new SelectedEntity(); // classe générique des entités sélectionnées
			$label->setId($labelDB->getId());
			$label->setName($labelDB->getName());
			$label->setImageName("label-32.png");
			$labelIDArray_tprr = $labelIDArray;
			unset($labelIDArray_tprr[$i]);
			$label->setEntityIDList_unselect((count($labelIDArray_tprr) > 0) ? implode('-', $labelIDArray_tprr) : '0'); // Liste des étiquettes sélectionnées si l'utilisateur désélectionne l'étiquette
			if (count($labelIDArray) > 1) {
				if ($i > 0) {
					$labelIDArray_tprr = $labelIDArray;
					$labelIDArray_tprr[$i] = $labelIDArray_tprr[$i-1];
					$labelIDArray_tprr[$i-1] = $labelID;
					$label->setEntityIDList_sortBefore(implode('-', $labelIDArray_tprr)); // Liste des étiquettes sélectionnées si l'utilisateur remonte l'étiquette dans l'ordre de tri
				}
				if ($i < count($labelIDArray)-1) {
					$labelIDArray_tprr = $labelIDArray;
					$labelIDArray_tprr[$i] = $labelIDArray_tprr[$i+1];
					$labelIDArray_tprr[$i+1] = $labelID;
					$label->setEntityIDList_sortAfter(implode('-', $labelIDArray_tprr)); // Liste des étiquettes sélectionnées si l'utilisateur redescend l'étiquette dans l'ordre de tri
				}
			}
			$i++;
			array_push($selectedLabels, $label);
		}
	}
	return $selectedLabels;
    }
	
	// Retourne un tableau des étiquettes pouvant être ajoutées à une réservation
	static function getAvailableLabels($labelsDB, $selectedLabelIDList)
    {
	$selectedLabelIDArray = array();
	if (strcmp($selectedLabelIDList, "0") != 0) { // La chaine '0' équivaut à une chaine vide
		$selectedLabelIDArray = explode("-", $selectedLabelIDList);
	}
	$availableLabels = array();
    foreach ($labelsDB as $labelDB) {
		if (array_search($labelDB->getId(), $selectedLabelIDArray) === false) {
			$label = new AddEntity(); // classe générique des entités pouvant être ajoutées à la sélection
			$label->setId($labelDB->getId());
			$label->setName($labelDB->getName());
			$label->setImageName("label-32.png");
			$label->setEntityIDList_select((count($selectedLabelIDArray) < 1) ? $labelDB->getId() : ($selectedLabelIDList.'-'.$labelDB->getId())); // Liste des étiquettes sélectionnées si l'utilisateur sélectionne l'étiquette
			array_push($availableLabels, $label);
		}
	}
	return $availableLabels;
    }

	// Retourne un tableau des étiquettes pouvant être ajoutées à une réservation
	static function initAvailableLabels($em, \App\Entity\File $file, $selectedLabelIDList)
	{
	$lRepository = $em->getRepository(Label::Class);
	$labelsDB = $lRepository->getLabels	($file);
	return BookingApi::getAvailableLabels($labelsDB, $selectedLabelIDList);
	}

	// Retourne une chaine correspondant à la liste des étiquettes d'une réservation
	static function getBookingLabelsUrl($em, \App\Entity\Booking $booking)
	{
	$blRepository = $em->getRepository(BookingLabel::Class);
	$bookingLabelsDB = $blRepository->getBookingLabels($booking);
	if (count($bookingLabelsDB) <= 0) {
		return '0';
	}
	$premier = true;
	foreach ($bookingLabelsDB as $bookingLabel) {
		if ($premier) {
			$url = $bookingLabel['labelID'];
		} else {
			$url .= '-'.$bookingLabel['labelID'];
		}
		$premier = false;
	}
	return $url;
	}

	// Retourne un tableau d'étiquettes à partir d'une liste d'ID
	static function getLabels($em, $labelIDList)
	{
	$labelIDArray = array();
	if (strcmp($labelIDList, "0") != 0) { // La chaine '0' équivaut à une chaine vide
		$labelIDArray = explode("-", $labelIDList);
	}
	$labels = array();
	$lRepository = $em->getRepository(Label::Class);
	foreach ($labelIDArray as $labelID) {
		$label = $lRepository->find($labelID);
		if ($label !== null) {
			$labels[] = $label;
		}
	}
	return $labels;
	}

	// Retourne un tableau d'identifiants d'étiquettes à partir d'une liste d'ID
	static function getLabelsID($labelIDList)
	{
	$labelsID = array();
	if (strcmp($labelIDList, "0") != 0) {
		$labelsID = explode("-", $labelIDList);
	}
	return $labelsID;
	}

	// Retourne un tableau des étiquettes d'une réservation
	static function getBookingLabelPlanningInfo($em, \App\Entity\Booking $booking, &$numberLabels)
	{
	$blRepository = $em->getRepository(BookingLabel::Class);
	$bookingLabels = $blRepository->findBy(array('booking' => $booking), array('oorder' => 'asc'));

	if (count($bookingLabels) <= 0) {
		$numberLabels = 0;
		return '';
	}
	
	$numberLabels = count($bookingLabels);
	return $bookingLabels[0]->getLabel()->getName();
	}

	// Retourne les informations de début et de fin de réservation à partir d'une liste de périodes contenue dans une Url
    static function getBookingLinesUrlBeginningAndEndPeriod($em, $timetableLinesList, &$beginningDate, &$beginningTimetableLine, &$endDate, &$endTimetableLine)
	{
	$cellArray  = explode("-", $timetableLinesList);
    $ttlRepository = $em->getRepository(TimetableLine::Class);
	list($beginningDateString, $beginningTimetableID, $beginningTimetableLinesList) = explode("+", $cellArray[0]);
	$beginningDate = date_create_from_format("Ymd", $beginningDateString);
	$beginningTimetableLines = explode("*", $beginningTimetableLinesList);
	$beginningTimetableLineID = $beginningTimetableLines[0];
	$beginningTimetableLine = $ttlRepository->find($beginningTimetableLineID);
	list($endDateString, $endTimetableID, $endTimetableLinesList) = explode("+", $cellArray[count($cellArray)-1]);
	$endDate = date_create_from_format("Ymd", $endDateString);
	$endTimetableLines = explode("*", $endTimetableLinesList);
	$endTimetableLineID = $endTimetableLines[count($endTimetableLines)-1];
	$endTimetableLine = $ttlRepository->find($endTimetableLineID);
	}

	// Convertit une URL comprenant une liste de grilles horaires (pour réservation) en un tableau de grilles horaires
	static function getTimetableLines($timetableLinesUrl)
	{
	$timetableLineArray = array();
	$urlArray  = explode("-", $timetableLinesUrl);
	foreach ($urlArray as $urlDate) {
		list($dateString, $timetableID, $timetableLinesList) = explode("+", $urlDate);	
		$timetableLineIDArray = explode("*", $timetableLinesList);
		foreach ($timetableLineIDArray as $timetableLineID) {
			$timetableLineArray[] = ($dateString.'-'.$timetableID.'-'.$timetableLineID);
		}
	}
	return $timetableLineArray;
	}


	static function getPlanningBookings($em, \App\Entity\File $file, \Datetime $beginningDate, \Datetime $endDate, \App\Entity\Planification $planification, \App\Entity\PlanificationPeriod $planificationPeriod, \App\Entity\UserFile $currentUserFile)
	{
	$bRepository = $em->getRepository(Booking::Class);

	$evenResourcesID = ResourceApi::getEvenPlanifiedResourcesID($em, $planificationPeriod);
	$bookingsDB = $bRepository->getPlanningBookings($file, $beginningDate, $endDate, $planification, $planificationPeriod);

	return BookingApi::getPlanningBookingArray($em, $currentUserFile, $bookingsDB, 'P', $evenResourcesID, 0, 0);
	}


	static function getDuplicateBookings($em, \App\Entity\File $file, \Datetime $beginningDate, \Datetime $endDate, \Datetime $newBookingBeginningDate, \Datetime $newBookingEndDate,
		\App\Entity\Planification $planification, \App\Entity\PlanificationPeriod $planificationPeriod, \App\Entity\Booking $originBooking, $newBookingID, \App\Entity\UserFile $currentUserFile)
	{
	$bRepository = $em->getRepository(Booking::Class);

	$evenResourcesID = array();
	$bookingsDB = $bRepository->getDuplicateBookings($file, $beginningDate, $endDate, $newBookingBeginningDate, $newBookingEndDate,
		$planification, $planificationPeriod, $originBooking->getResource());

	return BookingApi::getPlanningBookingArray($em, $currentUserFile, $bookingsDB, 'D', $evenResourcesID, $originBooking->getID(), $newBookingID);
	}

	// Retourne le tableau des réservations pour affichage dans un planning
	// bookingsDB: Ressources interrogées en base de données
	// planningType: P = Planning, C = réservations Cycliques
	// evenResourcesID: Tableau des ressources ayant un numéro d'ordre pair: Pour planningType P = Planning
	// bookingID: Réservation: Pour planningType D = Duplication de réservation
	static function getPlanningBookingArray($em, \App\Entity\UserFile $currentUserFile, $bookingsDB, $planningType, $evenResourcesID, $originBookingID, $newBookingID)
	{
	$bRepository = $em->getRepository(Booking::Class);

	$bookings = array();
	if (count($bookingsDB) <= 0) {
		return $bookings;
	}

	$memo_date = "00000000";
	$memo_bookingID = 0;
	$memo_resourceID = 0;
	$currentBookingHeaderKey = "";
	$bookingTimetableLinesCount = 0; // Compteur des lignes de la reservation courante.
	$resourceBookingCount = 0; // Compteur des reservations de la ressource courante.
	$numberUsers = 1;
	$numberLabels = 0;

	foreach ($bookingsDB as $booking) {
		$key = $booking['date']->format('Ymd').'-'.$booking['planificationID'].'-'.$booking['planificationPeriodID'].'-'.$booking['planificationLineID'].'-'.$booking['resourceID'].'-'.$booking['timetableID'].'-'.$booking['timetableLineID'];

		if ($memo_bookingID > 0 && ($booking['bookingID'] <> $memo_bookingID || $booking['date']->format('Ymd') <> $memo_date)) { // On a parcouru une reservation ou rupture de date.
			$bookings[$currentBookingHeaderKey]->setNumberTimetableLines($bookingTimetableLinesCount);

			$userString = BookingApi::getBookingUserPlanningInfo($em, $bRepository->find($memo_bookingID), $currentUserFile, $numberUsers);
			$bookings[$currentBookingHeaderKey]->setFirstUserName($userString);
			$bookings[$currentBookingHeaderKey]->setNumberUsers($numberUsers);

			$labelString = BookingApi::getBookingLabelPlanningInfo($em, $bRepository->find($memo_bookingID), $numberLabels);
			$bookings[$currentBookingHeaderKey]->setFirstLabelName($labelString);
			$bookings[$currentBookingHeaderKey]->setNumberLabels($numberLabels);

			$bookings[$currentBookingHeaderKey]->setNote($bRepository->find($memo_bookingID)->getNote());
			$bookings[$currentBookingHeaderKey]->setUserId($bRepository->find($memo_bookingID)->getUser()->getID());
			$bookingTimetableLinesCount = 0;
			$resourceBookingCount++;
		}

		if ($booking['date']->format('Ymd') <> $memo_date || $booking['resourceID'] <> $memo_resourceID) { // On change de date ou de ressource.
			$resourceBookingCount = 0;
		}

		$bookingTimetableLinesCount++;

		if ($booking['bookingID'] <> $memo_bookingID || $booking['date']->format('Ymd') <> $memo_date) {
			$cellType = 'H';
			$currentBookingHeaderKey = $key;
		} else {
			$cellType = 'L';
		}

		if ($planningType == 'D') {
			// Duplication de réservation: La réservation traitée a une couleur verte (success), les autres ont une couleur rouge (danger)
			$cellClass = ((($booking['bookingID'] == $originBookingID) or ($booking['bookingID'] == $newBookingID)) ? 'success' : 'danger');
		} else {
			// Planning: La couleur des réservations est alternée à la fois entre ressources (utilisation du tableau des ressources d'ordre pair) et entre réservations d'une même journée (Compteur resourceBookingCount)
			$cellClass = (in_array($booking['resourceID'], $evenResourcesID) ? ((($resourceBookingCount % 2) < 1) ? 'success' : 'warning') : ((($resourceBookingCount % 2) < 1) ? 'info' : 'danger'));
		}
		$bookingNDB = new BookingNDB($booking['bookingID'], $cellType, $cellClass);
		$bookings[$key] = $bookingNDB;

		$memo_bookingID = $booking['bookingID'];
		$memo_resourceID = $booking['resourceID'];
		$memo_date = $booking['date']->format('Ymd');
	}

	$bookings[$currentBookingHeaderKey]->setNumberTimetableLines($bookingTimetableLinesCount); // Derniere reservation

	$userString = BookingApi::getBookingUserPlanningInfo($em, $bRepository->find($memo_bookingID), $currentUserFile, $numberUsers);
	$bookings[$currentBookingHeaderKey]->setFirstUserName($userString);
	$bookings[$currentBookingHeaderKey]->setNumberUsers($numberUsers);

	$labelString = BookingApi::getBookingLabelPlanningInfo($em, $bRepository->find($memo_bookingID), $numberLabels);
	$bookings[$currentBookingHeaderKey]->setFirstLabelName($labelString);
	$bookings[$currentBookingHeaderKey]->setNumberLabels($numberLabels);

	$bookings[$currentBookingHeaderKey]->setNote($bRepository->find($memo_bookingID)->getNote());
	$bookings[$currentBookingHeaderKey]->setUserId($bRepository->find($memo_bookingID)->getUser()->getID());
	return $bookings;
	}

	// Retourne une chaine correspondant à la liste des creneaux horaires d'une réservation
	static function getBookingLinesUrl($em, \App\Entity\Booking $booking)
	{
	$blRepository = $em->getRepository(BookingLine::Class);
	$bookingLinesDB = $blRepository->getBookingLines($booking);
	if (count($bookingLinesDB) <= 0) {
		return '';
	}

	// On construit une chaine comprenant toutes périodes de la réservation.
	// Les périodes sont regroupées par date séparées par un -
	// Pour chaque date, on a le codage date + timetableID + timetableLineIDList
	// timetableLineIDList est la liste des timetableLineID séparés par un *
	$premier = true;
	foreach ($bookingLinesDB as $bookingLine) {
		if ($premier) {
			$url = $bookingLine['date']->format('Ymd').'+'.$bookingLine['timetableID'].'+'.$bookingLine['timetableLineID'];
		
		} else if ($bookingLine['date']->format('Ymd') != $memo_date) {
			$url .= '-'.$bookingLine['date']->format('Ymd').'+'.$bookingLine['timetableID'].'+'.$bookingLine['timetableLineID'];
		} else {
			$url .= '*'.$bookingLine['timetableLineID'];
		}
		$premier = false;
		$memo_date = $bookingLine['date']->format('Ymd');
	}
	return $url;
	}

	// Retourne la liste d'emails des destinataires du mail informant de la création/mise à jour/suppression des réservations
	static function getBookingUserEmailArray($em, \App\Entity\Booking $booking, $fileAdministrator, $bookingUser)
	{
	$ufRepository = $em->getRepository(UserFile::Class);
	$buRepository = $em->getRepository(BookingUser::Class);

	$emailArray = array();

	if ($fileAdministrator) { // Administrateurs du dossier
		$fileAdministrators = $ufRepository->getUserFileAdministrators($booking->getFile());

		foreach ($fileAdministrators as $userFile) {
			array_push($emailArray, $userFile->getEmail());
		}
	}

	if ($bookingUser) { // Utilisateurs de la réservation
		$bookingUsers = $buRepository->findBy(array('booking' => $booking), array('oorder' => 'asc'));

		foreach ($bookingUsers as $bookingUser) {
			// Je ne parviens pas à mettre le nom des utilisateurs en clair... array_push($emailArray, [$bookingUser->getUserFile()->getEmail() => $bookingUser->getUserFile()->getFirstAndLastName()]);
			// alors que ça, ça marche bien... ->setTo(['eric.pierre.willard@gmail.com', 'maxence.willard@gmail.com' => 'Maxence Willard'])

			if (!$fileAdministrator or !$bookingUser->getUserFile()->getAdministrator()) { // On traite le cas ou l'utilisateur est déjà dans la liste en tant que administrateur du dossier.
				array_push($emailArray, $bookingUser->getUserFile()->getEmail());
			}
		}
	}
	return $emailArray;
	}


	// Contrôle si une réservation peut être duppliquée. On parcourt les lignes de réservation et on recherche sur les conditions de la clé unique
	// Retourne 0 si aucune ligne de réservation trouvée, et l'ID de la première ligne sinon
	static function ctrlDuplicateBooking($em, \App\Entity\Booking $booking, $gap)
	{
	$blRepository = $em->getRepository(BookingLine::Class);
	$bookingLinesDB = $blRepository->getBookingLines($booking);

	$bookingLines = $blRepository->findBy(array('booking' => $booking), array('id' => 'asc'));
	foreach ($bookingLines as $bookingLine) {

		$newBookingLineDate = clone $bookingLine->getDate();
		$newBookingLineDate->add(new \DateInterval('P'.$gap.'D'));

		$newBookingLineDate = $blRepository->findOneBy(array('resource' => $bookingLine->getResource(), 'ddate' => $newBookingLineDate, 'timetable' => $bookingLine->getTimetable(), 'timetableLine' => $bookingLine->getTimetableLine()));
		if ($newBookingLineDate !== null) {
			return $newBookingLineDate->getId();
		}
	}
	return 0;
	}

	// Dupplication d'une réservation.
	static function duplicateBooking($em, \App\Entity\Booking $booking, $gap, $connectedUser, $currentFile)
	{
	$bliRepository = $em->getRepository(BookingLine::Class);
	$buRepository = $em->getRepository(BookingUser::Class);
	$blaRepository = $em->getRepository(BookingLabel::Class);
	$plRepository = $em->getRepository(PlanificationLine::Class);

	$firstBookingLine = $bliRepository->getFirstBookingLine($booking);
	$lastBookingLine = $bliRepository->getLastBookingLine($booking);

	$newBookingBeginningDate = clone $firstBookingLine->getDate();
	$newBookingBeginningDate->add(new \DateInterval('P'.$gap.'D'));

	$newBookingEndDate = clone $lastBookingLine->getDate();
	$newBookingEndDate->add(new \DateInterval('P'.$gap.'D'));

	$newBooking = new Booking($connectedUser, $currentFile, $booking->getPlanification(), $booking->getResource());

	$newBooking->setBeginningDate(date_create_from_format('YmdHi', $newBookingBeginningDate->format('Ymd').$firstBookingLine->getTimetableLine()->getBeginningTime()->format('Hi')));
	$newBooking->setEndDate(date_create_from_format('YmdHi', $newBookingEndDate->format('Ymd').$lastBookingLine->getTimetableLine()->getEndTime()->format('Hi')));

	$newBooking->setNote($booking->getNote());
	// $newBooking->setFormNote($booking->getFormNote());
	$em->persist($newBooking);

	$bookingLines = $bliRepository->findBy(array('booking' => $booking), array('id' => 'asc'));
	foreach ($bookingLines as $bookingLine) {

		$newBookingLineDate = clone $bookingLine->getDate();
		$newBookingLineDate->add(new \DateInterval('P'.$gap.'D'));

		$newBookingLine = new BookingLine($connectedUser, $newBooking, $bookingLine->getResource());
		$newBookingLine->setDate($newBookingLineDate);
		$newBookingLine->setPlanification($bookingLine->getPlanification());
		$newBookingLine->setPlanificationPeriod($bookingLine->getPlanificationPeriod());
		// La référence à la ligne de planification est recherchée car si la nouvelle réservation et l'ancienne ne sont pas sur un même jour de la semaine (ce qui est théoriquement possible) cette référence n'est pas la même entre les deux réservations
		$newBookingLine->setPlanificationLine($plRepository->findOneBy(array('planificationPeriod' => $bookingLine->getPlanificationPeriod(), 'weekDay' => strtoupper($newBookingLineDate->format('D')))));
		$newBookingLine->setTimetable($bookingLine->getTimetable());
		$newBookingLine->setTimetableLine($bookingLine->getTimetableLine());
		$em->persist($newBookingLine);
	}

	$bookingUsers = $buRepository->findBy(array('booking' => $booking), array('id' => 'asc'));
	foreach ($bookingUsers as $bookingUser) {

		$newBookingUser = new BookingUser($connectedUser, $newBooking, $bookingUser->getUserFile());
		$newBookingUser->setOrder($bookingUser->getOrder());
		$em->persist($newBookingUser);
	}

	$bookingLabels = $blaRepository->findBy(array('booking' => $booking), array('id' => 'asc'));
	foreach ($bookingLabels as $bookingLabel) {

		$newBookingLabel = new BookingLabel($connectedUser, $newBooking, $bookingLabel->getLabel());
		$newBookingLabel->setOrder($bookingLabel->getOrder());
		$em->persist($newBookingLabel);
	}

	$bookingDuplication = new BookingDuplication($connectedUser, $booking, $newBookingBeginningDate, $gap, $newBooking);
	$em->persist($bookingDuplication);

	$em->flush();
	return $newBooking->getID();
	}
}
