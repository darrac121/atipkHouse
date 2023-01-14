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

    public function cgu(): Response
    {
        // $img = '../public/img_website/logo.png';
        $img = '/img_website/logo.png';

        return $this->render('cgu/index.html.twig', [
            'img' => $img,
        ]);
    }
    
    public function motdepasseoublie(): Response
    {
        // $img = '../public/img_website/logo.png';
        $img = '/img_website/logo.png';

        return $this->render('mot_de_passe_oublier/index.html.twig', [
            'img' => $img,
        ]);
    }
    
    
    public function mentionlegal(): Response
    {
        // $img = '../public/img_website/logo.png';
        $img = '/img_website/logo.png';

        return $this->render('mentionlegal/index.html.twig', [
            'img' => $img,
        ]);
    }

    public function info(): Response
    {
        // $img = '../public/img_website/logo.png';
        $img1 = '/img_website/image_info_page/image1.webp';
        $img2 = '/img_website/image_info_page/image2.webp';
        $img3 = '/img_website/image_info_page/image3.webp';
        
        return $this->render('info/index.html.twig', [
            'img1' => $img3,
            'img2' => $img2,
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