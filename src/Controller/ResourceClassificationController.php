<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use App\Entity\UserContext;
use App\Entity\ResourceClassification;
use App\Entity\UserFile;
use App\Entity\Resource;

use App\Form\ResourceClassificationType;

use App\Api\ResourceApi;

class ResourceClassificationController extends Controller
{
    /**
     * @Route("/resourceclassification/{resourceType}", name="resource_classification_index")
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
    $rcRepository = $em->getRepository(ResourceClassification::Class);

	// Classifications externes
    $listExternalRC = $rcRepository->getExternalResourceClassifications($userContext->getCurrentFile(), $resourceType);

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
    public function activate_internal(Request $request, $resourceType, $resourceClassificationCode)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
 
	$rcRepository = $em->getRepository(ResourceClassification::Class);
	$resourceClassification = $rcRepository->findOneBy(array('file' => $userContext->getCurrentFile(), 'internal' => 1, 'type' => $resourceType, 'code' => $resourceClassificationCode));

	if ($resourceClassification === null) {
        $resourceClassification = new ResourceClassification($connectedUser, $userContext->getCurrentFile());
        $resourceClassification->setInternal(1);
        $resourceClassification->setType($resourceType);
        $resourceClassification->setCode($resourceClassificationCode);
        $resourceClassification->setName($resourceClassificationCode);
        $em->persist($resourceClassification);
        $resourceClassification->setActive(1);
	} else {
		$resourceClassification->setActive(1);
	}
	$em->flush();
    $request->getSession()->getFlashBag()->add('notice', 'resourceClassification.activated.ok');
    return $this->redirectToRoute('resource_classification_index', array('resourceType' => $resourceType));
    }

    /**
     * @Route("/resourceclassification/unactivateinternal/{resourceType}/{resourceClassificationCode}", name="resource_classification_unactivate_internal")
     */
    public function unactivate_internal(Request $request, $resourceType, $resourceClassificationCode)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$rcRepository = $em->getRepository(ResourceClassification::Class);
	$resourceClassification = $rcRepository->findOneBy(array('file' => $userContext->getCurrentFile(), 'internal' => 1, 'type' => $resourceType, 'code' => $resourceClassificationCode));
    
	if ($resourceClassification === null) {
        $resourceClassification = new ResourceClassification($connectedUser, $userContext->getCurrentFile());
        $resourceClassification->setInternal(1);
        $resourceClassification->setType($resourceType);
        $resourceClassification->setCode($resourceClassificationCode);
        $resourceClassification->setName($resourceClassificationCode);
        $em->persist($resourceClassification);
        $resourceClassification->setActive(0);
	} else {
		$resourceClassification->setActive(0);
	}
	$em->flush();
    $request->getSession()->getFlashBag()->add('notice', 'resourceClassification.unactivated.ok');
    return $this->redirectToRoute('resource_classification_index', array('resourceType' => $resourceType));
	}

    /**
     * @Route("/resourceclassification/activateexternal/{resourceType}/{resourceClassificationID}", name="resource_classification_activate_external")
     * @ParamConverter("resourceClassification", options={"mapping": {"resourceClassificationID": "id"}})
     */
    public function activate_external(Request $request, $resourceType, \App\Entity\ResourceClassification $resourceClassification)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$resourceClassification->setActive(1);
	$em->flush();
    $request->getSession()->getFlashBag()->add('notice', 'resourceClassification.activated.ok');
    
	return $this->redirectToRoute('resource_classification_index', array('resourceType' => $resourceType));
    }

    /**
     * @Route("/resourceclassification/unactivateexternal/{resourceType}/{resourceClassificationID}", name="resource_classification_unactivate_external")
     * @ParamConverter("resourceClassification", options={"mapping": {"resourceClassificationID": "id"}})
     */
    public function unactivate_external(Request $request, $resourceType, \App\Entity\ResourceClassification $resourceClassification)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$resourceClassification->setActive(0);
	$em->flush();
    $request->getSession()->getFlashBag()->add('notice', 'resourceClassification.unactivated.ok');
    return $this->redirectToRoute('resource_classification_index', array('resourceType' => $resourceType));
    }

    /**
     * @Route("/resourceclassification/add/{resourceType}", name="resource_classification_add")
     */
    public function add(Request $request, $resourceType)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$resourceClassification = new ResourceClassification($connectedUser, $userContext->getCurrentFile());
	$resourceClassification->setInternal(0);
	$resourceClassification->setType($resourceType);
	$resourceClassification->setActive(1);
	$form = $this->createForm(ResourceClassificationType::class, $resourceClassification);

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($resourceClassification);
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'resourceClassification.created.ok');
		return $this->redirectToRoute('resource_classification_index', array('resourceType' => $resourceType));
		}
    }

	return $this->render('resource_classification/add.html.twig',
		array('userContext' => $userContext, 'resourceType' => $resourceType, 'form' => $form->createView()));
    }

    /**
     * @Route("/resourceclassification/modify/{resourceType}/{resourceClassificationID}", name="resource_classification_modify")
     * @ParamConverter("resourceClassification", options={"mapping": {"resourceClassificationID": "id"}})
     */
    public function modify(Request $request, $resourceType, \App\Entity\ResourceClassification $resourceClassification)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$form = $this->createForm(ResourceClassificationType::class, $resourceClassification);

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'resourceClassification.updated.ok');
			return $this->redirectToRoute('resource_classification_index', array('resourceType' => $resourceType));
		}
    }

	return $this->render('resource_classification/modify.html.twig',
		array('userContext' => $userContext, 'resourceType' => $resourceType, 'resourceClassification' => $resourceClassification, 'form' => $form->createView()));
    }

    /**
     * @Route("/resourceclassification/delete/{resourceType}/{resourceClassificationID}", name="resource_classification_delete")
     * @ParamConverter("resourceClassification", options={"mapping": {"resourceClassificationID": "id"}})
     */
    public function delete(Request $request, $resourceType, \App\Entity\ResourceClassification $resourceClassification)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$form = $this->get('form.factory')->create();

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			$em->remove($resourceClassification);
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'resourceClassification.deleted.ok');
			return $this->redirectToRoute('resource_classification_index', array('resourceType' => $resourceType));
		}
    }

	return $this->render('resource_classification/delete.html.twig', array('userContext' => $userContext, 'resourceType' => $resourceType, 'resourceClassification' => $resourceClassification, 'form' => $form->createView()));
    }

    /**
     * @Route("/resourceclassification/foreigninternal/{resourceType}/{resourceClassificationCode}", name="resource_classification_foreign_internal")
     */
    public function foreign_internal($resourceType, $resourceClassificationCode)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	if ($resourceType == 'USER') {
		$ufRepository = $em->getRepository(UserFile::Class);
		$listUserFiles = $ufRepository->getUserFilesFrom_IRC($userContext->getCurrentFile(), $resourceClassificationCode);
		return $this->render('resource_classification/foreign.user.internal.html.twig',
			array('userContext' => $userContext, 'resourceType' => $resourceType, 'resourceClassificationCode' => $resourceClassificationCode,
				'action' => 'unactivate', 'listUserFiles' => $listUserFiles));
	} else {
		$rRepository = $em->getRepository(Resource::Class);
		$listResources = $rRepository->getResources_IRC($userContext->getCurrentFile(), $resourceType, $resourceClassificationCode);
                
		return $this->render('resource_classification/foreign.internal.html.twig',
	array('userContext' => $userContext, 'resourceType' => $resourceType, 'resourceClassificationCode' => $resourceClassificationCode, 'listResources' => $listResources));
	}
    }

    /**
     * @Route("/resourceclassification/foreignexternal/{resourceType}/{resourceClassificationID}/{action}", name="resource_classification_foreign_external")
     * @ParamConverter("resourceClassification", options={"mapping": {"resourceClassificationID": "id"}})
     */
    public function foreign_external($resourceType, \App\Entity\ResourceClassification $resourceClassification, $action)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	if ($resourceType == 'USER') {
		$ufRepository = $em->getRepository(UserFile::Class);
		$listUserFiles = $ufRepository->getUserFilesFrom_ERC($userContext->getCurrentFile(), $resourceClassification);
		return $this->render('resource_classification/foreign.user.external.html.twig',
			array('userContext' => $userContext, 'resourceType' => $resourceType, 'resourceClassification' => $resourceClassification,
				'action' => $action, 'listUserFiles' => $listUserFiles));
	} else {
		$rRepository = $em->getRepository(Resource::Class);
		$listResources = $rRepository->getResources_ERC($userContext->getCurrentFile(), $resourceType, $resourceClassification);
                
		return $this->render('resource_classification/foreign.external.html.twig',
			array('userContext' => $userContext, 'resourceType' => $resourceType, 'resourceClassification' => $resourceClassification,
				'action' => $action, 'listResources' => $listResources)	);
	}

	return $this->render('resource_classification/index.html.twig', array('userContext' => $userContext));
    }
}
