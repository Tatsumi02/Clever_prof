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




class ProfilController extends AbstractController
{
    /**
     * * Require ROLE_USER for only this controller method.
     *
     * @IsGranted("ROLE_USER")
     * @Route("/profil", name="profil")
     */
    public function index()
    {
        return $this->render('profil/index.html.twig', [
          
        ]);
    }

    /**
     * * Require ROLE_USER for only this controller method.
     *
     * @IsGranted("ROLE_USER")
     * @Route("/form-change", name="form")
     */
    public function form(Request $request, SluggerInterface $slugger){

      
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

    


}
