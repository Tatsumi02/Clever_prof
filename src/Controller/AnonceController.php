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
use App\Entity\Pourcentages;
use App\Repository\PourcentagesRepository;
use App\Entity\AnnonceValidation;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\AnnonValidationRepository;
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
    * * Require ROLE_PROF for only this controller method.
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
    * * Require ROLE_PROF for only this controller method.
    *
    * @IsGranted("ROLE_PROF")
     * @Route("/votre-cours.html",name="a_cours")
     */
    public function s_cours(Request $request){
        $repository = $this -> getDoctrine() -> getRepository(Matiere::class);
        $cours = $repository -> findAll();
        
        return $this->render('anonce/cours.html.twig', [
            'cours' => $cours,
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
    * * Require ROLE_PROF for only this controller method.
    *
    * @IsGranted("ROLE_PROF")
    * @Route("/traitement-cours/{branche}/{cours}",name="traitement_cours")
    */
    public function traitementCours($branche,$cours,Request $request){
        $em = $this -> getDoctrine() -> getManager();
        $anonce = new Anonce();
        $matiere = $cours.'-'.$branche;

        $anonce->setMatiere($matiere);
        $anonce->setCours($cours);
        $anonce->setTypeCours('undefined');
        $anonce->setTitre('undefined');
        $anonce->setParcours('undefined');
        $anonce->setMethodologie('undefined');
        $anonce->setLieuCours('undefined');
        $anonce->setTarifHeure(500);
        $anonce->setPhotoProfil('school.PNG');
        $anonce->setActif('false');
        $anonce->setCertifier('false');
        $anonce->setPourcentage(10);
        
        $anonce->setDateAnonce(new \DateTime());
        $anonce->setAnonceurId($this->getUser()->getId());
        $em->persist($anonce);
        $em->flush();
 
       return $this->redirectToRoute('type_cours');
    }

//---------------------------------------------- CONTROLLERS POURS LES UPDATES DES CHAMPS ANNONCES -------------------
    /**
    * * Require ROLE_PROF for only this controller method.
    *
    * @IsGranted("ROLE_PROF")
    * @Route("/update-cours/{id}/{branche}/{cours}",name="update_cours")
    */
    public function update_cours($id,$branche,$cours,Request $request){
        $matiere = $cours.'-'.$branche;
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $branches = $repository -> update_cours($id,$matiere);

         return $this->redirectToRoute('update_type_cours',['id'=>$id]);
         
    }

    //methode pour update le type de cours
    //formulaire de choix
    /**
    * * Require ROLE_PROF for only this controller method.
    *
    * @IsGranted("ROLE_PROF")
    * @Route("/update-type-cours/{id}/",name="update_type_cours")
    */
    public function update_type_cours(Request $request,$id){
        $id = $request->get('id');


        return $this->render('anonce/update/update_type_cours.html.twig', [
            'id' => $id,
        ]);
    }

    //methode pour update l type de cours
    //traitement du formulaire 
    /**
    * * Require ROLE_PROF for only this controller method.
    *
    * @IsGranted("ROLE_PROF")
    * @Route("/update-type-cours-traitement/{id}/",name="update_type_cours_traitement")
    */
    public function type_cours(Request $request,$id){

        $typeCours = $request->get('full');
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $branches = $repository -> update_type_cours($id,$typeCours);
        
        
        return $this->redirectToRoute('update_titre',['id'=>$id]);
         
    }

    /**
    * * Require ROLE_USER for only this controller method.
    * 
    * @IsGranted("ROLE_USER")
     * @Route("/{id}/update_titre-de-votre-annonce.html",name="update_titre")
     */
    public function update_titre(Request $request,$id){

        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $branches = $repository -> findBy(['id'=>$id]);

        return $this->render('anonce/update/update_titre.html.twig', [
            'id' => $id,
            'infos' => $branches,
        ]);

    }

    /**
    * * Require ROLE_PROF for only this controller method.
    * 
    * @IsGranted("ROLE_PROF")
     * @Route("/{id}/update_traitement_titre-de-votre-annonce.html",name="update_titre_traitement")
     */
    public function update_titre_traitement(Request $request,$id){
        $titre = $request->get('titre');
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $branches = $repository -> update_titre($id,$titre);
        
        
        return $this->redirectToRoute('update_parcours',['id'=>$id]);
         
        
    }

     /**
    * * Require ROLE_PROF for only this controller method.
    * 
    * @IsGranted("ROLE_PROF")
     * @Route("/{id}/update-parcours-annonce.html",name="update_parcours")
     */
    public function update_parcours(Request $request,$id){

        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $branches = $repository -> findBy(['id'=>$id]);

        return $this->render('anonce/update/update_parcours.html.twig', [
            'id' => $id,
            'infos' => $branches,
        ]);

    }

     /**
    * * Require ROLE_PROF for only this controller method.
    * 
    * @IsGranted("ROLE_PROF")
     * @Route("/{id}/update-parcours-traitement.html",name="update_parcours_traitement")
     */
    public function update_parcours_traitement(Request $request,$id){
        $parcours = $request->get('parcours');
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $branches = $repository -> update_parcours($id,$parcours);
        
        
        return $this->redirectToRoute('update_methodologie',['id'=>$id]);
         
        
    }

     /**
    * * Require ROLE_PROF for only this controller method.
    * 
    * @IsGranted("ROLE_PROF")
     * @Route("/{id}/update-methodologie-annonce.html",name="update_methodologie")
     */
    public function update_methodologie(Request $request,$id){

        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $branches = $repository -> findBy(['id'=>$id]);

        return $this->render('anonce/update/update_methodologie.html.twig', [
            'id' => $id,
            'infos' => $branches,
        ]);

    }
    
     /**
    * * Require ROLE_PROF for only this controller method.
    * 
    * @IsGranted("ROLE_PROF")
     * @Route("/{id}/update-methodologie-traitement.html",name="update_methodologie_traitement")
     */
    public function update_methodologie_traitement(Request $request,$id){
        $methodologie = $request->get('methodologie');
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $branches = $repository -> update_methodologie($id,$methodologie);
        
        
        return $this->redirectToRoute('update_lieux_cours',['id'=>$id]);
        
    }

     /**
    * * Require ROLE_PROF for only this controller method.
    * 
    * @IsGranted("ROLE_PROF")
     * @Route("/{id}/update-lieux_cours-annonce.html",name="update_lieux_cours")
     */
    public function update_lieux_cours(Request $request,$id){

        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $branches = $repository -> findBy(['id'=>$id]);

        return $this->render('anonce/update/update_lieux_cours.html.twig', [
            'id' => $id,
            'infos' => $branches,
        ]);

    }

     /**
    * * Require ROLE_PROF for only this controller method.
    * 
    * @IsGranted("ROLE_PROF")
     * @Route("/{id}/update-lieux_cours-traitement.html",name="update_lieu_cours_traitement")
     */
    public function update_lieu_cours_traitement(Request $request,$id){
        $lieux_cours = $request->get('lieu');
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $branches = $repository -> update_lieux_cours($id,$lieux_cours);
        
        
        return $this->redirectToRoute('update_tarif_heure',['id'=>$id]);

    }

    /**
    * * Require ROLE_PROF for only this controller method.
    * 
    * @IsGranted("ROLE_PROF")
     * @Route("/{id}/update-tarif_heure-annonce.html",name="update_tarif_heure")
     */
    public function update_tarif_heure(Request $request,$id){

        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $branches = $repository -> findBy(['id'=>$id]);

        return $this->render('anonce/update/update_tarif_heure.html.twig', [
            'id' => $id,
            'infos' => $branches,
        ]);

    }

    
     /**
    * * Require ROLE_PROF for only this controller method.
    * 
    * @IsGranted("ROLE_PROF")
     * @Route("/{id}/update-tarif_heure-traitement.html",name="update_tarif_heure_traitement")
     */
    public function update_tarif_heure_traitement(Request $request,$id){
        $tarif_heure = $request->get('tarif');
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $branches = $repository -> update_tarif_heure($id,$tarif_heure);
        
        
        return $this->redirectToRoute('end_update');

    }

    /**
    * * Require ROLE_PROF for only this controller method.
    * 
    * @IsGranted("ROLE_PROF")
     * @Route("/fin-mise-a-jour.html",name="end_update")
     */
    public function end_update(Request $request){

        return $this->render('anonce/update/end_update.html.twig', [
            ''=>''
        ]);

    }






//--------------------------------------------- FIN DES CONTROLLERS POUR L'UPDATE DES CHAMPS ANNONCES----------------------

    /**
    * * Require ROLE_PROF for only this controller method.
    * 
    * @IsGranted("ROLE_PROF")
     * @Route("/votre-type-de-cours.html",name="type_cours")
     */
    public function typeCours(Request $request){
        return $this->render('anonce/type_cours.html.twig', [
            'controller_name' => 'AnonceController',
        ]);

    }

     /**
    * * Require ROLE_PROF for only this controller method.
    *
    * @IsGranted("ROLE_PROF")
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
    * * Require ROLE_PROF for only this controller method.
    * 
    * @IsGranted("ROLE_PROF")
     * @Route("/titre-de-votre-annonce.html",name="titre")
     */
    public function titre(Request $request){
        return $this->render('anonce/titre.html.twig', [
            'controller_name' => 'AnonceController',
        ]);

    }

     /**
    * * Require ROLE_PROF for only this controller method.
    *
    * @IsGranted("ROLE_PROF")
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
    * * Require ROLE_PROF for only this controller method.
    * 
    * @IsGranted("ROLE_PROF")
     * @Route("/votre-parcours.html",name="parcours")
     */
    public function parcours(Request $request){
        return $this->render('anonce/parcours.html.twig', [
            'controller_name' => 'AnonceController',
        ]);

    }

     /**
    * * Require ROLE_PROF for only this controller method.
    *
    * @IsGranted("ROLE_PROF")
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
    * * Require ROLE_PROF for only this controller method.
    * 
    * @IsGranted("ROLE_PROF")
     * @Route("/votre-methododologie.html",name="methodologie")
     */
    public function methodologie(Request $request){
        return $this->render('anonce/methodologie.html.twig', [
            'controller_name' => 'AnonceController',
        ]);

    }

     /**
    * * Require ROLE_PROF for only this controller method.
    *
    * @IsGranted("ROLE_PROF")
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
    * * Require ROLE_PROF for only this controller method.
    * 
    * @IsGranted("ROLE_PROF")
     * @Route("/votre-lieu_cours.html",name="lieu_cours")
     */
    public function lieu_cours(Request $request){
        return $this->render('anonce/lieu_cours.html.twig', [
            'controller_name' => 'AnonceController',
        ]);

    }

     /**
    * * Require ROLE_PROF for only this controller method.
    *
    * @IsGranted("ROLE_PROF")
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
    * * Require ROLE_PROF for only this controller method.
    * 
    * @IsGranted("ROLE_PROF")
     * @Route("/votre-tarif-par-heure.html",name="tarif_heure")
     */
    public function tarif_heure(Request $request){
        return $this->render('anonce/tarif_heure.html.twig', [
            'controller_name' => 'AnonceController',
        ]);

    }

     /**
    * * Require ROLE_PROF for only this controller method.
    *
    * @IsGranted("ROLE_PROF")
     * @Route("/traitement-tirif-heure.html",name="tarif_heure_traitement")
     */
    public function tarifTraitement(Request $request){
        $tarif_init = (int) $request->get('tarif'); //recuperons le tarif, du professeur
        
          //nous allons faire une lecture dans la table pourcentage pour reccuperer le % actuelles
          $repository2 = $this -> getDoctrine() -> getRepository(Pourcentages::class);
          $pourcentages = $repository2 -> findAll();

          //nous allons maintenant boucler sur le resultat de requette pour recuperer le pourcentage
          $pourcentage_val = 10; //cette variable contiendra la valeur du % lu. c'est initialement egale a 10
        
          foreach($pourcentages as $pourcent){
               $pourcentage_val = $pourcent->getPourcentage();
          }
       
        //maintenant, retirons notre pourcentage dans ce tarif
        $pourcentage = ($tarif_init * $pourcentage_val)/100; //on a notre %
        //maintenant on va ajouter se % sur le tarif initiale du professeur
        $tarif_final = $tarif_init + $pourcentage;
        
        $tarif = (int) $tarif_final; //le tarif a enregistrer
        
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $branche = $repository -> findBy(['anonceur_id' => $this->getUser()->getId()]);
  
          foreach($branche as $br){
              $b = $br->getMatiere();
          }
  
          $upTypeCours = $repository -> updateTarifHeure($tarif,$this->getUser()->getId(),$b,$pourcentage);
       
           return $this->redirectToRoute('photo_profil');
       // return new Response('photo_profil');
        
  
      }

      /**
     * * Require ROLE_PROF for only this controller method.
     *
     * @IsGranted("ROLE_PROF")
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
            
            // $upRole = $repository2 -> updateRole($this->getUser()->getId());
             //notifions l'admin de la nouvelle anonce fait
            return $this->redirect($this->generateUrl('Etape_suivant'));
          }
        }
        

        /*$repository = $this -> getDoctrine() -> getRepository(Anonce::class);
       
        $branches = $repository -> findOneBy(['anonceur_id'=>$this->getUser()->getId()]);
        //foreach($branches as $branche){
            //$repository = $this -> getDoctrine() -> getRepository(AnnonceValidation::class);
           // $validations = $repository -> findBy(['annonceur_id'=>$this->getUser()->getId(),'public'=>'false']);
       // foreach($validations as $validation){h
            $em = $this -> getDoctrine() -> getManager();
            $anonceV = new AnnonceValidation();
            $anonceV ->setAnnonceurId($branches -> getAnonceurId());
            $anonceV->setAnnonceId($branches -> getId());
            $anonceV->setPublic('false');

            $em -> persist($anonceV);
            $em->flush();
        // }
            
       // } */
        

        return $this->render('anonce/pdp.html.twig', [
            'form' => $form->createView(),
            'controller_name' => 'hum',
        ]);
    }
         

    /**
     * 
     * * Require ROLE_PROF for only this controller method.
     *
     * @IsGranted("ROLE_PROF")
     * @Route("/presentantation-complet.html",name="Etape_suivant")
     */
    public function suivant(Request $request){

        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
       
        $branche = $repository -> findBy(['anonceur_id' => $this->getUser()->getId()]);
            
            foreach($branche as $br){
                $b = $br->getMatiere();
                
            }

            $data = $repository -> findOneBy(['anonceur_id'=>$this->getUser()->getId(),'matiere'=>$b]);
            $currentAnnonce = $repository->findBy(['anonceur_id'=>$this->getUser()->getId(),'matiere'=>$b]);
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
     * * Require ROLE_PROF for only this controller method.
     *
     * @IsGranted("ROLE_PROF")
     * @Route("/{id_anonce}/update-anonce.html",name="update")
     */
    public function update(Request $request,$id_anonce){
        $id = $request->get('id_anonce');
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
       
        $branche = $repository -> findBy(['id' => $id]);

        return $this->render('anonce/update.html.twig', [
            'anonce' => $branche,
        ]);
           
    }

    
    /**
     * 
     * @Route("/{id}/confirmation-annonce.html",name="confirme_annonce")
     */
    public function CAnnonce($id){
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $up = $repository -> updatActif($id);

        return $this->redirectToRoute('annonce_validation');
    }

    



}
