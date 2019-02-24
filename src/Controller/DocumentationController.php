<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DocumentationController extends Controller
{
   /**
     * @Route("/documentation/{pageCode}", name="documentation")
     */
    public function index($pageCode)
    {
	return $this->render('documentation/index.html.twig', array('pageCode' => $pageCode));
    }
}
