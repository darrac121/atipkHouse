<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Form\Annonce2Type;
use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\LebelleOptionAnnonce;
use App\Entity\ImageAnnonce;


use App\Form\ImageAnnonceType;

use App\Repository\LebelleOptionAnnonceRepository;
use App\Repository\ImageAnnonceRepository;


use App\Entity\OptionAnnonce;
use App\Repository\OptionAnnonceRepository;



use Symfony\Component\HttpFoundation\File\UploadedFile;


#[Route('/annonce')]
class AnnonceController extends AbstractController
{
    #[Route('/', name: 'app_annonce_index', methods: ['GET'])]
    public function index(AnnonceRepository $annonceRepository,ImageAnnonceRepository $im): Response
    {
        return $this->render('annonce/index.html.twig', [
            'annonces' => $annonceRepository->findAll(),
            'imgs'=>$im->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AnnonceRepository $annonceRepository,ImageAnnonceRepository $imageAnnonceRepository): Response
    {
        $annonce = new Annonce();
        $form = $this->createForm(Annonce2Type::class, $annonce);
        $form->handleRequest($request);

        
        // $imageAnnonce = new ImageAnnonce();
        // $form2 = $this->createForm(ImageAnnonceType::class, $imageAnnonce);
        // $form2->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonceRepository->save($annonce, true);
           /*
            if ($form2->isSubmitted() && $form2->isValid()) {
                $imageAnnonceRepository->save($imageAnnonce, true);
                
                
                $imageAnnonce->setIdAnnonce($annonce->getId());
                // setIdAnnonce
                //// @var UploadedFile $brochureFile /
                $brochureFile = $form2->get('lien')->getData();
                
                // var_dump($brochureFile);
                // die;
                
                if ($brochureFile) {
                    $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $originalFilename;
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();
                    // Move the file to the directory where brochures are stored
                    try {
                        $brochureFile->move(
                            $this->getParameter('imageAnnonce'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    $imageAnnonce->setLien($this->getParameter('imageAnnonce_bdd').$newFilename);
                    $imageAnnonceRepository->save($imageAnnonce, true);
                }

            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);
            }
            */
            return $this->redirectToRoute('app_annonce_index', [], Response::HTTP_SEE_OTHER);

        }
        return $this->renderForm('annonce/new.html.twig', [
            'annonce' => $annonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_annonce_show', methods: ['GET'])]
    public function show(Annonce $annonce,ImageAnnonceRepository $im,OptionAnnonceRepository $opt,LebelleOptionAnnonceRepository $loannonce): Response
    {
        return $this->render('annonce/show.html.twig', [
            'annonce' => $annonce,
            'img'=>$im->findAll(),
            'opt'=>$opt->findAll(),
            'loannonce'=>$loannonce->findAll(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Annonce $annonce, AnnonceRepository $annonceRepository): Response
    {
        $form = $this->createForm(Annonce2Type::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $annonceRepository->save($annonce, true);

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
