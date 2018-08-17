<?php
namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use App\Entity\File;
use App\Entity\User;
use App\Entity\UserContext;
use App\Entity\ListContext;
use App\Form\FileType;
use App\Api\AdministrationApi;

use App\Entity\FileEditContext;

class FileController extends Controller
{
    /**
     * @Route("/file/{page}", name="file", requirements={"page"="\d+"})
     */
    public function index($page)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
    $fRepository = $em->getRepository(File::Class);
    $numberRecords = $fRepository->getUserFilesCount($connectedUser);

    $listContext = new ListContext($em, $connectedUser, 'file', 'file', $page, $numberRecords);

    $listFiles = $fRepository->getUserDisplayedFiles($connectedUser, $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());
    
	return $this->render('file/index.html.twig', array('userContext' => $userContext, 'listContext' => $listContext, 'listFiles' => $listFiles));
    }
	
    /**
     * @Route("/file/add", name="file_add")
     */
    public function add(Request $request)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	
	$file = new File($connectedUser);
	$form = $this->createForm(FileType::class, $file);
	
	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));

		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($file);
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'file.created.ok');
			return $this->redirectToRoute('file', array('page' => 1));
		}
    }

	return $this->render('file/add.html.twig', array('userContext' => $userContext, 'form' => $form->createView()));
    }

    /**
     * @Route("/file/edit/{fileID}", name="file_edit")
     * @ParamConverter("file", options={"mapping": {"fileID": "id"}})
     */
    public function edit(Request $request, File $file)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	$fileEditContext = new FileEditContext($em, $file); // contexte dossier

	return $this->render('file/edit.html.twig', array('userContext' => $userContext, 'file' => $file, 'fileEditContext' => $fileEditContext));
    }

	// Modification d'un dossier
	/**
     * @Route("/file/modify/{fileID}", name="file_modify")
     * @ParamConverter("file", options={"mapping": {"fileID": "id"}})
     */
    public function modify(Request $request, File $file)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
    $form = $this->createForm(FileType::class, $file);

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'file.updated.ok');
			return $this->redirectToRoute('file_edit', array('fileID' => $file->getId()));
		}
    }
	return $this->render('file/modify.html.twig', array('userContext' => $userContext, 'file' => $file, 'form' => $form->createView()));
    }

    // Suppression d'un dossier
	/**
     * @Route("/file/delete/{fileID}", name="file_delete")
     * @ParamConverter("file", options={"mapping": {"fileID": "id"}})
     */
    public function delete(Request $request, File $file)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$currentFile = ($file->getId() == $userContext->getCurrentFileID()); // On repere si le dossier a supprimer est le dossier en cours.
	$form = $this->get('form.factory')->create();

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			$em->remove($file);
			$em->flush();
			if ($currentFile) { // Si le dossier supprime etait le dossier en cours, on positionne le premier dossier de la liste comme dossier en cours
				AdministrationApi::setFirstFileAsCurrent($em, $connectedUser);
			}
			$request->getSession()->getFlashBag()->add('notice', 'file.deleted.ok');
			return $this->redirectToRoute('file', array('page' => 1));
		}
    }
	return $this->render('file/delete.html.twig', array('userContext' => $userContext, 'file' => $file, 'form' => $form->createView()));
    }

	// Affichage des grilles horaires d'un dossier
	/**
     * @Route("/file/foreign/{fileID}", name="file_foreign")
     * @ParamConverter("file", options={"mapping": {"fileID": "id"}})
     */
    public function foreign(Request $request, File $file)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	return $this->render('file/foreign.html.twig', array('userContext' => $userContext, 'file' => $file));
    }


	// Positionne un dossier comme dossier en cours
	/**
     * @Route("/file/setcurrent/{fileID}", name="file_set_current")
     * @ParamConverter("file", options={"mapping": {"fileID": "id"}})
     */
    public function set_current(Request $request, File $file)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    // Mise a jour du dossier en cours
	AdministrationApi::setCurrentFile($em, $connectedUser, $file);
	$userContext->setCurrentFile($file); // Mettre a jour le dossier en cours dans le contexte utilisateur
    $request->getSession()->getFlashBag()->add('notice', 'file.current.updated.ok');
    return $this->redirectToRoute('file_edit', array('fileID' => $file->getId()));
    }
}
