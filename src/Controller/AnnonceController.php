<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\ImageAnnonce;
use App\Entity\User;
use App\Form\Annonce2Type;
use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry as DoctrineManagerRegistry;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;



use App\Form\ImageAnnonceType;

use App\Repository\LebelleOptionAnnonceRepository;
// use App\Entity\LebelleOptionAnnonce;
use App\Repository\ImageAnnonceRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\OptionAnnonceRepository;
use App\Repository\CategoryRepository;

// use App\Repository\OptionAnnonce;
use App\Entity\OptionAnnonce;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\UserRepository;
use Psr\Log\LoggerInterface;
use SebastianBergmann\Environment\Console;
use Symfony\Component\HttpKernel\Log\Logger;


#[Route('/annonce')]
class AnnonceController extends AbstractController
{
    

    #[Route('/', name: 'app_annonce_index', methods: ['GET'])]
    public function index(AnnonceRepository $annonceRepository,ImageAnnonceRepository $im,UserRepository $user): Response
    {
        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonceRepository->findAll(),
            'imgs'=>$im->findAll(),
            'user'=>$user->findAll(),
        ]);
    }
    #[Route('/admin')]
    public function showannonceattente(AnnonceRepository $annonceRepository,ImageAnnonceRepository $im,UserRepository $user): Response
    {
        return $this->render('annonce/admin.html.twig', [
            'annonces' => $annonceRepository->findAll(),
        ]);
    }
    #[Route('/mesannonces')]
    public function mesAnnonces(AnnonceRepository $annonceRepository,ImageAnnonceRepository $im,UserRepository $user, DoctrineManagerRegistry $doctrine): Response
    {
        /*$repository = $doctrine->getRepository(User::class);
        $email = $this->getUser()->getUserIdentifier();
        $user = $repository->findOneBy(array('email' => $email));
        */
        
        return $this->render('annonce/mesannonces.html.twig', [
            //'annonces' => $annonceRepository->findBy(['id_User', $user]),
            'annonces' => $annonceRepository->findAll(),
            'imgs'=>$im->findAll(),
            'user'=>$user->findAll(),
        ]);
    }

#[Route('/showannonce')]
  public function showannonce(AnnonceRepository $annonceRepository, int $id): Response
  {
    $querryresult = $annonceRepository->find($id);

    // render
    return $this->render('annonce/_showannonce.html.twig', [
      'annonce' => $querryresult,
    ]);
  }



    #[Route('/new', name: 'app_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, annonceRepository $annonceRepository,ImageAnnonceRepository $imageAnnonceRepository, DoctrineManagerRegistry $doctrine,LebelleOptionAnnonceRepository $LebelleOptionAnnonceRepository,OptionAnnonceRepository $OptionAnnonceRepository,CategoryRepository $CategoryRepository,): Response
    {

        $annonce = new Annonce();
        $form = $this->createForm(Annonce2Type::class, $annonce);
        $form->handleRequest($request);
        $annonce->setStatus("0");
        
        //user
        $repository = $doctrine->getRepository(User::class);
        
        $email = $this->getUser()->getUserIdentifier();
        $user = $repository->findOneBy(array('email' => $email));
        $user->addAnnonce($annonce);
        
        // $imageAnnonce = new ImageAnnonce();
        // $form2 = $this->createForm(ImageAnnonceType::class, $imageAnnonce);
        // $form2->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $annonceRepository->save($annonce, true);
            
            
            $uploadedFiles = $form['imageFile']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/img_annonces/';
            $bdddes = "/img_annonces/";

            // var_dump($uploadedFiles);
            foreach($uploadedFiles as $uploadedFile){
                // var_dump($);
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                // var_dump($originalFilename);
                $cat = array(" ", "_", "-", "1", "2", "3", "4", "5", "6", "7", "8", "9");
                $name = str_replace($cat,'',$originalFilename);
                $newFilename = $name.'-'.uniqid().'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                // var_dump($newFilename);
                $image = new ImageAnnonce();
                $image->setIdAnnonce($annonce);
                $image->setLien($bdddes.$newFilename);
                $image->setStatus('1');
                $imageAnnonceRepository->save($image,true);
            }

            $host = "localhost";
            $username = "root";
            $password = "";
            $database = "atypikhouse";
            
            $conn = mysqli_connect($host, $username, $password, $database);
            
            if (!$conn) {
              // Gérez les erreurs de connexion ici
            }
            foreach($_POST['values'] as $key => $value){
                if ($_POST['values'][$key]) {
                    $val = $_POST['values'][$key];
                    $id_a= $annonce;
                    $inse = "INSERT INTO `option_annonce`(`id_annonce_id`, `id_libelle_id`, `valeur`) VALUES ($id_a,$key,'$val')";
                    // var_dump($inse);
                    mysqli_query($conn, $inse);
                }
            }
                    //   die;
            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);

        }
        return $this->renderForm('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
            'CategoryRepository'=>$CategoryRepository->findAll(),
            'LebelleOptionAnnonceRepository'=>$LebelleOptionAnnonceRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'app_annonce_show', methods: ['GET'])]
    public function show(Annonce $annonce,ImageAnnonceRepository $im,OptionAnnonceRepository $opt,LebelleOptionAnnonceRepository $loannonce,UserRepository $user,CategoryRepository $CategoryRepository): Response
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
            'img'=>$im->findAll(),
            'opt'=>$opt->findAll(),
            'loannonce'=>$loannonce->findAll(),
            'CategoryRepository'=>$CategoryRepository->findAll(),
            'user'=>$user->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository,SluggerInterface $slugger): Response
    {
        $form = $this->createForm(Annonce2Type::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonceRepository->save($annonce, true);
            $uploadedFile = $form['imageFile']->getData();
            $destination = $this->getParameter('kernel.project_dir').'/public/img_annonces';

            $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
            $uploadedFile->move(
                $destination,
                $newFilename
            );


            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $annonceRepository->remove($annonce, true);
        }

        return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
    }
}
