<?php

namespace App\Controller;

use App\Entity\DocumentProprietaire;
use App\Form\DocumentProprietaireType;
use App\Repository\DocumentProprietaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/document/proprietaire')]
class DocumentProprietaireController extends AbstractController
{
    #[Route('/', name: 'app_document_proprietaire_index', methods: ['GET'])]
    public function index(DocumentProprietaireRepository $documentProprietaireRepository): Response
    {
        return $this->render('document_proprietaire/index.html.twig', [
            'document_proprietaires' => $documentProprietaireRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_document_proprietaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DocumentProprietaireRepository $documentProprietaireRepository): Response
    {
        $documentProprietaire = new DocumentProprietaire();
        $form = $this->createForm(DocumentProprietaireType::class, $documentProprietaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $documentProprietaireRepository->save($documentProprietaire, true);

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
