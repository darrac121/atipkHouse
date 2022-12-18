<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\LebelleOptionAnnonce;
use App\Form\LebelleOptionAnnonceType;
use App\Repository\LebelleOptionAnnonceRepository;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as DoctrineManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/lebelle/option/annonce')]
class LebelleOptionAnnonceController extends AbstractController
{
    #[Route('/', name: 'app_lebelle_option_annonce_index', methods: ['GET'])]
    public function index(LebelleOptionAnnonceRepository $lebelleOptionAnnonceRepository): Response
    {
        return $this->render('lebelle_option_annonce/index.html.twig', [
            'lebelle_option_annonces' => $lebelleOptionAnnonceRepository->findAll(),
        ]);
    }
    
    #[Route('/showlebelle', name: 'app_lebelle_option_annonce_show_lebelle')]
    public function showlebelle(ManagerRegistry $doctrine, int $id): Response
    {
        $querryresult = $doctrine->getRepository(LebelleOptionAnnonce::class)->findBy(
            ["idCategory" => $id]);

        // render
        return $this->render('category/_showlebelle.html.twig', [
            'lebelle_option_annonces' => $querryresult,
        ]);
    }

    #[Route('/new', name: 'app_lebelle_option_annonce_new', methods: ['GET', 'POST'])]
    public function new(Request $request, LebelleOptionAnnonceRepository $lebelleOptionAnnonceRepository, DoctrineManagerRegistry $doctrine): Response
    {
        $lebelleOptionAnnonce = new LebelleOptionAnnonce();
        $form = $this->createForm(LebelleOptionAnnonceType::class, $lebelleOptionAnnonce);
        $form->handleRequest($request);

       

        $idcategory = $request->query->get('idcategory');
        
        $repository = $doctrine->getRepository(Category::class);
        $category = $repository->find($idcategory);

        $lebelleOptionAnnonce->setIdCategory($category);

        if ($form->isSubmitted() && $form->isValid()) {
            $lebelleOptionAnnonceRepository->save($lebelleOptionAnnonce, true);

            return $this->redirectToRoute('app_lebelle_option_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lebelle_option_annonce/new.html.twig', [
            'lebelle_option_annonce' => $lebelleOptionAnnonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lebelle_option_annonce_show', methods: ['GET'])]
    public function show(LebelleOptionAnnonce $lebelleOptionAnnonce): Response
    {
        return $this->render('lebelle_option_annonce/show.html.twig', [
            'lebelle_option_annonce' => $lebelleOptionAnnonce,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lebelle_option_annonce_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, LebelleOptionAnnonce $lebelleOptionAnnonce, LebelleOptionAnnonceRepository $lebelleOptionAnnonceRepository): Response
    {
        $form = $this->createForm(LebelleOptionAnnonceType::class, $lebelleOptionAnnonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $lebelleOptionAnnonceRepository->save($lebelleOptionAnnonce, true);

            return $this->redirectToRoute('app_lebelle_option_annonce_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('lebelle_option_annonce/edit.html.twig', [
            'lebelle_option_annonce' => $lebelleOptionAnnonce,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lebelle_option_annonce_delete', methods: ['POST'])]
    public function delete(Request $request, LebelleOptionAnnonce $lebelleOptionAnnonce, LebelleOptionAnnonceRepository $lebelleOptionAnnonceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$lebelleOptionAnnonce->getId(), $request->request->get('_token'))) {
            $lebelleOptionAnnonceRepository->remove($lebelleOptionAnnonce, true);
        }

        return $this->redirectToRoute('app_lebelle_option_annonce_index', [], Response::HTTP_SEE_OTHER);
    }
}
