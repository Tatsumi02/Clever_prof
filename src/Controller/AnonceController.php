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
use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Notification;
use App\Repository\AnonceRepository;
use App\Repository\NotificationRepository;
use App\Entity\Matiere;
use App\Repository\MatiereRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Form\PdpType;



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
        $anonce->setCertifier('false');
        $anonce->setDateAnonce(new \DateTime());
        $anonce->setAnonceurId($this->getUser()->getId());
        $em->persist($anonce);
        $em->flush();
 
       return $this->redirectToRoute('type_cours');
    }

    /**
    * * Require ROLE_USER for only this controller method.
    * 
    * @IsGranted("ROLE_USER")
     * @Route("/votre-type-de-cours.html",name="type_cours")
     */
    public function typeCours(Request $request){
        return $this->render('anonce/type_cours.html.twig', [
            'controller_name' => 'AnonceController',
        ]);

    }

     /**
    * * Require ROLE_USER for only this controller method.
    *
    * @IsGranted("ROLE_USER")
     * @Route("/traitement-type-de-cours.html",name="type_cours_traitement")
     */
    public function tCoursTraitement(Request $request){
       $typeCours = $request->get('full');
       
    $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $branche = $repository -> findBy(['anonceur_id' => $this->getUser()->getId()]);

        foreach($branche as $br){
            $b = $br->getMatiere();
        }

        $upTypeCours = $repository -> updateTypeCours($request->get('full'),$this->getUser()->getId(),$b);
     
        return $this->redirectToRoute('titre');
    }    

     /**
    * * Require ROLE_USER for only this controller method.
    * 
    * @IsGranted("ROLE_USER")
     * @Route("/titre-de-votre-annonce.html",name="titre")
     */
    public function titre(Request $request){
        return $this->render('anonce/titre.html.twig', [
            'controller_name' => 'AnonceController',
        ]);

    }

     /**
    * * Require ROLE_USER for only this controller method.
    *
    * @IsGranted("ROLE_USER")
     * @Route("/traitement-titre-annoce.html",name="titre_traitement")
     */
    public function titreTraitement(Request $request){
      $titre = $request->get('titre');
      
      $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $branche = $repository -> findBy(['anonceur_id' => $this->getUser()->getId()]);

        foreach($branche as $br){
            $b = $br->getMatiere();
        }

        $upTypeCours = $repository -> updateTypeTitre($request->get('titre'),$this->getUser()->getId(),$b);
     
        return $this->redirectToRoute('parcours');

    }

     /**
    * * Require ROLE_USER for only this controller method.
    * 
    * @IsGranted("ROLE_USER")
     * @Route("/votre-parcours.html",name="parcours")
     */
    public function parcours(Request $request){
        return $this->render('anonce/parcours.html.twig', [
            'controller_name' => 'AnonceController',
        ]);

    }

     /**
    * * Require ROLE_USER for only this controller method.
    *
    * @IsGranted("ROLE_USER")
     * @Route("/traitement-parcours.html",name="parcours_traitement")
     */
    public function parcoursTraitement(Request $request){
        $titre = $request->get('titre');
        
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
          $branche = $repository -> findBy(['anonceur_id' => $this->getUser()->getId()]);
  
          foreach($branche as $br){
              $b = $br->getMatiere();
          }
  
          $upTypeCours = $repository -> updateParcours($request->get('parcours'),$this->getUser()->getId(),$b);
       
          return $this->redirectToRoute('methodologie');
  
      }

   /**
    * * Require ROLE_USER for only this controller method.
    * 
    * @IsGranted("ROLE_USER")
     * @Route("/votre-methododologie.html",name="methodologie")
     */
    public function methodologie(Request $request){
        return $this->render('anonce/methodologie.html.twig', [
            'controller_name' => 'AnonceController',
        ]);

    }

     /**
    * * Require ROLE_USER for only this controller method.
    *
    * @IsGranted("ROLE_USER")
     * @Route("/traitement-methodologie.html",name="methodologie_traitement")
     */
    public function methodologieTraitement(Request $request){
        $titre = $request->get('methodologie');
        
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
          $branche = $repository -> findBy(['anonceur_id' => $this->getUser()->getId()]);
  
          foreach($branche as $br){
              $b = $br->getMatiere();
          }
  
          $upTypeCours = $repository -> updateMethodologie($request->get('methodologie'),$this->getUser()->getId(),$b);
       
         return $this->redirectToRoute('lieu_cours');
  
      }
  
    /**
    * * Require ROLE_USER for only this controller method.
    * 
    * @IsGranted("ROLE_USER")
     * @Route("/votre-lieu_cours.html",name="lieu_cours")
     */
    public function lieu_cours(Request $request){
        return $this->render('anonce/lieu_cours.html.twig', [
            'controller_name' => 'AnonceController',
        ]);

    }

     /**
    * * Require ROLE_USER for only this controller method.
    *
    * @IsGranted("ROLE_USER")
     * @Route("/traitement-lieu_cours.html",name="lieu_cours_traitement")
     */
    public function lieu_traitementTraitement(Request $request){
        $titre = $request->get('lieu');
        
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
          $branche = $repository -> findBy(['anonceur_id' => $this->getUser()->getId()]);
  
          foreach($branche as $br){
              $b = $br->getMatiere();
          }
  
          $upTypeCours = $repository -> updateLieuCours($request->get('lieu'),$this->getUser()->getId(),$b);
       
          return $this->redirectToRoute('tarif_heure');
        
  
      }

    /**
    * * Require ROLE_USER for only this controller method.
    * 
    * @IsGranted("ROLE_USER")
     * @Route("/votre-tarif-par-heure.html",name="tarif_heure")
     */
    public function tarif_heure(Request $request){
        return $this->render('anonce/tarif_heure.html.twig', [
            'controller_name' => 'AnonceController',
        ]);

    }

     /**
    * * Require ROLE_USER for only this controller method.
    *
    * @IsGranted("ROLE_USER")
     * @Route("/traitement-tirif-heure.html",name="tarif_heure_traitement")
     */
    public function tarifTraitement(Request $request){
        $titre = $request->get('tarif');
        
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
          $branche = $repository -> findBy(['anonceur_id' => $this->getUser()->getId()]);
  
          foreach($branche as $br){
              $b = $br->getMatiere();
          }
  
          $upTypeCours = $repository -> updateTarifHeure($request->get('tarif'),$this->getUser()->getId(),$b);
       
           return $this->redirectToRoute('photo_profil');
       // return new Response('photo_profil');
        
  
      }

      /**
     * * Require ROLE_USER for only this controller method.
     *
     * @IsGranted("ROLE_USER")
     * @Route("/photo-profile.html", name="photo_profil")
     */
    public function changePdp(Request $request, SluggerInterface $slugger){
        $anonce = new Anonce();
        $form = $this->createForm(PdpType::class, $anonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('brochure')->getData();

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($brochureFile) {
                 //enregistrons l'extension dans une variable
                 $extension = $brochureFile->guessExtension();
                // creons un tableau d'extension disponible pour le fichier.
                $extensions_autorisees = array('jpg','gif','GIF', 'jpeg','JPG','JPEG','png','PNG');
                  $nameErrorFile = $slugger->slug(pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME)); //cette variable doit retenir le nom du fichier avant la verification sur l'extension
                 if (in_array($extension,$extensions_autorisees)){
            
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                
                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('pdp_annonce'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    //throw new Exception("Error Processing Request", 1);

                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
               
            }else{
                /*si l'utilisateur a essayer d'envoyer un fichier different d'une image
                return $this->render('elysionne/ErrorFileExtension.html.twig', [
                    'nomFichier' =>  $nameErrorFile,
                    'extension' => $extension,
                ]);*/
                return new Response('une erreur est produit: La cause est du au fichier que vous avez essayer d\'envoyer');
            }

            
            $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
            $repository2 = $this -> getDoctrine() -> getRepository(User::class);
            $branche = $repository -> findBy(['anonceur_id' => $this->getUser()->getId()]);
             
            foreach($branche as $br){
                $b = $br->getMatiere();
            }

            //$repository = $this -> getDoctrine() -> getRepository(Anonce::class);
            $mod = $repository -> updatePdp($newFilename,$this->getUser()->getId(),$b);
            
            $upRole = $repository2 -> updateRole($this->getUser()->getId());
             //notifions l'admin de la nouvelle anonce fait
            return $this->redirect($this->generateUrl('Etape_suivant'));
          }
        }

        return $this->render('anonce/pdp.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'hum',
        ]);
    }

    /**
     * 
     * * Require ROLE_USER for only this controller method.
     *
     * @IsGranted("ROLE_USER")
     * @Route("/presentantation-complet.html",name="Etape_suivant")
     */
    public function suivant(Request $request){

        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
       
        $branche = $repository -> findBy(['anonceur_id' => $this->getUser()->getId()]);
            
            foreach($branche as $br){
                $b = $br->getMatiere();
                
            }

            $data = $repository -> findOneBy(['anonceur_id'=>$this->getUser()->getId(),'matiere'=>$b,'actif'=>'true']);
            $currentAnnonce = $repository->findBy(['anonceur_id'=>$this->getUser()->getId(),'matiere'=>$b,'actif'=>'true']);
            $cours = explode('-',$b);

        return $this->render('anonce/visualisation.html.twig', [
            'pdp' => $data->getPhotoProfil(),
            'cours' => $data->getMatiere(),
            'titre' => $data->getTitre(),
            'allData' => $currentAnnonce,
            'autreAnonce' => $branche,
            'cours' => $cours[0],
            'controller_name' => 'hum',
        ]);
    }

   /**
     * 
     * * Require ROLE_USER for only this controller method.
     *
     * @IsGranted("ROLE_USER")
     * @Route("/update-anonce.html",name="update")
     */
    public function update(Request $request){
        $id = $request->get('id_anonce');
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
       
        $branche = $repository -> findBy(['id' => $id]);

        return $this->render('anonce/update.html.twig', [
            'anonce' => $branche,
        ]);
           
    }



}
