<?php
// src/Controller/Home.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/home')]
class Home extends AbstractController
{
    public function home(): Response
    {
        $number = random_int(0, 100);

        return $this->render('home/home.html.twig', [
            'number' => $number,
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