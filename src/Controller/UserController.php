<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EmailType;
use App\Form\User1Type;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Form\TokenType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

#[Route('/user')]
class UserController extends AbstractController
{

    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    #[Route('/attente', name: 'app_user_attente')]
    public function proprietaireattente(UserRepository $userRepository): Response
    {
        return $this->render('user/propietaireattente.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $userPasswordHasher,): Response
    {
        $user = new User();
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $userRepository->save($user, true);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            return $this->redirectToRoute('app_document_proprietaire_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('/register.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/proprietaire', methods: ['GET', 'POST'])]
    public function addProprietaire(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager,EmailVerifier $emailVerifier): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setRoles(["ROLE_PRO"]);
            $user->setStatus(0);
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            // generate a signed url and email it to the user
            $emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('atipikhouse@dev3-g3-lz-es-zt-fb.go.yj.fr', 'AtipikHouse'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            return $this->redirectToRoute('home');
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(User1Type::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'form' => $form,
        ]);
    }
    
    #[Route('/{id}/active', name: 'app_user_active', methods: ['GET', 'POST'])]
    public function active(Request $request, User $user, UserRepository $userRepository, EntityManagerInterface $em, MailerInterface $mailer): Response
    {

        $user->setStatus(1);
        $em->flush();
        $email = (new Email())
                ->from('atipikhouse@dev3-g3-lz-es-zt-fb.go.yj.fr')
                ->to($user->getEmail())
                //->cc('cc@example.com')
                //->bcc('bcc@example.com')
                //->replyTo('fabien@example.com')
                //->priority(Email::PRIORITY_HIGH)
                ->subject('Compte Propriétaire activé')
                ->html('<p>Votre compte a été créé.</br>Allez sur le site <a href="dev3-g3-lz-es-zt-fb.go.yj.fr">AtipikHouse</p></br></br>Bien à vous,</br>AtipikHouse');
        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);


    }
    #[Route('/{id}/desactive', name: 'app_user_desactive', methods: ['GET', 'POST'])]
    public function desactive(Request $request, User $user, UserRepository $userRepository, EntityManagerInterface $em): Response
    {

        $user->setStatus(3);
        $em->flush();
        return $this->redirectToRoute('app_logout', [], Response::HTTP_SEE_OTHER);


    }

    #[Route('/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
