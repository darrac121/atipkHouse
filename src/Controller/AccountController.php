<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/account')]
class AccountController extends AbstractController
{
    #[Route('/', name: 'app_account')]
    public function index(Session $session): Response
    {
        
        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController',
        ]);
    }
    #[Route('/{id}/desactiver', name: 'app_account_desactive', methods: ['GET', 'POST'])]
    public function active(Request $request, User $user, UserRepository $userRepository, EntityManagerInterface $em): Response
    {

        $user->setStatus(1);
        $em->flush();
        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);


    }
    #[Route('/{id}/edit', name: 'app_account_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
