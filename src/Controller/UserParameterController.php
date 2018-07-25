<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserParameterController extends Controller
{
    /**
     * @Route("/user/parameter", name="parameter_numberLinesColumns")
     */
    public function index()
    {
        return $this->render('user_parameter/index.html.twig', [
            'controller_name' => 'UserParameterController',
        ]);
    }
}
