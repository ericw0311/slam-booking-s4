<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FileController extends Controller
{
    /**
     * @Route("/file/{page}", name="file", requirements={"page"="\d+"})
     */
    public function index($page)
    {
	$connectedUser = $this->getUser();
	$em = $this->getDoctrine()->getManager();

        return $this->render('file/index.html.twig', [
            'controller_name' => 'FileController',
        ]);
    }
}
