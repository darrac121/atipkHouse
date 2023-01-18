<?php

namespace App\Controller;

use App\Entity\DocumentProprietaire;
use App\Entity\User;
use App\Form\DocumentProprietaireType;
use App\Repository\DocumentProprietaireRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as DoctrineManagerRegistry;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;


#[Route('/document')]
class DocumentProprietaireController extends AbstractController
{
    #[Route('/', name: 'app_document_proprietaire_index', methods: ['GET'])]
    public function index(DocumentProprietaireRepository $documentProprietaireRepository): Response
    {
        return $this->render('document_proprietaire/index.html.twig', [
            'document_proprietaires' => $documentProprietaireRepository->findAll(),
        ]);
    }
    #[Route('/{id}/docs', name: 'app_document_proprietaire_pro', methods: ['GET'])]
    public function showspro(DocumentProprietaireRepository $documentProprietaireRepository,UserRepository $userRepository, int $id): Response
    {
        $user = $userRepository->find($id);
        return $this->render('document_proprietaire/index.html.twig', [
            'document_proprietaires' => $documentProprietaireRepository->findBy(['idUser' => $user]),
        ]);
    }

    #[Route('/new', name: 'app_document_proprietaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DocumentProprietaireRepository $documentProprietaireRepository, DoctrineManagerRegistry $doctrine, SluggerInterface $slugger): Response
    {
        $documentProprietaire = new DocumentProprietaire();
        $form = $this->createForm(DocumentProprietaireType::class, $documentProprietaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $destination = $this->getParameter('kernel.project_dir').'/public/uploads/docpro/';
            $uploadedFile = $form['lien']->getData();
            $bdddes = "/uploads/docpro/";
            if ($uploadedFile) {
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$uploadedFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $uploadedFile->move(
                        $destination,
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $documentProprietaire->setLien($bdddes.$newFilename);

                $documentProprietaire->setStatus(0);
                $repository = $doctrine->getRepository(User::class);
        
                $email = $this->getUser()->getUserIdentifier();
                $user = $repository->findOneBy(array('email' => $email));
                $documentProprietaire->setIdUser($user);

                $documentProprietaireRepository->save($documentProprietaire, true);
                
            }

            return $this->redirectToRoute('app_document_proprietaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('document_proprietaire/new.html.twig', [
            'document_proprietaire' => $documentProprietaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_document_proprietaire_show', methods: ['GET'])]
    public function show(DocumentProprietaire $documentProprietaire): Response
    {
        return $this->render('document_proprietaire/show.html.twig', [
            'document_proprietaire' => $documentProprietaire,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_document_proprietaire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, DocumentProprietaire $documentProprietaire, DocumentProprietaireRepository $documentProprietaireRepository): Response
    {
        $form = $this->createForm(DocumentProprietaireType::class, $documentProprietaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $documentProprietaireRepository->save($documentProprietaire, true);

            return $this->redirectToRoute('app_document_proprietaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('document_proprietaire/edit.html.twig', [
            'document_proprietaire' => $documentProprietaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_document_proprietaire_delete', methods: ['POST'])]
    public function delete(Request $request, DocumentProprietaire $documentProprietaire, DocumentProprietaireRepository $documentProprietaireRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$documentProprietaire->getId(), $request->request->get('_token'))) {
            $documentProprietaireRepository->remove($documentProprietaire, true);
        }

        return $this->redirectToRoute('app_document_proprietaire_index', [], Response::HTTP_SEE_OTHER);
    }
}
