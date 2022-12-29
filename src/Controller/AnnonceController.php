<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\ImageAnnonce;
use App\Form\Annonce2Type;
use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use App\Form\ImageAnnonceType;

use App\Repository\LebelleOptionAnnonceRepository;
use App\Repository\ImageAnnonceRepository;
//use Gedmo\Sluggable\Util\Urlizer;
use App\Controller\Urlizer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use App\Repository\OptionAnnonceRepository;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\UserRepository;



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

    #[Route('/new', name: 'app_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, annonceRepository $annonceRepository,ImageAnnonceRepository $imageAnnonceRepository,SluggerInterface $slugger): Response
    {
        $annonce = new Annonce();
        $form = $this->createForm(Annonce2Type::class, $annonce);
        $form->handleRequest($request);

        
        // $imageAnnonce = new ImageAnnonce();
        // $form2 = $this->createForm(ImageAnnonceType::class, $imageAnnonce);
        // $form2->handleRequest($request);

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
            //create new entity image
            $image = new ImageAnnonce();
            //$image->setIdAnnonce($annonce);
            $image->setLien($newFilename);


            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);

        }
        return $this->renderForm('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annonce_show', methods: ['GET'])]
    public function show(Annonce $annonce,ImageAnnonceRepository $im,OptionAnnonceRepository $opt,LebelleOptionAnnonceRepository $loannonce,UserRepository $user): Response
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
            'img'=>$im->findAll(),
            'opt'=>$opt->findAll(),
            'loannonce'=>$loannonce->findAll(),
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
            //create new entity image
            $image = new ImageAnnonce();
            $image->setIdAnnonce($annonce);
            $image->setLien($newFilename);

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
