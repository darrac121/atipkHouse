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
        // $img = '../public/img_website/logo.png';
        $img = '/img_website/logo.png';

        return $this->render('Help/Help.html.twig', [
            'img' => $img,
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