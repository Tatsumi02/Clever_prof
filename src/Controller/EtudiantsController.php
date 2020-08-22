<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


 /**
  * @Route("/etudiants")
  */
class EtudiantsController extends AbstractController
{
    /**
     * @Route("/", name="etudiants")
     */
    public function index()
    {
        return $this->render('etudiants/index.html.twig', [
            'controller_name' => 'EtudiantsController',
        ]);
    }
}
