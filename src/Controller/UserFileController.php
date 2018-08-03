<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\User;
use App\Entity\UserFile;
use App\Entity\UserParameter;
use App\Entity\UserContext;
use App\Entity\ListContext;
use App\Form\UserFileAddType;
use App\Form\UserFileEmailType;
use App\Form\UserFileType;
use App\Form\UserFileAccountType;

use App\Api\AdministrationApi;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserFileController extends Controller
{
    /**
     * @Route("/userfile/{page}", name="user_file", requirements={"page"="\d+"})
     */
    public function index($page)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
 	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
 
	$ufRepository = $em->getRepository(UserFile::Class);
    $numberRecords = $ufRepository->getUserFilesCount($userContext->getCurrentFile());
    $listContext = new ListContext($em, $connectedUser, 'userFile', $page, $numberRecords);
    $listUserFiles = $ufRepository->getDisplayedUserFiles($userContext->getCurrentFile(), $listContext->getFirstRecordIndex(), $listContext->getMaxRecords());
    
	return $this->render('user_file/index.html.twig', array('userContext' => $userContext, 'listContext' => $listContext, 'listUserFiles' => $listUserFiles));
    }


	// 1ere etape de l'ajout d'un utilisateur au dossier en cours (userFile): saisie de son email.
	// Si l'utilisateur (user) correspondant existe, le userFile est cree a partir de l'utilisateur trouve.
	// Si l'utilisateur (user) correspondant n'existe pas, le userFile est cree par un formulaire (2eme etape).
    /**
     * @Route("/userfile/email", name="user_file_email")
     */
    public function email(Request $request)
    {
    $connectedUser = $this->getUser();
    $em = $this->getDoctrine()->getManager();
    $userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $userFile = new UserFile($connectedUser, $userContext->getCurrentFile()); // Initialisation du userFile. Les zones lastName, firstName et email sont gerees par le formulaire UserFileEmailType
    $userFile->setAdministrator(false);
    $userFile->setUserCreated(false);
    $form = $this->createForm(UserFileEmailType::class, $userFile);
    $userFound = false;

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));

		if ($form->isSubmitted() && $form->isValid()) {
			$uRepository = $em->getRepository(User::Class); // On recherche l'utilisateur d'apres l'email saisi
			$user = $uRepository->findOneBy(array('email' => $userFile->getEmail()));
			if ($user === null) { // L'utilisateur n'existe pas, on appelle le formulaire pour creer le userFile
				return $this->redirectToRoute('user_file_add', array('email' => $userFile->getEmail()));
			} else { // L'utilisateur existe, on cree le userFile a partir de l'utilisateur
				$userFound = true;
				$userFile->setAccount($user);
				$userFile->setAccountType($user->getAccountType());
				$userFile->setLastName($user->getLastName());
				$userFile->setFirstName($user->getFirstName());
				$userFile->setUniqueName($user->getUniqueName());
				$userFile->setUserCreated(true);
				$userFile->setUsername($user->getUserName());
				$em->persist($userFile);
				$em->flush();
				if ($userFound) { // Mise a jour du dossier en cours de l'utilisateur trouve
					AdministrationApi::setCurrentFileIfNotDefined($em, $user, $userFile->getFile());
				}
				$request->getSession()->getFlashBag()->add('notice', 'userFile.created.ok');
				return $this->redirectToRoute('user_file', array('page' => 1));
			}
		}
    }
	return $this->render('user_file/email.html.twig', array('userContext' => $userContext, 'form' => $form->createView()));
    }


	// 2eme etape de l'ajout d'un utilisateur au dossier en cours (userFile): saisie de son email.
	// L'utilisateur (user) correspondant a l'email saisi a l'etape 1 n'existe pas, le userFile est cree par un formulaire.
    /**
     * @Route("/userfile/add/{email}", name="user_file_add")
     */
    public function add(Request $request, $email)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur
	
    $userFile = new UserFile($connectedUser, $userContext->getCurrentFile());
    $userFile->setEmail($email);
    $userFile->setAdministrator	(false);
    $userFile->setUserCreated(false);
    $form = $this->createForm(UserFileAddType::class, $userFile);

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			$em->persist($userFile);
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'user_file.created.ok');
			return $this->redirectToRoute('user_file', array('page' => 1));
		}
    }

	return $this->render('user_file/add.html.twig', array('userContext' => $userContext, 'userFile' => $userFile, 'form' => $form->createView()));
    }


    // Affichage du detail d'un utilisateur du dossier en cours (userFile)
    /**
    * @Route("/userfile/edit/{userFileID}", name="user_file_edit")
    * @ParamConverter("userFile", options={"mapping": {"userFileID": "id"}})
    */
    public function edit(UserFile $userFile)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    // L'utilisateur connecte est-il le createur du dossier ?
    $connectedUserIsFileCreator = ($connectedUser === $userContext->getCurrentFile()->getUser());
    // L'utilisateur selectionne est-il le createur du dossier ?
    $selectedUserIsFileCreator = ($userFile->getUserCreated() and $userFile->getAccount() === $userContext->getCurrentFile()->getUser());
	$atLeastOneUserClassification = false;

	return $this->render('user_file/edit.html.twig', array('userContext' => $userContext, 'userFile' => $userFile,
		'connectedUserIsFileCreator' => $connectedUserIsFileCreator,
		'selectedUserIsFileCreator' => $selectedUserIsFileCreator,
		'atLeastOneUserClassification' => $atLeastOneUserClassification));
    }


    // Modification d'un utilisateur du dossier en cours (userFile)
    /**
    * @Route("/userfile/modify/{userFileID}", name="user_file_modify")
    * @ParamConverter("userFile", options={"mapping": {"userFileID": "id"}})
    */
    public function modify(Request $request, UserFile $userFile)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$userFileUserCreated = $userFile->getUserCreated(); // Information sauvegardee car peut etre modifiee par la suite
    if ($userFileUserCreated) { // L'utilisateur à modifier a un compte utilisateur de crée
        $form = $this->createForm(UserFileAccountType::class, $userFile);
    } else {
        $form = $this->createForm(UserFileType::class, $userFile);
    }
    $userFound = false;

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {

			if (!$userFile->getUserCreated()) { // On traite le cas tres particulier de modification de l'email
				$uRepository = $em->getRepository(User::Class); // On recherche l'utilisateur d'apres l'email modifie
				$user = $uRepository->findOneBy(array('email' => $userFile->getEmail()));
				if ($user != null) { // L'utilisateur existe, on met a jour le userFile a partir de l'utilisateur
					$userFound = true;
					$userFile->setAccount($user);
					$userFile->setAccountType($user->getAccountType());
					$userFile->setLastName($user->getLastName());
					$userFile->setFirstName($user->getFirstName());
					$userFile->setUniqueName($user->getUniqueName());
					$userFile->setUserCreated(true);
					$userFile->setUsername($user->getUsername());
				}
			}
			$em->flush();
			if ($userFound) { // Mise a jour du dossier en cours de l'utilisateur trouve
				AdministrationApi::setCurrentFileIfNotDefined($em, $user, $userFile->getFile());
			}
			$request->getSession()->getFlashBag()->add('notice', 'userFile.updated.ok');
			return $this->redirectToRoute('user_file_edit', array('userFileID' => $userFile->getId()));
		}
    }

    if ($userFileUserCreated) { // L'utilisateur a modifier a un compte utilisateur de cree
        $request->getSession()->getFlashBag()->add('notice', 'user_file.update.not.allowed.3');
    }
    return $this->render('user_file/modify.html.twig', array('userContext' => $userContext, 'userFile' => $userFile, 'form' => $form->createView()));
    }


    // Suppression d'un utilisateur du dossier en cours (userFile)
    /**
    * @Route("/userfile/delete/{userFileID}", name="user_file_delete")
    * @ParamConverter("userFile", options={"mapping": {"userFileID": "id"}})
    */
	public function delete(Request $request, UserFile $userFile)
	{
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	$userAccount = $userFile->getAccount(); // Compte utilisateur attaché au userFile
	$form = $this->get('form.factory')->create();

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			$em->remove($userFile);
			$em->flush();
        
			if ($userAccount != null) { // Le userFile a un compte utilisateur attaché
				$currentFileID = AdministrationApi::getCurrentFileID($em, $userAccount);
				if ($currentFileID == $userContext->getCurrentFileID()) { // Son dossier en cours est le dossier en cours de l'utilisateur connecte
					AdministrationApi::setFirstFileAsCurrent($em, $userAccount); // On met a jour son dossier en cours
				}
			}
			$request->getSession()->getFlashBag()->add('notice', 'user_file.deleted.ok');
			return $this->redirectToRoute('user_file', array('page' => 1));
		}
	}
	return $this->render('user_file/delete.html.twig', array('userContext' => $userContext, 'userFile' => $userFile, 'form' => $form->createView()));
    }
}
