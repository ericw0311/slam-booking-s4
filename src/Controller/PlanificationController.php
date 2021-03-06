<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use App\Entity\Constants;
use App\Entity\UserParameter;
use App\Entity\UserContext;
use App\Entity\ListContext;
use App\Entity\Trace;
use App\Entity\Resource;
use App\Entity\Planification;
use App\Entity\PlanificationPeriod;
use App\Entity\PlanificationResource;
use App\Entity\PlanificationLine;
use App\Entity\PlanificationLinesNDB;
use App\Entity\PlanificationContext;
use App\Entity\Timetable;
use App\Entity\UserParameterNLC;
use App\Entity\Booking;
use App\Entity\BookingLine;
use App\Entity\PlanificationPeriodCreateDate;

use App\Form\PlanificationType;
use App\Form\PlanificationLinesNDBType;
use App\Form\UserParameterNLCType;
use App\Form\PlanificationPeriodCreateDateType;

use App\Api\AdministrationApi;
use App\Api\ResourceApi;
use App\Api\PlanningApi;

class PlanificationController extends Controller
{
	// Affichage des planifications du dossier en cours
    /**
     * @Route("/planification/{page}", name="planification", requirements={"page"="\d+"})
     */
	public function index($page)
	{
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$pRepository = $em->getRepository(Planification::Class);
	$numberRecords = $pRepository->getPlanificationsCount($userContext->getCurrentFile());
	$listContext = new ListContext($em, $connectedUser, 'planification', 'planification', $page, $numberRecords);
	$listPlanifications = $pRepository->getDisplayedPlanifications($userContext->getCurrentFile(), $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());

	return $this->render('planification/index.html.twig', 
	array('userContext' => $userContext, 'listContext' => $listContext, 'listPlanifications' => $listPlanifications));
    }

	// Ajout d'une planification: Sélection du type de ressources à planifier
    /**
     * @Route("/planification/type", name="planification_type")
     */
    public function type()
	{
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $rRepository = $em->getRepository(Resource::Class);
    $prRepository = $em->getRepository(PlanificationResource::Class);
    $resourceTypesToPlanify = $rRepository->getResourceTypesToPlanify($userContext->getCurrentFile(), $prRepository->getResourcePlanifiedQB());

	return $this->render('planification/type.html.twig', array('userContext' => $userContext, 'resourceTypes' => $resourceTypesToPlanify));
    }
	
	// Initialisation des ressources à planifier
    /**
     * @Route("/planification/initresource/{type}/{resourceIDList}", defaults={"resourceIDList" = null}, name="planification_init_resource")
     */
    public function init_resource($type, $resourceIDList)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$selectedResources = ResourceApi::getSelectedResources($em, $resourceIDList);
	$availableResources = ResourceApi::initAvailableResources($em, $userContext->getCurrentFile(), $type, $resourceIDList);

	return $this->render('planification/resource.insert.html.twig', array('userContext' => $userContext, 'type' => $type, 'selectedResources' => $selectedResources, 'selectedResourcesIDList' => $resourceIDList,
		'availableResources' => $availableResources));
    }

	// Validation des ressources à planifier
    /**
     * @Route("/planification/validateinitresource/{type}/{resourceIDList}", name="planification_validate_init_resource")
     */
    public function validate_init_resource(Request $request, $type, $resourceIDList)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$resourceIDArray = explode('-', $resourceIDList);
    $rRepository = $em->getRepository(Resource::Class);
	$i = 0;
	$planification = new Planification($connectedUser, $userContext->getCurrentFile());
	$planification->setType($type);
	$planification->setInternal(0);
	$planificationPeriod = new PlanificationPeriod($connectedUser, $planification);
	$internal = 0;
	$classificationCode = null;
    foreach ($resourceIDArray as $resourceID) {
		$resourceDB = $rRepository->find($resourceID);
		if ($i++ == 0) {
			$planification->setName($resourceDB->getName());
			$em->persist($planification);
			$em->persist($planificationPeriod);
			// Initialise les lignes de planification
			foreach (Constants::WEEK_DAY_CODE as $dayOrder => $dayCode) {
				$planificationLine = new PlanificationLine($connectedUser, $planificationPeriod, $dayCode, $dayOrder);
				$em->persist($planificationLine);
			}

			if ($resourceDB->getInternal()) { // L'indicateur interne est à 1 si toutes les ressources de la période de planifications sont de classification internes et du même code
				$internal = 1;
				$classificationCode = $resourceDB->getCode();
			}
		} else if ($internal > 0 && ($resourceDB->getInternal() <= 0 || $resourceDB->getCode() !=  $classificationCode)) {
				$internal = 0;
				$classificationCode = null;
		}
		$planificationResource = new PlanificationResource($connectedUser, $planificationPeriod, $resourceDB);
		$planificationResource->setOrder($i);
		$em->persist($planificationResource);
	}
	$planification->setInternal($internal);
	if ($internal > 0) {
		$planification->setCode($classificationCode);
	} else {
		$planification->setCodeNull();
	}
	$em->persist($planification);
	$em->flush();
	$request->getSession()->getFlashBag()->add('notice', 'planification.created.ok');
	return $this->redirectToRoute('planification_line', array('planificationID' => $planification->getID(), 'planificationPeriodID' => $planificationPeriod->getID()));
    }

	// Mise a jour des ressources à planifier
	/**
     * @Route("/planification/updateresource/{planificationID}/{planificationPeriodID}/{resourceIDList}",
     * defaults={"resourceIDList" = null}, 
     * name="planification_update_resource")
	 * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
	 * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
     */
    public function update_resource(Planification $planification, PlanificationPeriod $planificationPeriod, $resourceIDList)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$selectedResources = ResourceApi::getSelectedResources($em, $resourceIDList);
	$availableResources = ResourceApi::updateAvailableResources($em, $userContext->getCurrentFile(), $planification->getType(), $planificationPeriod, $resourceIDList);
    
	return $this->render('planification/resource.update.html.twig', array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod,
		'selectedResources' => $selectedResources, 'selectedResourcesIDList' => $resourceIDList, 'availableResources' => $availableResources));
    }

	// Validation des ressources à planifier
	/**
     * @Route("/planification/validateupdateresource/{planificationID}/{planificationPeriodID}/{resourceIDList}", name="planification_validate_update_resource")
	 * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
	 * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
     */
	public function validate_update_resource(Request $request, Planification $planification, PlanificationPeriod $planificationPeriod, $resourceIDList)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $prRepository = $em->getRepository(PlanificationResource::Class);
    $planificationResources = $prRepository->getResources($planificationPeriod);
	$resourceIDArray = explode('-', $resourceIDList);

    foreach ($planificationResources as $planificationResource) { // Parcours des ressources existantes de la période de planification
		if (array_search($planificationResource->getResource()->getId(), $resourceIDArray) === false) { // Si la ressource n'est pas dans la liste actuelle, on la supprime
			$em->remove($planificationResource);
		}
	}
    $rRepository = $em->getRepository(Resource::Class);
	$i = 0;
	$internal = 0;
	$classificationCode = null;
    foreach ($resourceIDArray as $resourceID) { // Parcours des ressources sélectionnées
		$resource = $rRepository->find($resourceID);
		if ($resource !== null) {
			$i++; // On recherche si la ressource est déjà planifiée pour la période de planification. Si c'est le cas on met à jour l'ordre, sinon on ajoute la ressource à la période de planification
			$planificationResource = $prRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'resource' => $resource));
			if ($planificationResource === null) {
				$planificationResource = new PlanificationResource($connectedUser, $planificationPeriod, $resource);
				$planificationResource->setOrder($i);
			} else {
				$planificationResource->setOrder($i);
			}
			$em->persist($planificationResource);
			
			if ($i <= 1) {
				if ($resource->getInternal()) { // L'indicateur interne est à 1 si toutes les ressources de la période de planifications sont de classification internes et du même code
					$internal = 1;
					$classificationCode = $resource->getCode();
				}
			} else if ($internal > 0 && ($resource->getInternal() <= 0 || $resource->getCode() !=  $classificationCode)) {
				$internal = 0;
				$classificationCode = null;
			}
		}
	}
	$planification->setInternal($internal);
	if ($internal > 0) {
		$planification->setCode($classificationCode);
	} else {
		$planification->setCodeNull();
	}
	$em->persist($planification);
	$em->flush();

	$request->getSession()->getFlashBag()->add('notice', 'planification.resource.updated.ok');
	return $this->redirectToRoute('planification_edit', array('planificationID' => $planification->getID(), 'planificationPeriodID' => $planificationPeriod->getId()));
    }

    // Edition du detail d'une planification
	/**
     * @Route("/planification/editlastperiod/{planificationID}", name="planification_edit_lp")
	 * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
     */
	public function edit_last_period(Planification $planification)
    {
    $em = $this->getDoctrine()->getManager();
    $ppRepository = $em->getRepository(PlanificationPeriod::Class);
    $planificationPeriod = $ppRepository->findOneBy(array('planification' => $planification), array('id' => 'DESC'));

	return $this->redirectToRoute('planification_edit', array('planificationID' => $planification->getID(), 'planificationPeriodID' => $planificationPeriod->getID()));
    }

    // Edition du detail d'une planification
	/**
     * @Route("/planification/edit/{planificationID}/{planificationPeriodID}", name="planification_edit")
	 * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
	 * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
     */
	public function edit(Planification $planification, PlanificationPeriod $planificationPeriod)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $planificationContext = new PlanificationContext($em, $userContext->getCurrentFile(), $planification, $planificationPeriod); // contexte planification
    $prRepository = $em->getRepository(PlanificationResource::Class);
    $planificationResources = $prRepository->getResources($planificationPeriod);
	$resourceIDList = '';
    foreach ($planificationResources as $planificationResourceDB) {
$resourceIDList = ($resourceIDList == '') ? $planificationResourceDB->getResource()->getId() : ($resourceIDList.'-'.$planificationResourceDB->getResource()->getId());
	}
    $plRepository = $em->getRepository(PlanificationLine::Class);
    $planificationLines = $plRepository->getLines($planificationPeriod);

	return $this->render('planification/edit.html.twig', array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod,
		'planificationResources' => $planificationResources, 'resourceIDList' => $resourceIDList, 'planificationLines' => $planificationLines, 'planificationContext' => $planificationContext));
    }


	// Mise a jour des lignes de planification
	/**
     * @Route("/planification/line/{planificationID}/{planificationPeriodID}", name="planification_line")
	 * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
	 * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
     */
	public function line(Request $request, Planification $planification, PlanificationPeriod $planificationPeriod)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $tRepository = $em->getRepository(Timetable::Class);
    $plRepository = $em->getRepository(PlanificationLine::Class);

    $firstTimetable = $tRepository->getFirstTimetable($userContext->getCurrentFile()); // Premiere grille horaire du dossier

	$line_MON = $plRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => 'MON'));
	$line_TUE = $plRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => 'TUE'));
	$line_WED = $plRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => 'WED'));
	$line_THU = $plRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => 'THU'));
	$line_FRI = $plRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => 'FRI'));
	$line_SAT = $plRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => 'SAT'));
	$line_SUN = $plRepository->findOneBy(array('planificationPeriod' => $planificationPeriod, 'weekDay' => 'SUN'));
	$planificationLinesNDB = new PlanificationLinesNDB($planificationPeriod);
	if ($line_MON->getActive()) {
		$planificationLinesNDB->setTimetableMON($line_MON->getTimetable());
	} else {
		$planificationLinesNDB->setTimetableMON($firstTimetable);
	}
	$planificationLinesNDB->setActivateMON($line_MON->getActive());
	if ($line_TUE->getActive()) {
		$planificationLinesNDB->setTimetableTUE($line_TUE->getTimetable());
	} else {
		$planificationLinesNDB->setTimetableTUE($firstTimetable);
	}
	$planificationLinesNDB->setActivateTUE($line_TUE->getActive());
	if ($line_WED->getActive()) {
		$planificationLinesNDB->setTimetableWED($line_WED->getTimetable());
	} else {
		$planificationLinesNDB->setTimetableWED($firstTimetable);
	}
	$planificationLinesNDB->setActivateWED($line_WED->getActive());
	if ($line_THU->getActive()) {
		$planificationLinesNDB->setTimetableTHU($line_THU->getTimetable());
	} else {
		$planificationLinesNDB->setTimetableTHU($firstTimetable);
	}
	$planificationLinesNDB->setActivateTHU($line_THU->getActive());
	if ($line_FRI->getActive()) {
		$planificationLinesNDB->setTimetableFRI($line_FRI->getTimetable());
	} else {
		$planificationLinesNDB->setTimetableFRI($firstTimetable);
	}
	$planificationLinesNDB->setActivateFRI($line_FRI->getActive());
	if ($line_SAT->getActive()) {
		$planificationLinesNDB->setTimetableSAT($line_SAT->getTimetable());
	} else {
		$planificationLinesNDB->setTimetableSAT($firstTimetable);
	}
	$planificationLinesNDB->setActivateSAT($line_SAT->getActive());
	if ($line_SUN->getActive()) {
		$planificationLinesNDB->setTimetableSUN($line_SUN->getTimetable());
	} else {
		$planificationLinesNDB->setTimetableSUN($firstTimetable);
	}
	$planificationLinesNDB->setActivateSUN($line_SUN->getActive());

    $form = $this->createForm(PlanificationLinesNDBType::class, $planificationLinesNDB, array('current_file' => $userContext->getCurrentFile()));

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			$line_MON->setTimetable($planificationLinesNDB->getTimetableMON());
			$line_MON->setActive($planificationLinesNDB->getActivateMON());
			$em->persist($line_MON);
			$line_TUE->setTimetable($planificationLinesNDB->getTimetableTUE());
			$line_TUE->setActive($planificationLinesNDB->getActivateTUE());
			$em->persist($line_TUE);
			$line_WED->setTimetable($planificationLinesNDB->getTimetableWED());
			$line_WED->setActive($planificationLinesNDB->getActivateWED());
			$em->persist($line_WED);
			$line_THU->setTimetable($planificationLinesNDB->getTimetableTHU());
			$line_THU->setActive($planificationLinesNDB->getActivateTHU());
			$em->persist($line_THU);
			$line_FRI->setTimetable($planificationLinesNDB->getTimetableFRI());
			$line_FRI->setActive($planificationLinesNDB->getActivateFRI());
			$em->persist($line_FRI);
			$line_SAT->setTimetable($planificationLinesNDB->getTimetableSAT());
			$line_SAT->setActive($planificationLinesNDB->getActivateSAT());
			$em->persist($line_SAT);
			$line_SUN->setTimetable($planificationLinesNDB->getTimetableSUN());
			$line_SUN->setActive($planificationLinesNDB->getActivateSUN());
			$em->persist($line_SUN);
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'planification.line.updated.ok');
			return $this->redirectToRoute('planification_edit', array('planificationID' => $planification->getId(), 'planificationPeriodID' => $planificationPeriod->getId()));
		}
    }

	return $this->render('planification/line.html.twig',
		array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'form' => $form->createView()));
    }


    // Modification d'une planification
	/**
     * @Route("/planification/modify/{planificationID}/{planificationPeriodID}", name="planification_modify")
	 * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
	 * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
     */
	public function modify(Request $request, Planification $planification, PlanificationPeriod $planificationPeriod)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $form = $this->createForm(PlanificationType::class, $planification);

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'planification.updated.ok');
			return $this->redirectToRoute('planification_edit', array('planificationID' => $planification->getId(), 'planificationPeriodID' => $planificationPeriod->getId()));
		}
    }
    return $this->render('planification/modify.html.twig', array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'form' => $form->createView()));
    }
	

    // Suppression d'une planification
	/**
     * @Route("/planification/delete/{planificationID}/{planificationPeriodID}", name="planification_delete")
	 * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
	 * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
     */
	public function delete(Request $request, Planification $planification, PlanificationPeriod $planificationPeriod)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$em->remove($planification);
	$em->flush();
	$request->getSession()->getFlashBag()->add('notice', 'planification.deleted.ok');
	return $this->redirectToRoute('planification', array('page' => 1));
    }

  /**
     * @Route("/planification/periodcreate/{planificationID}/{planificationPeriodID}", name="planification_period_create")
     * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
     * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
     */
	public function period_create(Request $request, Planification $planification, PlanificationPeriod $planificationPeriod)
	{
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$blRepository = $em->getRepository(BookingLine::Class);
	$lastBookingLine = $blRepository->getLastPlanificationBookingLine($userContext->getCurrentFile(), $planification); 

	$ppCreateDate = new PlanificationPeriodCreateDate($lastBookingLine->getDate());
	$form = $this->createForm(PlanificationPeriodCreateDateType::class, $ppCreateDate);

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			// Recherche des lignes de planification de la période en cours
			$plRepository = $em->getRepository(PlanificationLine::Class);
			$planificationLines = $plRepository->getLines($planificationPeriod);

			// Recherche des ressources planifiées de la période en cours
			$prRepository = $em->getRepository(PlanificationResource::Class);
			$planificationResources = $prRepository->getResources($planificationPeriod);

			$lDate = $ppCreateDate->getDate();
			
			// Cloture de la période à la veille de la date choisie
			$previousDate = clone $lDate;
			$previousDate->sub(new \DateInterval('P1D'));
			$planificationPeriod->setEndDate($previousDate);
			
			// Création d'une nouvelle période
			$newPeriod = new PlanificationPeriod($connectedUser, $planification);
			$newPeriod->setBeginningDate($lDate);
			$em->persist($newPeriod);
			
			// Recopie des lignes de planification de la période en cours vers la nouvelle période
			foreach ($planificationLines as $previousPL) {
				$newPL = new PlanificationLine($connectedUser, $newPeriod, $previousPL->getWeekDay(), $previousPL->getOrder());
				$newPL->setActive($previousPL->getActive());
				if ($newPL->getActive()) {
					$newPL->setTimetable($previousPL->getTimetable());
				}
				$em->persist($newPL);
				
			}

			// Recopie des ressources planifiées de la période en cours vers la nouvelle période
			foreach ($planificationResources as $previousPR) {
				$newPR = new PlanificationResource($connectedUser, $newPeriod, $previousPR->getResource());
				$newPR->setOrder($previousPR->getOrder());
				$em->persist($newPR);
			}
  
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'planificationPeriod.created.ok');
			return $this->redirectToRoute('planification_edit', array('planificationID' => $planification->getId(), 'planificationPeriodID' => $newPeriod->getId()));
		}
    }
	return $this->render('planification/period.create.html.twig',
		array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod,
			'ppCreateDate' => $ppCreateDate, 'form' => $form->createView()));
    }

    // Suppression d'une période planification (uniquement la dernière si pas de réservations et au moins une période antérieure)
	/**
     * @Route("/planificationperiod/delete/{planificationID}/{planificationPeriodID}", name="planification_period_delete")
	 * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
	 * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
     */
	public function delete_period(Request $request, Planification $planification, PlanificationPeriod $planificationPeriod)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $ppRepository = $em->getRepository(PlanificationPeriod::Class);
	$previousPP = $ppRepository->getPreviousPlanificationPeriod($planification, $planificationPeriod->getID());
	
	$previousPP->setEndDateToNull();
	$em->persist($previousPP);

	$em->remove($planificationPeriod);
	$em->flush();
	$request->getSession()->getFlashBag()->add('notice', 'planificationPeriod.deleted.ok');
	return $this->redirectToRoute('planification_edit', array('planificationID' => $planification->getID(), 'planificationPeriodID' => $previousPP->getId()));
    }

    /**
     * @Route("/planification/bookinglist/{planificationID}/{planificationPeriodID}/{page}", name="planification_period_booking_list", requirements={"page"="\d+"})
     * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
     * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
     */
	public function booking_list(Planification $planification, PlanificationPeriod $planificationPeriod, $page)
	{
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$bRepository = $em->getRepository(Booking::Class);
	$numberRecords = $bRepository->getPlanificationPeriodBookingsCount($userContext->getCurrentFile(), $planification, $planificationPeriod);

    $listContext = new ListContext($em, $connectedUser, 'booking', 'booking', $page, $numberRecords);
    $listBookings = $bRepository->getPlanificationPeriodBookings($userContext->getCurrentFile(), $planification, $planificationPeriod, $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());

	$planning_path = 'planning_one'; // La route du planning est "one" ou "many" selon le nombre de planifications actives à la date du jour
	$numberPlanifications = PlanningApi::getNumberOfPlanifications($em, $userContext->getCurrentFile());
	if ($numberPlanifications > 1) {
		$planning_path = 'planning_many';
	}
	return $this->render('planification/booking.list.html.twig',
		array('userContext' => $userContext, 'listContext' => $listContext, 'planification' => $planification,
		'planificationPeriod' => $planificationPeriod, 'listBookings' => $listBookings, 'planning_path' => $planning_path));
    }

	// Met à jour le nombre de lignes et colonnes d'affichage des listes
	/**
     * @Route("/planification/numberLinesColumns/{planificationID}/{planificationPeriodID}/{page}", name="planification_period_number_lines_and_columns", requirements={"page"="\d+"})
     * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
     * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
     */
	public function number_lines_and_columns(Request $request, Planification $planification, PlanificationPeriod $planificationPeriod, $page)
	{
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();

	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$numberLines = AdministrationApi::getNumberLines($em, $connectedUser, 'booking');
	$numberColumns = AdministrationApi::getNumberColumns($em, $connectedUser, 'booking');

	$upRepository = $em->getRepository(UserParameter::Class);
	$userParameterNLC = new UserParameterNLC($numberLines, $numberColumns);
	$form = $this->createForm(UserParameterNLCType::class, $userParameterNLC);

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));

		if ($form->isSubmitted() && $form->isValid()) {

			AdministrationApi::setNumberLines($em, $connectedUser, 'booking', $userParameterNLC->getNumberLines());
			AdministrationApi::setNumberColumns($em, $connectedUser, 'booking', $userParameterNLC->getNumberColumns());
			$request->getSession()->getFlashBag()->add('notice', 'number.lines.columns.updated.ok');
			return $this->redirectToRoute('planification_period_booking_list',
		array('planificationID' => $planification->getId(), 'planificationPeriodID' => $planificationPeriod->getId(), 'page' => 1));
		}
	}

	return $this->render('planification/number.lines.and.columns.html.twig',
	array('userContext' => $userContext, 'planification' => $planification, 'planificationPeriod' => $planificationPeriod, 'page' => $page, 'form' => $form->createView()));
	}
}
