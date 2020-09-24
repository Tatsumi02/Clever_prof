<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\Anonce;
use App\Entity\Commande;
use App\Form\PdppType;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\UserRepository;
use App\Repository\CommandeRepository;
use App\Repository\AnonceRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/** 
* @Route("/profil")
*/
class ProfilController extends AbstractController
{
    /**
     * * Require ROLE_USER for only this controller method.
     *
     * @IsGranted("ROLE_USER")
     * @Route("/", name="profil")
     */
    public function index()
    {
        //recuperons tout les information de l'utilisateur en cours ! l'utilisateur actif en ce momment meme 
        $repository = $this -> getDoctrine() -> getRepository(User::class);
        $informations = $repository -> findBy(['id' => $this->getUser()->getId()]);
        
        return $this->render('profil/index.html.twig', [
          'infos' => $informations,
        ]);
    }

     /**
     * @Route("/{id}/{nom}", name="user_profil")
     */
    public function userProfil($id,$nom){
        //------------------controlleur pour les profils

        //recuperons les profils
        $repository = $this -> getDoctrine() -> getRepository(User::class);
        $users = $repository -> findBy(['id' => $id]);

        //recuperons les annonces de se profil
        $repository = $this -> getDoctrine() -> getRepository(Anonce::class);
        $annonces = $repository -> findBy(['anonceur_id' => $id,'actif' => 'true']);

        //compeur pour vrifier si les annonce sera trouver ou non
        $compt = 0;
        foreach($annonces as $is){
           $compt++;
        }

  
        return $this->render('home/user_profil.html.twig',[
          'users' => $users,
          'annonces' => $annonces,
          'compt' => $compt,
        ]);
      }
  

    /**
     * * Require ROLE_USER for only this controller method.
     *
     * @IsGranted("ROLE_USER")
     * @Route("/pdp-update.html", name="form")
     */
    public function form(Request $request, SluggerInterface $slugger){
      //methode permetent de changer la pdp
      
        $user = new User();
        $form = $this->createForm(PdppType::class, $user);
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
                // if (in_array($extension,$extensions_autorisees)){
            
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                
                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('pdp'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                    //throw new Exception("Error Processing Request", 1);

                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
               
            //}
             
        
            $repository = $this -> getDoctrine() -> getRepository(User::class);
            $mod = $repository -> updatePdp($newFilename,$this->getUser()->getId());
            
            return $this->redirect($this->generateUrl('profil'));
          }
        }
        
        return $this->render('profil/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }
//------------------------------------ update des informations personnels ------------------------

   
   /**
     * * Require ROLE_USER for only this controller method.
     *
     * @IsGranted("ROLE_USER")
     * @Route("/update_nom", name="update_nom")
     */
    public function update_nom(Request $request){
        
        $new_nom = $request->request->get('new_nom');
        $repository = $this -> getDoctrine() -> getRepository(User::class);
        $mod = $repository -> updateNom($new_nom,$this->getUser()->getId());
            
        return $this -> redirectToRoute('profil');
    }

     /**
     * * Require ROLE_USER for only this controller method.
     *
     * @IsGranted("ROLE_USER")
     * @Route("/update_prenom", name="update_prenom")
     */
    public function update_prenom(Request $request){

        $new_prenom = $request->request->get('new_prenom');
        $repository = $this -> getDoctrine() -> getRepository(User::class);
        $mod = $repository -> updatePrenom($new_prenom,$this->getUser()->getId());
            
        return $this -> redirectToRoute('profil');
    }

    /**
     * * Require ROLE_USER for only this controller method.
     *
     * @IsGranted("ROLE_USER")
     * @Route("/update_phone", name="update_phone")
     */
    public function update_phone(Request $request){
        
        $new_phone = $request->request->get('new_phone');
        $repository = $this -> getDoctrine() -> getRepository(User::class);
        $mod = $repository -> updatePhone($new_phone,$this->getUser()->getId());
            
        return $this -> redirectToRoute('profil');
    }

    /**
     * * Require ROLE_USER for only this controller method.
     *
     * @IsGranted("ROLE_USER")
     * @Route("/update_adresse", name="update_adresse")
     */
    public function update_adresse(Request $request){
        
        $new_adresse = $request->request->get('new_adresse');
        $repository = $this -> getDoctrine() -> getRepository(User::class);
        $mod = $repository -> updateAdresse($new_adresse,$this->getUser()->getId());
            
        return $this -> redirectToRoute('profil');
    }



//----------------------------------- fin update des informations personnels-----------------------

    


}
