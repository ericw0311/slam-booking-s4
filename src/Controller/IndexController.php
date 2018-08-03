<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\UserContext;

class IndexController extends Controller
{
	/**
     * @Route("/", name="index")
     */
    public function index()
    {
		return $this->render('index.html.twig');
    }
 
    /**
     * @Route("/nadmin", name="admin")
     */
    public function admin()
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();
	$userContext = new UserContext($em, $connectedUser); // contexte utilisateur

	return $this->render('admin/index.html.twig', array('userContext' => $userContext));
	}
}
