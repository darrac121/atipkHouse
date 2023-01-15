<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\User;
use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\AnnonceRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry as DoctrineManagerRegistry;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    #[Route('/', name: 'app_reservation_index', methods: ['GET'])]
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

    public function commande(ReservationRepository $reservationRepository, AnnonceRepository $annonceRepository, int $id): Response
    {
        $annonce = $annonceRepository->find($id);
        return $this->render('reservation/_commande.html.twig', [
            'reservations' => $reservationRepository->findBy(["idAnnonce"=>$annonce]),
        ]);
    }

    #[Route('/new', name: 'app_reservation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReservationRepository $reservationRepository, DoctrineManagerRegistry $doctrine): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        //add annonce
        $idannonce = $request->query->get('idannonce');

        $repository = $doctrine->getRepository(Annonce::class);
        $annonce = $repository->find($idannonce);
        $reservation->setIdAnnonce($annonce);

        //set user annonce 
        $repository2 = $doctrine->getRepository(User::class);
        $email = $this->getUser()->getUserIdentifier();
        $user = $repository2->findOneBy(array('email' => $email));
        $user->addIdUserReservation($reservation);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->save($reservation, true);

            return $this->redirectToRoute('annonce', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_show', methods: ['GET'])]
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reservationRepository->save($reservation, true);

            return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form,
        ]);
    }
    #[Route('/{id}/accepte', name: 'app_reservation_accepte', methods: ['GET', 'POST'])]
    public function accepte(Request $request, Reservation $reservation, ReservationRepository $reservationRepository, EntityManagerInterface $em): Response
    {

        $reservation->setStatue(1);
        $em->flush();
        return $this->redirectToRoute('app_annonce_commande', [], Response::HTTP_SEE_OTHER);


    }
    #[Route('/{id}/annuller', name: 'app_reservation_annuler', methods: ['GET', 'POST'])]
    public function annuller(Request $request, Reservation $reservation, ReservationRepository $reservationRepository, EntityManagerInterface $em): Response
    {

        $reservation->setStatue(3);
        $em->flush();
        return $this->redirectToRoute('app_annonce_commande', [], Response::HTTP_SEE_OTHER);


    }

    #[Route('/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation, ReservationRepository $reservationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getId(), $request->request->get('_token'))) {
            $reservationRepository->remove($reservation, true);
        }

        return $this->redirectToRoute('app_reservation_index', [], Response::HTTP_SEE_OTHER);
    }
}
