<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\ImageAnnonce;
use App\Entity\User;
use App\Form\Annonce2Type;
use App\Form\AnnonceType;
use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry as DoctrineManagerRegistry;
use Symfony\Component\Mime\Email;



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
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use SebastianBergmann\Environment\Console;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Mailer\MailerInterface;

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
    #[Route('/attente', name: 'app_annonce_attente', methods: ['GET'])]
    public function attente(AnnonceRepository $annonceRepository,ImageAnnonceRepository $im,Annonce $annonce): Response
    {
        
        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonceRepository->findAll(),
        ]);
    }
    #[Route('/commande', name:'app_annonce_commande')]
    public function commande(AnnonceRepository $annonceRepository): Response
    {
        return $this->render('annonce/commande.html.twig', [
            'annonces' => $annonceRepository->findAll(),
        ]);
    }
    #[Route('/annoncefilter', name: 'app_annonce_filter')]
    public function annoncebycategory(Request $request,AnnonceRepository $annonceRepository, CategoryRepository $categoryRepository,ImageAnnonceRepository $im,UserRepository $user): Response
    {
        $id = $request->query->get('idcategory');
        
        return $this->render('annonce/category.html.twig', [
            'annonces' => $annonceRepository->findBy(["idCategory"=> $id]),
            'imgs'=>$im->findAll(),
            'user'=>$user->findAll(),
        ]);
    }
    #[Route( name: 'app_annonce_sugestion')]
    public function sugestion(Request $request,AnnonceRepository $annonceRepository, CategoryRepository $categoryRepository,ImageAnnonceRepository $im,UserRepository $user,int $id): Response
    {
        
        return $this->render('annonce/sugestion.html.twig', [
            'annonces' => $annonceRepository->findBy(["idCategory"=> $id]),
            'imgs'=>$im->findAll(),
            'user'=>$user->findAll(),
        ]);
    }
    #[Route('/admin', name: 'app_annonce_admin' )]
    public function showannonceattente(AnnonceRepository $annonceRepository,ImageAnnonceRepository $im,UserRepository $user): Response
    {
        return $this->render('annonce/admin.html.twig', [
            'annonces' => $annonceRepository->findAll(),
        ]);
    }
    #[Route('/mesannonces', name: 'app_annonce_mesannonce')]
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
    public function new(Request $request, annonceRepository $annonceRepository,ImageAnnonceRepository $imageAnnonceRepository, DoctrineManagerRegistry $doctrine,LebelleOptionAnnonceRepository $LebelleOptionAnnonceRepository,MailerInterface $mailer,CategoryRepository $CategoryRepository): Response
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
            $annonce->setIdCategory($_POST['categorie']);
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
            $email = (new Email())
            ->from('atipikhouse@dev3-g3-lz-es-zt-fb.go.yj.fr')
            ->to('atipikdev3g3@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Nouvelle annonce en attente')
            ->html('<p>Une nouvelle annonce a été créé.</br>Allez sur le site <a href="dev3-g3-lz-es-zt-fb.go.yj.fr">AtipikHouse</p></br></br>Bien à vous,</br>AtipikHouse');

            $mailer->send($email);
            
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
    public function show(Annonce $annonce,ImageAnnonceRepository $im,OptionAnnonceRepository $opt,LebelleOptionAnnonceRepository $loannonce,UserRepository $user,CategoryRepository $CategoryRepository, LebelleOptionAnnonceRepository $LebelleOptionAnnonceRepository): Response
    
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
            'img'=>$im->findAll(),
            'opt'=>$opt->findAll(),
            'loannonce'=>$loannonce->findAll(),
            'CategoryRepository'=>$CategoryRepository->findAll(),
            'user'=>$user->findAll(),
            'LebelleOptionAnnonceRepository'=>$LebelleOptionAnnonceRepository->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request,LebelleOptionAnnonceRepository $loannonce, Annonce $annonce, AnnonceRepository $annonceRepository,SluggerInterface $slugger
    ,OptionAnnonceRepository $opt,ImageAnnonceRepository $im, LebelleOptionAnnonceRepository $LebelleOptionAnnonceRepository,UserRepository $user,CategoryRepository $CategoryRepository): Response
    {
        $form = $this->createForm(AnnonceType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonceRepository->save($annonce, true);
            $uploadedFile = $form['imageFile']->getData();
            if($uploadedFile != null){
                $destination = $this->getParameter('kernel.project_dir').'/public/img_annonces';

                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
            }
            


            return $this->render('annonce/show.html.twig', [
                'annonce' => $annonce,
                'opt'=>$opt->findAll(),
                'img'=>$im->findAll(),
                'loannonce'=>$loannonce->findAll(),
                'CategoryRepository'=>$CategoryRepository->findAll(),
                'user'=>$user->findAll(),
                'LebelleOptionAnnonceRepository'=>$LebelleOptionAnnonceRepository->findAll(),
            ]);
        }

        return $this->renderForm('annonce/edit.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
            'opt'=>$opt->findAll(),
            'LebelleOptionAnnonceRepository'=>$LebelleOptionAnnonceRepository->findAll(),
            'CategoryRepository'=>$CategoryRepository->findAll(),
            'user'=>$user->findAll(),
        ]);
    }
    #[Route('/{id}/active', name: 'app_annonce_active', methods: ['GET', 'POST'])]
    public function active(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository, EntityManagerInterface $em): Response
    {

        $annonce->setStatus(1);
        $em->flush();
        return $this->redirectToRoute('app_annonce_admin', [], Response::HTTP_SEE_OTHER);
        


    }
    #[Route('/{id}/desactive', name: 'app_annonce_desactive', methods: ['GET', 'POST'])]
    public function desactive(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository, EntityManagerInterface $em): Response
    {

        $annonce->setStatus(0);
        $em->flush();
        return $this->redirectToRoute('app_annonce_admin', [], Response::HTTP_SEE_OTHER);
        


    }

    #[Route('/{id}', name: 'app_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$annonce->getId(), $request->request->get('_token'))) {
            $annonceRepository->remove($annonce, true);
        }

        return $this->redirectToRoute('app_annonce_admin', [], Response::HTTP_SEE_OTHER);
    }
}
