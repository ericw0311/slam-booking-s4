<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

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
		return $this->render('admin/index.html.twig');
	}
}
