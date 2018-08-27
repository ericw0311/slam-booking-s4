<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PlanificationController extends Controller
{
    /**
     * @Route("/planification", name="planification")
     */
    public function index()
    {
        return $this->render('planification/index.html.twig', [
            'controller_name' => 'PlanificationController',
        ]);
    }
}
