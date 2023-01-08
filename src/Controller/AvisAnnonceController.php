<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\AvisAnnonce;
use App\Form\AvisAnnonceType;
use App\Repository\AvisAnnonceRepository;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/avis/annonce')]
class AvisAnnonceController extends AbstractController
{
    #[Route('/', name: 'app_avis_annonce_index', methods: ['GET'])]
    public function index(AvisAnnonceRepository $avisAnnonceRepository): Response
    {
        return $this->render('avis_annonce/index.html.twig', [
            'avis_annonces' => $avisAnnonceRepository->findAll(),
        ]);
    }

    public function avisannonce(AvisAnnonceRepository $avisAnnonceRepository, ManagerRegistry $doctrine, int $id): Response
    {

        $querryresult = $doctrine->getRepository(AvisAnnonce::class)->findBy(
            ["idAnnonce" => $id]);

        return $this->render('avis_annonce/_avisannonce.html.twig', [
            'avis_annonces' => $querryresult,
        ]);
    }

    #[Route('/new', name: 'app_avis_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AvisAnnonceRepository $avisAnnonceRepository, ManagerRegistry $doctrine,UserRepository $userRepository): Response
    {
       $idannonce = $request->query->get('idannonce');
       $repository = $doctrine->getRepository(Annonce::class);
       $annonce = $repository->find($idannonce);

        $avisAnnonce = new AvisAnnonce();

        $avisAnnonce->setIdAnnonce($annonce);
        //user
        
        $email = $this->getUser()->getUserIdentifier();
        $user = $userRepository->findOneBy(array('email' => $email));
        $avisAnnonce->setIdUser($user);

        $form = $this->createForm(AvisAnnonceType::class, $avisAnnonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avisAnnonceRepository->save($avisAnnonce, true);

            return $this->redirectToRoute('app_avis_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('avis_annonce/new.html.twig', [
            'avis_annonce' => $avisAnnonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_avis_annonce_show', methods: ['GET'])]
    public function show(AvisAnnonce $avisAnnonce): Response
    {
        return $this->render('avis_annonce/show.html.twig', [
            'avis_annonce' => $avisAnnonce,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_avis_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AvisAnnonce $avisAnnonce, AvisAnnonceRepository $avisAnnonceRepository): Response
    {
        $form = $this->createForm(AvisAnnonceType::class, $avisAnnonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $avisAnnonceRepository->save($avisAnnonce, true);

            return $this->redirectToRoute('app_avis_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('avis_annonce/edit.html.twig', [
            'avis_annonce' => $avisAnnonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_avis_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, AvisAnnonce $avisAnnonce, AvisAnnonceRepository $avisAnnonceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$avisAnnonce->getId(), $request->request->get('_token'))) {
            $avisAnnonceRepository->remove($avisAnnonce, true);
        }

        return $this->redirectToRoute('app_avis_annonce_index', [], Response::HTTP_SEE_OTHER);
    }
}
