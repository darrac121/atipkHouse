<?php

namespace App\Controller;

use App\Entity\OptionAnnonce;
use App\Form\OptionAnnonceType;
use App\Repository\OptionAnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/option/annonce')]
class OptionAnnonceController extends AbstractController
{
    #[Route('/', name: 'app_option_annonce_index', methods: ['GET'])]
    public function index(OptionAnnonceRepository $optionAnnonceRepository): Response
    {
        return $this->render('option_annonce/index.html.twig', [
            'option_annonces' => $optionAnnonceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_option_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, OptionAnnonceRepository $optionAnnonceRepository): Response
    {
        $optionAnnonce = new OptionAnnonce();
        $form = $this->createForm(OptionAnnonceType::class, $optionAnnonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $optionAnnonceRepository->save($optionAnnonce, true);

            return $this->redirectToRoute('app_option_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('option_annonce/new.html.twig', [
            'option_annonce' => $optionAnnonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_option_annonce_show', methods: ['GET'])]
    public function show(OptionAnnonce $optionAnnonce): Response
    {
        return $this->render('option_annonce/show.html.twig', [
            'option_annonce' => $optionAnnonce,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_option_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, OptionAnnonce $optionAnnonce, OptionAnnonceRepository $optionAnnonceRepository): Response
    {
        $form = $this->createForm(OptionAnnonceType::class, $optionAnnonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $optionAnnonceRepository->save($optionAnnonce, true);

            return $this->redirectToRoute('app_annonce_mesannonce', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('option_annonce/edit.html.twig', [
            'option_annonce' => $optionAnnonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_option_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, OptionAnnonce $optionAnnonce, OptionAnnonceRepository $optionAnnonceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$optionAnnonce->getId(), $request->request->get('_token'))) {
            $optionAnnonceRepository->remove($optionAnnonce, true);
        }

        return $this->redirectToRoute('app_option_annonce_index', [], Response::HTTP_SEE_OTHER);
    }
}
