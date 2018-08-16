<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use App\Entity\UserContext;
use App\Entity\ResourceClassification;

use App\Api\ResourceApi;

class ResourceClassificationController extends Controller
{
    /**
     * @Route("/resourceclassification/{resourceType}", name="resource_classification")
     */
    public function index($resourceType)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	// Classifications internes actives
	$activeInternalRC = ResourceApi::getActiveInternalResourceClassifications($em, $userContext->getCurrentFile(), $resourceType);

	// Nombre de ressources par classification interne
	$numberResourcesInternalRC =  ResourceApi::getInternalClassificationNumberResources($em, $userContext->getCurrentFile(), $resourceType);
    $RCRepository = $em->getRepository(ResourceClassification::Class);

	// Classifications externes
    $listExternalRC = $RCRepository->getExternalResourceClassifications($userContext->getCurrentFile(), $resourceType);

	// Nombre de ressources par classification externe
	$numberResourcesExternalRC =  ResourceApi::getExternalClassificationNumberResources($em, $userContext->getCurrentFile(), $resourceType, $listExternalRC);

	return $this->render('resource_classification/index.html.twig',
		array('userContext' => $userContext,
			'resourceType' => $resourceType,
			'activeInternalRC' => $activeInternalRC,
			'numberResourcesInternalRC' => $numberResourcesInternalRC,
			'listExternalRC' => $listExternalRC,
			'numberResourcesExternalRC' => $numberResourcesExternalRC));
    }

    /**
     * @Route("/resourceclassification/activateinternal/{resourceType}/{resourceClassificationCode}", name="resource_classification_activate_internal")
     */
    public function activate_internal($resourceType, $resourceClassificationCode)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	return $this->render('resource_classification/index.html.twig');
    }

    /**
     * @Route("/resourceclassification/unactivateinternal/{resourceType}/{resourceClassificationCode}", name="resource_classification_unactivate_internal")
     */
    public function unactivate_internal($resourceType, $resourceClassificationCode)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	return $this->render('resource_classification/index.html.twig');
    }

    /**
     * @Route("/resourceclassification/activateexternal/{resourceType}/{resourceClassificationID}", name="resource_classification_activate_external")
     * @ParamConverter("resourceClassification", options={"mapping": {"resourceClassificationID": "id"}})
     */
    public function activate_external($resourceType, $resourceClassificationID)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	return $this->render('resource_classification/index.html.twig');
    }

    /**
     * @Route("/resourceclassification/unactivateexternal/{resourceType}/{resourceClassificationID}", name="resource_classification_unactivate_external")
     * @ParamConverter("resourceClassification", options={"mapping": {"resourceClassificationID": "id"}})
     */
    public function unactivate_external($resourceType, $resourceClassificationID)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	return $this->render('resource_classification/index.html.twig');
    }

    /**
     * @Route("/resourceclassification/add/{resourceType}", name="resource_classification_add")
     */
    public function add($resourceType)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	return $this->render('resource_classification/index.html.twig');
    }

    /**
     * @Route("/resourceclassification/modify/{resourceType}/{resourceClassificationID}", name="resource_classification_modify")
     * @ParamConverter("resourceClassification", options={"mapping": {"resourceClassificationID": "id"}})
     */
    public function modify($resourceType, $resourceClassificationID)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	return $this->render('resource_classification/index.html.twig');
    }

    /**
     * @Route("/resourceclassification/delete/{resourceType}/{resourceClassificationID}", name="resource_classification_delete")
     * @ParamConverter("resourceClassification", options={"mapping": {"resourceClassificationID": "id"}})
     */
    public function delete($resourceType, $resourceClassificationID)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	return $this->render('resource_classification/index.html.twig');
    }

    /**
     * @Route("/resourceclassification/foreigninternal/{resourceType}/{resourceClassificationCode}", name="resource_classification_foreign_internal")
     */
    public function foreign_internal($resourceType, $resourceClassificationCode)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	return $this->render('resource_classification/index.html.twig');
    }

    /**
     * @Route("/resourceclassification/foreignexternal/{resourceType}/{resourceClassificationID}", name="resource_classification_foreign_external")
     * @ParamConverter("resourceClassification", options={"mapping": {"resourceClassificationID": "id"}})
     */
    public function foreign_external($resourceType, $resourceClassificationID)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	return $this->render('resource_classification/index.html.twig');
    }
}
