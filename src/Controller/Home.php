<?php
// src/Controller/Home.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

use App\Repository\LebelleOptionAnnonceRepository;
use App\Repository\ImageAnnonceRepository;
use App\Repository\AnnonceRepository;
use App\Repository\AvisAnnonceRepository;



#[Route('/home')]
class Home extends AbstractController
{
    public function home(AuthenticationUtils $authenticationUtils,AnnonceRepository $annonceRepository,ImageAnnonceRepository $im,AvisAnnonceRepository $avis): Response
    {
        $number = random_int(0, 100);
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
        // $idd = $authenticationUtils;
        // $id = $authenticationUtils->getAll();
        // var_dump($authenticationUtils);
        // die;
        return $this->render('home/home.html.twig', [
            'number' => $number,
            'last_username' => $lastUsername,
            'annonces' => $annonceRepository->findAll(),
            'imgs'=>$im->findAll(),
            'avis'=>$avis->findAll(),
            
            // 'id' => $id,
        ]);
    }
}


/*
class LuckyController extends AbstractController
{
    public function number(): Response
    {

        return new Response(
            'hello world'
        );
    }
}
*/