<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use App\Entity\UserContext;
use App\Entity\ListContext;
use App\Entity\Constants;
use App\Entity\ResourceClassification;
use App\Entity\Resource;
use App\Entity\File;
use App\Entity\ResourceClassificationNDB;
use App\Entity\ResourceContext;

use App\Form\ResourceType;
use App\Form\ResourceAddType;

class ResourceController extends Controller
{
	/**
	 * @Route("/resource/{page}", name="resource", requirements={"page"="\d+"})
	 */
    public function index($page)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
 
    $rRepository = $em->getRepository(Resource::Class);
    $numberRecords = $rRepository->getResourcesCount($userContext->getCurrentFile());

    $listContext = new ListContext($em, $connectedUser, 'resource', 'resource', $page, $numberRecords);

    $listResources = $rRepository->getDisplayedResources($userContext->getCurrentFile(), $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());
    
	return $this->render('resource/index.html.twig', array('userContext' => $userContext, 'listContext' => $listContext, 'listResources' => $listResources));
    }


    /**
     * @Route("/resource/classification", name="resource_classification")
     */
    public function classification(Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $rcRepository = $em->getRepository(ResourceClassification::Class);

	$activeRC = array();
	foreach (Constants::DISPLAYED_RESOURCE_TYPE as $resourceType) {

		$defaultActiveRC = Constants::RESOURCE_CLASSIFICATION_ACTIVE[$resourceType]; // Classifications actives par défaut
		$activeInternalRC_DB = $rcRepository->getInternalResourceClassificationCodes($userContext->getCurrentFile(), $resourceType, 1); // Classifications internes actives (lues en BD)
		$unactiveInternalRC_DB = $rcRepository->getInternalResourceClassificationCodes($userContext->getCurrentFile(), $resourceType, 0); // Classifications internes inactives (lues en BD)

		foreach (Constants::RESOURCE_CLASSIFICATION[$resourceType] as $resourceClassificationCode) {
			if ((in_array($resourceClassificationCode, $defaultActiveRC) || in_array($resourceClassificationCode, $activeInternalRC_DB))
				&& !in_array($resourceClassificationCode, $unactiveInternalRC_DB))
			{
			$resourceClassificationNDB = new ResourceClassificationNDB();
			$resourceClassificationNDB->setInternal(1);
			$resourceClassificationNDB->setType($resourceType);
			$resourceClassificationNDB->setCode($resourceClassificationCode);
			array_push($activeRC, $resourceClassificationNDB);
			}
		}

		$activeExternalRC = $rcRepository->getActiveExternalResourceClassifications($userContext->getCurrentFile(), $resourceType);
		foreach ($activeExternalRC as $resourceClassification) {
			$resourceClassificationNDB = new ResourceClassificationNDB();
			$resourceClassificationNDB->setInternal(0);
			$resourceClassificationNDB->setType($resourceType);
			$resourceClassificationNDB->setId($resourceClassification->getId());
			$resourceClassificationNDB->setName($resourceClassification->getName());
			array_push($activeRC, $resourceClassificationNDB);
		}
	}

	return $this->render('resource/classification.html.twig', array('userContext' => $userContext, 'activeRC' => $activeRC));
    }


	/**
     * @Route("/resource/addinternal/{type}/{code}", name="resource_add_internal")
     */
    public function add_internal(Request $request, $type, $code)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	
	$resource = new Resource($connectedUser, $userContext->getCurrentFile());
	$resource->setInternal(true);
	$resource->setType($type);
	$resource->setCode($code);
	$form = $this->createForm(ResourceAddType::class, $resource);

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));

		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($resource);
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'resource.created.ok');

			if ($form->get('validateAndCreate')->isClicked()) {
				return $this->redirectToRoute('resource_add_internal', array('type' => $type, 'code' => $code));
			} else {
				return $this->redirectToRoute('resource', array('page' => 1));
			}
		}
    }

	return $this->render('resource/add.html.twig', array('userContext' => $userContext, 'resourceClassification' => null, 'resource' => $resource, 'form' => $form->createView()));
    }


	/**
     * @Route("/resource/addexternal/{type}/{resourceClassificationID}", name="resource_add_external")
     * @ParamConverter("resourceClassification", options={"mapping": {"resourceClassificationID": "id"}})
     */
    public function add_external(Request $request, $type, \App\Entity\ResourceClassification $resourceClassification)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	
	$resource = new Resource($connectedUser, $userContext->getCurrentFile());
	$resource->setInternal(false);
	$resource->setType($type);
	$resource->setClassification($resourceClassification);
	$form = $this->createForm(ResourceAddType::class, $resource);

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));

		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($resource);
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'resource.created.ok');

			if ($form->get('validateAndCreate')->isClicked()) {
				return $this->redirectToRoute('resource_add_external', array('type' => $type, 'resourceClassificationID' => $resourceClassification->getId()));
			} else {
				return $this->redirectToRoute('resource', array('page' => 1));
			}
		}
    }

	return $this->render('resource/add.html.twig', array('userContext' => $userContext, 'resourceClassification' => $resourceClassification, 'resource' => $resource, 'form' => $form->createView()));
    }


	/**
     * @Route("/resource/edit/{resourceID}", name="resource_edit")
     * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
     */
	public function edit(Request $request, \App\Entity\Resource $resource)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$resourceContext = new ResourceContext($em, $resource); // contexte ressource

	return $this->render('resource/edit.html.twig', array('userContext' => $userContext, 'resource' => $resource, 'resourceContext' => $resourceContext));
    }


	// Modification d'une ressource
	/**
     * @Route("/resource/modify/{resourceID}", name="resource_modify")
     * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
     */
    public function modify(Request $request, \App\Entity\Resource $resource)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $form = $this->createForm(ResourceType::class, $resource);

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'resource.updated.ok');
			return $this->redirectToRoute('resource_edit', array('resourceID' => $resource->getId()));
		}
    }

	return $this->render('resource/modify.html.twig', array('userContext' => $userContext, 'resource' => $resource, 'form' => $form->createView()));
    }


    // Suppression d'une ressource
	/**
     * @Route("/resource/delete/{resourceID}", name="resource_delete")
     * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
     */
    public function delete(Request $request, \App\Entity\Resource $resource)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$form = $this->get('form.factory')->create();

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			$em->remove($resource);
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'resource.deleted.ok');
			return $this->redirectToRoute('resource', array('page' => 1));
		}
    }

	return $this->render('resource/delete.html.twig', array('userContext' => $userContext, 'resource' => $resource, 'form' => $form->createView()));
    }


	/**
     * @Route("/resource/foreign/{resourceID}", name="resource_foreign")
     * @ParamConverter("resource", options={"mapping": {"resourceID": "id"}})
     */
    public function foreign(Request $request, \App\Entity\Resource $resource)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	return $this->render('resource/foreign.html.twig', array('userContext' => $userContext, 'resource' => $resource));
    }
}
