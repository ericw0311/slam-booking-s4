<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use App\Entity\Label;
use App\Entity\UserParameter;
use App\Entity\UserContext;
use App\Entity\ListContext;

use App\Form\LabelType;

class LabelController extends Controller
{
    /**
     * @Route("/label/{page}", name="label", requirements={"page"="\d+"})
     */
    public function index($page)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
    $lRepository = $em->getRepository(Label::Class);
    $numberRecords = $lRepository->getLabelsCount($userContext->getCurrentFile());
    $listContext = new ListContext($em, $connectedUser, 'label', 'label', $page, $numberRecords);

    $listLabels = $lRepository->getDisplayedLabels($userContext->getCurrentFile(), $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());
                
	return $this->render('label/index.html.twig', array('userContext' => $userContext, 'listContext' => $listContext, 'listLabels' => $listLabels));
    }

	// Ajout d'une étiquete
    /**
     * @Route("/label/add", name="label_add")
     */
    public function add(Request $request)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	$label = new Label($connectedUser, $userContext->getCurrentFile());
	$form = $this->createForm(LabelType::class, $label);

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($label);
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'label.created.ok');
			return $this->redirectToRoute('label', array('page' => 1));
		}
    }
	return $this->render('label/add.html.twig', array('userContext' => $userContext, 'form' => $form->createView()));
	}
	
    // Edition du detail d'une étiquete
    /**
     * @Route("/label/edit/{labelID}", name="label_edit")
     * @ParamConverter("label", options={"mapping": {"labelID": "id"}})
     */
    public function edit(Request $request, Label $label)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	return $this->render('label/edit.html.twig', array('userContext' => $userContext, 'label' => $label));
    }


    // Modification d'une étiquete
	/**
     * @Route("/label/modify/{labelID}", name="label_modify")
     * @ParamConverter("label", options={"mapping": {"labelID": "id"}})
     */
    public function modify(Request $request, Label $label)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
    $form = $this->createForm(LabelType::class, $label);
	
	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'label.updated.ok');
			return $this->redirectToRoute('label_edit', array('labelID' => $label->getId()));
		}
    }
	return $this->render('label/modify.html.twig', array('userContext' => $userContext, 'label' => $label, 'form' => $form->createView()));
    }

    // Suppression d'une étiquete
	/**
     * @Route("/label/delete/{labelID}", name="label_delete")
     * @ParamConverter("label", options={"mapping": {"labelID": "id"}})
     */
    public function delete(Request $request, Label $label)
    {
	$connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur
    $form = $this->get('form.factory')->create();

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			$em->remove($label);
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'label.deleted.ok');
			return $this->redirectToRoute('label', array('page' => 1));
		}
    }
	return $this->render('label/delete.html.twig', array('userContext' => $userContext, 'label' => $label, 'form' => $form->createView()));
    }
}
