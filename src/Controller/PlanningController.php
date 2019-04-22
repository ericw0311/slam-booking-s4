<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use Psr\Log\LoggerInterface;

use App\Entity\Constants;
use App\Entity\UserContext;
use App\Entity\ListContext;
use App\Entity\UserParameter;
use App\Entity\UserParameterNLC;
use App\Entity\BookingPeriod;
use App\Entity\PlanningContext;
use App\Entity\Planification;
use App\Entity\PlanificationPeriod;
use App\Entity\PlanificationResource;
use App\Entity\PlanificationLine;
use App\Entity\TimetableLine;
use App\Entity\Booking;
use App\Entity\Ddate;
use App\Form\DdateType;
use App\Form\UserParameterNLCType;
use App\Api\AdministrationApi;
use App\Api\PlanningApi;
use App\Api\BookingApi;

class PlanningController extends Controller
{
    /**
     * @Route("/planning/access", name="planning")
     */
	public function access(LoggerInterface $logger)
	{
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$pRepository = $em->getRepository(Planification::Class);
	$currentDate = date("Ymd");
	$logger->info('PlanningController.access DBG 1 _'.$currentDate.'_');
    $planifications = $pRepository->getPlanningPlanifications($userContext->getCurrentFile(), new \DateTime());
	// Aucune planification
	if (count($planifications) <= 0) {
		return $this->redirectToRoute('planning_no_planification');
    }

	// Acces au planning d'une planification
	if (count($planifications) >= constant(Constants::class.'::PLANNING_MIN_NUMBER_PLANIFICATION_LIST')) { // Via la liste
		return $this->redirectToRoute('planning_list', array('page' => 1));
	} else if (count($planifications) > 1) { // Planification unique
		return $this->redirectToRoute('planning_many_pp', array('planificationID' => $planifications[0]['ID'], 'planificationPeriodID' => $planifications[0]['planificationPeriodID'], 'date' => $currentDate));
	} else {
		return $this->redirectToRoute('planning_one_pp', array('planificationID' => $planifications[0]['ID'], 'planificationPeriodID' => $planifications[0]['planificationPeriodID'], 'date' => $currentDate));
	}
	}

	// Affichage des planifications du dossier en cours
    /**
     * @Route("/planning/{page}", name="planning_list", requirements={"page"="\d+"})
     */
	public function index($page)
	{
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$currentDate = new \DateTime();

	$pRepository = $em->getRepository(Planification::Class);
    $numberRecords = $pRepository->getPlanningPlanificationsCount($userContext->getCurrentFile(), new \DateTime());
	
	$listContext = new ListContext($em, $connectedUser, 'planning', 'planning', $page, $numberRecords);
    $listPlanifications = $pRepository->getPlanningPlanifications($userContext->getCurrentFile(), new \DateTime());

	return $this->render('planning/index.html.twig', 
	array('userContext' => $userContext, 'listContext' => $listContext, 'listPlanifications' => $listPlanifications, 'date' => $currentDate));
    }


    /**
     * @Route("/planning/noplanification", name="planning_no_planification")
     */
    public function no_planification()
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	return $this->render('planning/no.planification.html.twig', array('userContext' => $userContext));
	}

    /**
     * @Route("/planning/allbookinglist/{page}", name="planning_all_booking_list", requirements={"page"="\d+"})
     */
	public function all_booking_list($page)
	{
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $bRepository = $em->getRepository(Booking::Class);
    $numberRecords = $bRepository->getAllBookingsCount($userContext->getCurrentFile());
    $listContext = new ListContext($em, $connectedUser, 'booking', 'booking', $page, $numberRecords);
    $listBookings = $bRepository->getAllBookings($userContext->getCurrentFile(), $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());
                
	$planning_path = 'planning_one'; // La route du planning est "one" ou "many" selon le nombre de planifications actives à la date du jour
	$numberPlanifications = PlanningApi::getNumberOfPlanifications($em, $userContext->getCurrentFile());
	if ($numberPlanifications > 1) {
		$planning_path = 'planning_many';
	}
	return $this->render('planning/booking.list.html.twig',
		array('userContext' => $userContext, 'listContext' => $listContext, 'listBookings' => $listBookings, 'list_path' => 'planning_all_booking_list', 'planning_path' => $planning_path));
    }

    /**
     * @Route("/planning/currentuserbookinglist/{page}", name="planning_current_user_booking_list", requirements={"page"="\d+"})
     */
	public function current_user_booking_list($page)
	{
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $bRepository = $em->getRepository(Booking::Class);
    $numberRecords = $bRepository->getUserFileBookingsCount($userContext->getCurrentFile(), $userContext->getCurrentUserFile());
    $listContext = new ListContext($em, $connectedUser, 'booking', 'booking', $page, $numberRecords);
    $listBookings = $bRepository->getUserFileBookings($userContext->getCurrentFile(), $userContext->getCurrentUserFile(), $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());

	$planning_path = 'planning_one'; // La route du planning est "one" ou "many" selon le nombre de planifications actives à la date du jour
	$numberPlanifications = PlanningApi::getNumberOfPlanifications($em, $userContext->getCurrentFile());
	if ($numberPlanifications > 1) {
		$planning_path = 'planning_many';
	}
	return $this->render('planning/booking.list.html.twig',
		array('userContext' => $userContext, 'listContext' => $listContext, 'listBookings' => $listBookings, 'list_path' => 'planning_current_user_booking_list', 'planning_path' => $planning_path));
    }

    /**
     * @Route("/planning/inprogressbookinglist/{page}", name="planning_in_progress_booking_list", requirements={"page"="\d+"})
     */
	public function in_progress_booking_list($page)
	{
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $bRepository = $em->getRepository(Booking::Class);
    $numberRecords = $bRepository->getFromDatetimeBookingsCount($userContext->getCurrentFile(), new \DateTime());
    $listContext = new ListContext($em, $connectedUser, 'booking', 'booking', $page, $numberRecords);
    $listBookings = $bRepository->getFromDatetimeBookings($userContext->getCurrentFile(), new \DateTime(), $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());
                
	$planning_path = 'planning_one'; // La route du planning est "one" ou "many" selon le nombre de planifications actives à la date du jour
	$numberPlanifications = PlanningApi::getNumberOfPlanifications($em, $userContext->getCurrentFile());
	if ($numberPlanifications > 1) {
		$planning_path = 'planning_many';
	}

	return $this->render('planning/booking.list.html.twig',
		array('userContext' => $userContext, 'listContext' => $listContext, 'listBookings' => $listBookings, 'list_path' => 'planning_in_progress_booking_list', 'planning_path' => $planning_path));
    }

    /**
     * @Route("/planning/currentuserinprogressbookinglist/{page}", name="planning_current_user_in_progress_booking_list", requirements={"page"="\d+"})
     */
	public function current_user_in_progress_booking_list($page)
	{
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $bRepository = $em->getRepository(Booking::Class);
    $numberRecords = $bRepository->getUserFileFromDatetimeBookingsCount($userContext->getCurrentFile(), $userContext->getCurrentUserFile(), new \DateTime());
    $listContext = new ListContext($em, $connectedUser, 'booking', 'booking', $page, $numberRecords);
    $listBookings = $bRepository->getUserFileFromDatetimeBookings($userContext->getCurrentFile(), $userContext->getCurrentUserFile(), new \DateTime(), $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());
                
	$planning_path = 'planning_one'; // La route du planning est "one" ou "many" selon le nombre de planifications actives à la date du jour
	$numberPlanifications = PlanningApi::getNumberOfPlanifications($em, $userContext->getCurrentFile());
	if ($numberPlanifications > 1) {
		$planning_path = 'planning_many';
	}

	return $this->render('planning/booking.list.html.twig',
		array('userContext' => $userContext, 'listContext' => $listContext, 'listBookings' => $listBookings, 'list_path' => 'planning_current_user_in_progress_booking_list', 'planning_path' => $planning_path));
    }

    // Affichage de la grille horaire journaliere pour plusieurs planifications
    /**
     * @Route("/planning/many/{planificationID}/{date}", name="planning_many")
	 * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
	 * @ParamConverter("date", options={"format": "Ymd"})
     */
    public function many(Request $request, LoggerInterface $logger, Planification $planification, \Datetime $date)
    {
	return PlanningController::planning($request, $logger, $planification, $date, 1);
    }

    // Affichage de la grille horaire journaliere d'une planification
    /**
     * @Route("/planning/one/{planificationID}/{date}", name="planning_one")
	 * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
	 * @ParamConverter("date", options={"format": "Ymd"})
	*/
    public function one(Request $request, LoggerInterface $logger, Planification $planification, \Datetime $date)
    {
	return PlanningController::planning($request, $logger, $planification, $date, 0);
    }

	// Affichage de la grille horaire journaliere d'une planification (la période de planification n'est pas passée, elle est déterminée)
    public function planning(Request $request, LoggerInterface $logger, Planification $planification, \Datetime $date, $many)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$logger->info('PlanningController.planning DBG 1');
	$lDate = $date;

    $ddate = new Ddate();
    $form = $this->createForm(DdateType::class, $ddate);
	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			$lDate = $ddate->getDate();
		}
    }

	$logger->info('PlanningController.planning DBG 2 _'.$lDate->format('Y-m-d H:i:s').'_');
	$lDate = new \DateTime($lDate->format('Y-m-d')); // On ignor la partie heures-minutes-secondes
	$logger->info('PlanningController.planning DBG 3 _'.$lDate->format('Y-m-d H:i:s').'_');

    $pRepository = $em->getRepository(Planification::Class);
    $planifications = $pRepository->getPlanningPlanifications($userContext->getCurrentFile(), $lDate);
	if (count($planifications) <= 0) {
		return $this->redirectToRoute('planning_no_planification');
    }
	// 1) Si la planification passée n'est pas trouvée dans la liste des planifications, on prend la première de la liste
	// 2) On initialise la période planification
	$planificationFound = false;
	$planificationPeriodID = 0;
	foreach ($planifications as $i_planification) {
		if ($i_planification['ID'] == $planification->getID()) { $planificationFound = true; $planificationPeriodID = $i_planification['planificationPeriodID']; break; } // La planification en cours est dans la liste des planifications ouvertes à la date en cours.
	}
    $ppRepository = $em->getRepository(PlanificationPeriod::Class);
	if ($planificationFound) {
		$lPlanification = $planification;
		$planificationPeriod = $ppRepository->find($planificationPeriodID);
	} else {
		$lPlanification = $pRepository->find($planifications[0]['ID']);
		$planificationPeriod = $ppRepository->find($planifications[0]['planificationPeriodID']);
	}
	$previousDate = clone $lDate;
	$previousDate->sub(new \DateInterval('P1D'));
	$nextDate = clone $lDate;
	$nextDate->add(new \DateInterval('P1D'));

	$bookingPeriod = new BookingPeriod($em, $userContext, $planificationPeriod); // période de réservation

    $planningContext = new PlanningContext($logger, $em, $connectedUser, $userContext->getCurrentFile(), $bookingPeriod, 'P', $lDate, $lDate, 1);

    $prRepository = $em->getRepository(PlanificationResource::Class);
    $planificationResources = $prRepository->getResources($planificationPeriod);

	$bookings = BookingApi::getPlanningBookings($em, $userContext->getCurrentFile(), $lDate, $planningContext->getLastDate(1), $lPlanification, $planificationPeriod, $userContext->getCurrentUserFile());
	
	$listAcces = (count($planifications) >= constant(Constants::class.'::PLANNING_MIN_NUMBER_PLANIFICATION_LIST')); // Accès au planning via la liste des planifications

	return $this->render('planning/'.($many ? 'many' : 'one').'.html.twig',
		array('userContext' => $userContext, 'planningContext' => $planningContext, 'bookingPeriod' => $bookingPeriod,
			'planification' => $lPlanification, 'planificationPeriod' => $planificationPeriod,
			'planifications' => $planifications, 'planificationResources' => $planificationResources,
		 'date' => $lDate, 'nextDate' => $nextDate, 'previousDate' => $previousDate, 'bookings' => $bookings,
         'list_acces' => $listAcces, 'form' => $form->createView()));
    }

    // Affichage du planning pour plusieurs planifications (periode de planification connue)
    /**
     * @Route("/planning/manypp/{planificationID}/{planificationPeriodID}/{date}", name="planning_many_pp")
	 * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
	 * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
	 * @ParamConverter("date", options={"format": "Ymd"})
     */
    public function many_pp(Request $request, LoggerInterface $logger, Planification $planification, PlanificationPeriod $planificationPeriod, \Datetime $date)
    {
	return PlanningController::planning_pp($request, $logger, $planification, $planificationPeriod, $date, 1);
    }

    // Affichage du planning pour une planification (periode de planification connue)
    /**
     * @Route("/planning/onepp/{planificationID}/{planificationPeriodID}/{date}", name="planning_one_pp")
	 * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
	 * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
	 * @ParamConverter("date", options={"format": "Ymd"})
     */
    public function one_pp(Request $request, LoggerInterface $logger, Planification $planification, PlanificationPeriod $planificationPeriod, \Datetime $date)
    {
	return PlanningController::planning_pp($request, $logger, $planification, $planificationPeriod, $date, 0);
    }

	// Affichage du planning (periode de planification connue)
	public function planning_pp(Request $request, LoggerInterface $logger, Planification $planification, PlanificationPeriod $planificationPeriod, \Datetime $date, $many)
	{
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$logger->info('PlanningController.planning_pp DBG 1');

	$ddate = new Ddate();
	$form = $this->createForm(DdateType::class, $ddate);
    
	if ($request->isMethod('POST')) { // Si l'utilisateur change de date, on ré oriente vers la route qui recherche la période de planification
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			return $this->redirectToRoute('planning_'.($many ? 'many' : 'one'),
				array('planificationID' => $planification->getID(), 'date' => $ddate->getDate()->format("Ymd")));
		}
    }

	$logger->info('PlanningController.planning_pp DBG 2 _'.$date->format('Y-m-d H:i:s').'_');

	$lDate = new \DateTime($date->format('Y-m-d')); // lDate permet d'ignorer la partie heures-minutes-secondes
	$logger->info('PlanningController.planning_pp DBG 3 _'.$lDate->format('Y-m-d H:i:s').'_');

	$pRepository = $em->getRepository(Planification::Class);
    $planifications = $pRepository->getPlanningPlanifications($userContext->getCurrentFile(), $lDate);
	$previousDate = clone $lDate;
	$previousDate->sub(new \DateInterval('P1D'));
	$nextDate = clone $lDate;
	$nextDate->add(new \DateInterval('P1D'));

	$bookingPeriod = new BookingPeriod($em, $userContext, $planificationPeriod); // période de réservation

    $planningContext = new PlanningContext($logger, $em, $connectedUser, $userContext->getCurrentFile(), $bookingPeriod, 'P', $lDate, $lDate, 1);

    $prRepository = $em->getRepository(PlanificationResource::Class);
    $planificationResources = $prRepository->getResources($planificationPeriod);

	$bookings = BookingApi::getPlanningBookings($em, $userContext->getCurrentFile(), $lDate, $planningContext->getLastDate(1), $planification, $planificationPeriod, $userContext->getCurrentUserFile());

	$listAcces = (count($planifications) >= constant(Constants::class.'::PLANNING_MIN_NUMBER_PLANIFICATION_LIST'));

	return $this->render('planning/'.($many ? 'many' : 'one').'.html.twig',
		array('userContext' => $userContext, 'planningContext' => $planningContext, 'bookingPeriod' => $bookingPeriod,
			'planification' => $planification, 'planificationPeriod' => $planificationPeriod,
			'planifications' => $planifications, 'planificationResources' => $planificationResources,
			'date' => $lDate, 'nextDate' => $nextDate, 'previousDate' => $previousDate, 'bookings' => $bookings,
			'list_acces' => $listAcces, 'form' => $form->createView()));
    }

	// Mise à jour du nombre de lignes pour plusieurs planifications
    /**
     * @Route("/planning/manysetnumberlines/{planificationID}/{planificationPeriodID}/{date}/{numberLines}", name="planning_many_set_number_lines", requirements={"numberLines"="\d+"})
	 * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
	 * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
	 * @ParamConverter("date", options={"format": "Ymd"})
     */
    public function many_set_number_lines(Request $request, Planification $planification, PlanificationPeriod $planificationPeriod, \Datetime $date, $numberLines)
    {
	return PlanningController::set_number_lines($request, $planification, $planificationPeriod, $date, $numberLines, 1);
    }

	// Mise à jour du nombre de lignes pour une planification
    /**
     * @Route("/planning/onesetnumberlines/{planificationID}/{planificationPeriodID}/{date}/{numberLines}", name="planning_one_set_number_lines", requirements={"numberLines"="\d+"})
	 * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
	 * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
	 * @ParamConverter("date", options={"format": "Ymd"})
     */
    public function one_set_number_lines(Request $request, Planification $planification, PlanificationPeriod $planificationPeriod, \Datetime $date, $numberLines)
    {
	return PlanningController::set_number_lines($request, $planification, $planificationPeriod, $date, $numberLines, 0);
    }

	// Mise à jour du nombre de lignes
	public function set_number_lines(Request $request, Planification $planification, PlanificationPeriod $planificationPeriod, \Datetime $date, $numberLines, $many)
	{
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	PlanningApi::setNumberLines($em, $connectedUser, $numberLines);

	return $this->redirectToRoute('planning_'.($many ? 'many' : 'one').'_pp',
		array('planificationID' => $planification->getID(), 'planificationPeriodID' => $planificationPeriod->getID(), 'date' => $date->format("Ymd")));
    }

	// Mise à jour du nombre de colonnes pour plusieurs planifications
    /**
     * @Route("/planning/manysetnumbercolumns/{planificationID}/{planificationPeriodID}/{date}/{numberColumns}", name="planning_many_set_number_columns", requirements={"numberColumns"="\d+"})
	 * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
	 * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
	 * @ParamConverter("date", options={"format": "Ymd"})
     */
    public function many_set_number_columns(Request $request, Planification $planification, PlanificationPeriod $planificationPeriod, \Datetime $date, $numberColumns)
    {
	return PlanningController::set_number_columns($request, $planification, $planificationPeriod, $date, $numberColumns, 1);
    }

	// Mise à jour du nombre de colonnes pour une planification
    /**
     * @Route("/planning/onesetnumbercolumns/{planificationID}/{planificationPeriodID}/{date}/{numberColumns}", name="planning_one_set_number_columns", requirements={"numberColumns"="\d+"})
	 * @ParamConverter("planification", options={"mapping": {"planificationID": "id"}})
	 * @ParamConverter("planificationPeriod", options={"mapping": {"planificationPeriodID": "id"}})
	 * @ParamConverter("date", options={"format": "Ymd"})
     */
    public function one_set_number_columns(Request $request, Planification $planification, PlanificationPeriod $planificationPeriod, \Datetime $date, $numberColumns)
    {
	return PlanningController::set_number_columns($request, $planification, $planificationPeriod, $date, $numberColumns, 0);
    }

	// Mise à jour du nombre de colonnes
	public function set_number_columns(Request $request, Planification $planification, PlanificationPeriod $planificationPeriod, \Datetime $date, $numberColumns, $many)
	{
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	PlanningApi::setNumberColumns($em, $connectedUser, $numberColumns);

	return $this->redirectToRoute('planning_'.($many ? 'many' : 'one').'_pp',
		array('planificationID' => $planification->getID(), 'planificationPeriodID' => $planificationPeriod->getID(), 'date' => $date->format("Ymd")));
    }

	// Met à jour le nombre de lignes et colonnes d'affichage des listes
	/**
     * @Route("/planning/numberLinesColumns/{list_path}/{page}", name="planning_number_lines_and_columns", requirements={"page"="\d+"})
     */
	public function number_lines_and_columns(Request $request, $list_path, $page)
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
			return $this->redirectToRoute($list_path, array('page' => 1));
		}
	}

	return $this->render('planning/number.lines.and.columns.html.twig',
	array('userContext' => $userContext, 'list_path' => $list_path, 'page' => $page, 'form' => $form->createView()));
	}
}
