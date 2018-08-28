<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LabelController extends Controller
{
    /**
     * @Route("/label", name="label")
     */
    public function index()
    {
        return $this->render('label/index.html.twig', [
            'controller_name' => 'LabelController',
        ]);
    }
}
