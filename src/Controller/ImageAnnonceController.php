<?php

namespace App\Controller;

use App\Entity\ImageAnnonce;
use App\Form\ImageAnnonceType;
use App\Repository\ImageAnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/image/annonce')]
class ImageAnnonceController extends AbstractController
{
    #[Route('/', name: 'app_image_annonce_index', methods: ['GET'])]
    public function index(ImageAnnonceRepository $imageAnnonceRepository): Response
    {
        return $this->render('image_annonce/index.html.twig', [
            'image_annonces' => $imageAnnonceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_image_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ImageAnnonceRepository $imageAnnonceRepository): Response
    {
        $imageAnnonce = new ImageAnnonce();
        $form = $this->createForm(ImageAnnonceType::class, $imageAnnonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageAnnonceRepository->save($imageAnnonce, true);
            /*

            /** @var UploadedFile $brochureFile 
            $brochureFile = $form->get('lien')->getData();
            
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
            }*/
            return $this->redirectToRoute('app_image_annonce_index', [], Response::HTTP_SEE_OTHER);
        
        }

        return $this->renderForm('image_annonce/new.html.twig', [
            'image_annonce' => $imageAnnonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_image_annonce_show', methods: ['GET'])]
    public function show(ImageAnnonce $imageAnnonce): Response
    {
        return $this->render('image_annonce/show.html.twig', [
            'image_annonce' => $imageAnnonce,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_image_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ImageAnnonce $imageAnnonce, ImageAnnonceRepository $imageAnnonceRepository): Response
    {
        $form = $this->createForm(ImageAnnonceType::class, $imageAnnonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageAnnonceRepository->save($imageAnnonce, true);

            return $this->redirectToRoute('app_image_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('image_annonce/edit.html.twig', [
            'image_annonce' => $imageAnnonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_image_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, ImageAnnonce $imageAnnonce, ImageAnnonceRepository $imageAnnonceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$imageAnnonce->getId(), $request->request->get('_token'))) {
            $imageAnnonceRepository->remove($imageAnnonce, true);
        }

        return $this->redirectToRoute('app_image_annonce_index', [], Response::HTTP_SEE_OTHER);
    }
}
