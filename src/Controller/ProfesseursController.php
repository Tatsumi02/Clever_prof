<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ProfesseursController extends AbstractController
{
    /**
     * @Route("/professeurs", name="professeurs")
     */
    public function index()
    {
        return $this->render('professeurs/index.html.twig', [
            'controller_name' => 'ProfesseursController',
        ]);
    }
}
