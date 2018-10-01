<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use App\Entity\UserParameter;
use App\Entity\UserContext;
use App\Entity\ListContext;
use App\Entity\Timetable;
use App\Entity\TimetableLine;
use App\Entity\Constants;
use App\Entity\TimetableContext;
use App\Entity\Planification;

use App\Form\TimetableType;
use App\Form\TimetableLineType;
use App\Form\TimetableLineAddType;

class TimetableController extends Controller
{
    /**
     * @Route("/timetable/{page}", name="timetable", requirements={"page"="\d+"})
     */
    public function index($page)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
    $tRepository = $em->getRepository(Timetable::Class);
    $numberRecords = $tRepository->getTimetablesCount($userContext->getCurrentFile());

    $listContext = new ListContext($em, $connectedUser, 'timetable', 'time', $page, $numberRecords);

    $listTimetables = $tRepository->getDisplayedTimetables($userContext->getCurrentFile(), $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());
    
	return $this->render('timetable/index.html.twig', array('userContext' => $userContext, 'listContext' => $listContext, 'listTimetables' => $listTimetables));
    }
	
    /**
     * @Route("/timetable/add", name="timetable_add")
     */
    public function add(Request $request)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	
	$timetable = new Timetable($connectedUser, $userContext->getCurrentFile());
	$timetable->setType("T");

	$form = $this->createForm(TimetableType::class, $timetable);
	
	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));

		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($timetable);
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'timetable.created.ok');
			return $this->redirectToRoute('timetable_add_line', array('timetableID' => $timetable->getID()));
		}
    }

	return $this->render('timetable/add.html.twig', array('userContext' => $userContext, 'form' => $form->createView()));
    }

    /**
     * @Route("/timetable/edit/{timetableID}", name="timetable_edit")
     * @ParamConverter("timetable", options={"mapping": {"timetableID": "id"}})
     */
    public function edit(Request $request, Timetable $timetable)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $tlRepository = $em->getRepository(TimetableLine::Class);
    $listTimetableLines = $tlRepository->getTimetableLines($timetable);

    $timetableContext = new TimetableContext($em, $userContext->getCurrentFile(), $timetable); // contexte grille horaire

	return $this->render('timetable/edit.html.twig', 
		array('userContext' => $userContext, 'timetable' => $timetable, 'listTimetableLines' => $listTimetableLines, 'timetableContext' => $timetableContext));
    }

	// Modification d'un dossier
	/**
     * @Route("/timetable/modify/{timetableID}", name="timetable_modify")
     * @ParamConverter("timetable", options={"mapping": {"timetableID": "id"}})
     */
    public function modify(Request $request, Timetable $timetable)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $form = $this->createForm(TimetableType::class, $timetable);

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'timetable.updated.ok');
			return $this->redirectToRoute('timetable_edit', array('timetableID' => $timetable->getId()));
		}
    }
	return $this->render('timetable/modify.html.twig', array('userContext' => $userContext, 'timetable' => $timetable, 'form' => $form->createView()));
    }

    // Suppression d'un dossier
	/**
     * @Route("/timetable/delete/{timetableID}", name="timetable_delete")
     * @ParamConverter("timetable", options={"mapping": {"timetableID": "id"}})
     */
    public function delete(Request $request, Timetable $timetable)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$form = $this->get('form.factory')->create();

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			$em->remove($timetable);
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'timetable.deleted.ok');
			return $this->redirectToRoute('timetable', array('page' => 1));
		}
    }
	return $this->render('timetable/delete.html.twig', array('userContext' => $userContext, 'timetable' => $timetable, 'form' => $form->createView()));
    }
	
    // Affichage des periodes de planification d'une grille horaire (message de suppression)
	/**
     * @Route("/timetable/foreigndelete/{timetableID}", name="timetable_foreign_delete")
     * @ParamConverter("timetable", options={"mapping": {"timetableID": "id"}})
     */
    public function foreign_delete(Request $request, Timetable $timetable)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $pRepository = $em->getRepository(Planification::Class);
    $listPlanifications = $pRepository->getTimetablePlanificationsList($userContext->getCurrentFile(), $timetable);

	return $this->render('timetable/foreign.delete.html.twig', array('userContext' => $userContext, 'timetable' => $timetable, 'listPlanifications' => $listPlanifications));
    }

	
    // Affichage des periodes de planification d'une grille horaire (message de modification)
	/**
     * @Route("/timetable/foreignupdate/{timetableID}", name="timetable_foreign_update")
     * @ParamConverter("timetable", options={"mapping": {"timetableID": "id"}})
     */
    public function foreign_update(Request $request, Timetable $timetable)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $pRepository = $em->getRepository(Planification::Class);
    $listPlanifications = $pRepository->getTimetablePlanificationsList($userContext->getCurrentFile(), $timetable);

	return $this->render('timetable/foreign.update.html.twig', array('userContext' => $userContext, 'timetable' => $timetable, 'listPlanifications' => $listPlanifications));
    }
	
	// Ajout d'un creneau horaire
	/**
     * @Route("/timetable/addline/{timetableID}", name="timetable_add_line")
     * @ParamConverter("timetable", options={"mapping": {"timetableID": "id"}})
     */
    public function add_line(Request $request, Timetable $timetable)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $tlRepository = $em->getRepository(TimetableLine::Class);
    $listLastTimetableLines = $tlRepository->getLastTimetableLines($timetable, Constants::NUMBER_LINES_BEFORE_AFTER_UPDATE);

    $timetableLine = new TimetableLine($connectedUser, $timetable);
	$timetableLine->setType("T");

    if (count($listLastTimetableLines) > 0) { // On initialise la date de début avec la date de fin du dernier créneau
		$timetableLine->setBeginningTime(current($listLastTimetableLines)->getEndTime());
	}

    $form = $this->createForm(TimetableLineAddType::class, $timetableLine);

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($timetableLine);
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'timetableLine.created.ok');
		
			if ($form->get('validateAndCreate')->isClicked()) {
				return $this->redirectToRoute('timetable_add_line', array('timetableID' => $timetable->getID()));
			} else {
				return $this->redirectToRoute('timetable_edit', array('timetableID' => $timetable->getID()));
			}
		}
    }
	return $this->render('timetable/addline.html.twig', array('userContext' => $userContext, 'timetable' => $timetable, 'listLastTimetableLines' => $listLastTimetableLines, 'form' => $form->createView()));
    }

	// Modification d'un creneau horaire
	/**
     * @Route("/timetable/modifyline/{timetableID}/{timetableLineID}", name="timetable_modify_line")
     * @ParamConverter("timetable", options={"mapping": {"timetableID": "id"}})
	 * @ParamConverter("timetableLine", options={"mapping": {"timetableLineID": "id"}})
     */
    public function modify_line(Request $request, Timetable $timetable, TimetableLine $timetableLine)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $tlRepository = $em->getRepository(TimetableLine::Class);

	$listPreviousTimetableLines = $tlRepository->getSomeTimetableLines($timetable, $timetableLine->getId(), Constants::NUMBER_LINES_BEFORE_AFTER_UPDATE, true);
	$listNextTimetableLines = $tlRepository->getSomeTimetableLines($timetable, $timetableLine->getId(), Constants::NUMBER_LINES_BEFORE_AFTER_UPDATE, false);
	
    $form = $this->createForm(TimetableLineType::class, $timetableLine);

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'timetableLine.updated.ok');
			return $this->redirectToRoute('timetable_edit', array('timetableID' => $timetable->getID()));
		}
    }
	return $this->render('timetable/modifyline.html.twig', array('userContext' => $userContext, 'timetable' => $timetable, 'timetableLine' => $timetableLine,
		'listPreviousTimetableLines' => $listPreviousTimetableLines, 'listNextTimetableLines' => $listNextTimetableLines, 'form' => $form->createView()));
    }

	// Suppression d'un creneau horaire
	/**
     * @Route("/timetable/deleteline/{timetableID}/{timetableLineID}", name="timetable_delete_line")
	 * @ParamConverter("timetableLine", options={"mapping": {"timetableLineID": "id"}})
     */
    public function delete_line(Request $request, $timetableID, TimetableLine $timetableLine)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $em->remove($timetableLine);
    $em->flush();
    $request->getSession()->getFlashBag()->add('notice', 'timetableLine.deleted.ok');

	return $this->redirectToRoute('timetable_edit', array('timetableID' => $timetableID));
	}
}
