<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Anonce;
use App\Repository\AnonceRepository;
use App\Entity\Matiere;
use App\Repository\MatiereRepository;



class AnonceController extends AbstractController
{
    /**
    * * Require ROLE_USER for only this controller method.
    *
    * @IsGranted("ROLE_USER")
     * @Route("/cree-une-anonce.html", name="cree_anonce")
     */
    public function index()
    {
        return $this->render('anonce/index.html.twig', [
            'controller_name' => 'AnonceController',
        ]);
    }

    /**
    * * Require ROLE_USER for only this controller method.
    *
    * @IsGranted("ROLE_USER")
     * @Route("/votre-cours.html",name="a_cours")
     */
    public function s_cours(Request $request){

        return $this->render('anonce/cours.html.twig', [
            'controller_name' => 'AnonceController',
        ]);
    
    }

    /**
     * @Route("/prendre-branche",name="ajax_cours")
     */
    public function ajaxCours(Request $request){
        $cour = $request->get('cours');
        $repository = $this -> getDoctrine() -> getRepository(Matiere::class);
        $branches = $repository -> findBy(['cours'=>$cour,]);
         
        if ($request->isXmlHttpRequest() || $request->query->get('showJson')==1) {
            $jsonData = array();
            $idx=0; $boost = 0;
            if($branches){  
            foreach($branches as $bran){
               // $lesBranche = explode(',',$bran->getBranche());
                $temp = array(
                  'branche' => $bran->getBranche(), 
                  'cours' => $bran -> getCours(),
                );
                $jsonData[$idx++] = $temp;
            }
          }
        
            return new JsonResponse($jsonData);
           
        }else{
            return new Response('erreur ajax');
        }
    }

    /**
    * * Require ROLE_USER for only this controller method.
    *
    * @IsGranted("ROLE_USER")
    * @Route("/traitement-cours/{branche}/{cours}",name="traitement_cours")
    */
    public function traitementCours($branche,$cours,Request $request){
        $em = $this -> getDoctrine() -> getManager();
        $anonce = new Anonce();
        $matiere = $cours.'-'.$branche;

        $anonce->setMatiere($matiere);
        $anonce->setTypeCours('indef');
        $anonce->setTitre('indef');
        $anonce->setParcours('indef');
        $anonce->setMethodologie('indef');
        $anonce->setLieuCours('indef');
        $anonce->setTarifHeure(500);
        $anonce->setPhotoProfil('indef');
        $anonce->setActif('indef');
        $anonce->setDateAnonce(new \DateTime());
        $anonce->setAnonceurId($this->getUser()->getId());
        $em->persist($anonce);
        $em->flush();
 
       return $this->redirectToRoute('');
    }


}
