<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Form\UserType;
use App\Form\UserPasswordType;
use App\Form\UserModifyType;
use App\Entity\User;
use App\Entity\UserContext;
use App\Events;

class UserController extends Controller
{
	/**
	 * @Route("/register", name="user_registration")
     */
	public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, EventDispatcherInterface $eventDispatcher)
	{
	$user = new User();
	$form = $this->createForm(UserType::class, $user);

	$form->handleRequest($request);
	if ($form->isSubmitted() && $form->isValid()) {
		$password = $passwordEncoder->encodePassword($user, $user->getPassword());
		$user->setPassword($password);

		// Par defaut l'utilisateur aura toujours le rôle ROLE_USER
		$user->setRoles(['ROLE_USER']);

		// On enregistre l'utilisateur dans la base
		$em = $this->getDoctrine()->getManager();
		$em->persist($user);
		$em->flush();

		//On déclenche l'event
		$event = new GenericEvent($user);
		$eventDispatcher->dispatch(Events::USER_REGISTERED, $event);

		return $this->redirectToRoute('user_login');
	}

	return $this->render('user/register.html.twig', array('form' => $form->createView()));
    }

	/**
	 * @Route("/login", name="user_login")
	 */
	public function login(AuthenticationUtils $helper): Response
	{
		return $this->render('user/login.html.twig', [
			// dernier username saisi (si il y en a un)
			'last_username' => $helper->getLastUsername(),
			// La derniere erreur de connexion (si il y en a une)
			'error' => $helper->getLastAuthenticationError(),
		]);
	}

	/**
	 * La route pour se deconnecter.
	 * 
	 * Mais celle ci ne doit jamais être executée car symfony l'interceptera avant.
     *
     *
     * @Route("/logout", name="user_logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }

	// Affichage du detail de l' utilisateur connecté
	/**
    * @Route("/user/edit", name="user_edit")
    */
    public function edit()
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	return $this->render('user/edit.html.twig', array('userContext' => $userContext, 'user' => $connectedUser));
    }

	// Modification de l' utilisateur connecté
    /**
    * @Route("/user/modify", name="user_modify")
    */
    public function modify(Request $request)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $form = $this->createForm(UserModifyType::class, $connectedUser);

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'user.updated.ok');
			return $this->redirectToRoute('user_edit');
		}
    }

    return $this->render('user/modify.html.twig', array('userContext' => $userContext, 'user' => $connectedUser, 'form' => $form->createView()));
    }

	// Modification du mot de passe de l' utilisateur connecté
    /**
    * @Route("/user/password", name="user_password")
    */
    public function password(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

    $form = $this->createForm(UserPasswordType::class, $connectedUser);

	if ($request->isMethod('POST')) {
		$form->submit($request->request->get($form->getName()));
		if ($form->isSubmitted() && $form->isValid()) {
			$password = $passwordEncoder->encodePassword($connectedUser, $connectedUser->getPassword());
			$connectedUser->setPassword($password);
			$em->flush();
			$request->getSession()->getFlashBag()->add('notice', 'user.password.updated.ok');
			return $this->redirectToRoute('user_edit');
		}
    }

    return $this->render('user/password.html.twig', array('userContext' => $userContext, 'user' => $connectedUser, 'form' => $form->createView()));
    }
}
