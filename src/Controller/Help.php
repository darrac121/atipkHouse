<?php
// src/Controller/Help.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/help')]
class Help extends AbstractController
{
    public function help(): Response
    {
        $number = random_int(0, 100);

        return $this->render('Help/Help.html.twig', [
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