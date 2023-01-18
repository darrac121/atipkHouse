<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Annonce;
use App\Repository\AnnonceRepository;


class SitemapController extends AbstractController
{
    /**
     * @Route("sitemap.xml", name="sitemap", defaults={"_format"="xml"})
     * @param Request $request
     * @return Response
     */
    public function index(Request $request,AnnonceRepository $annonceRepository)
    {
        // Nous récupérons le nom d'hôte depuis l'URL
        $hostname = $request->getSchemeAndHttpHost();
        // On ajoute les URLs "statiques"
        $date = date('Y-m-d');
        // Fabrication de la réponse XML
        $response = new Response(
            $this->renderView('sitemap/index.html.twig', [
                'annonces' => $annonceRepository->findAll(),
                'date'=>$date,
                'hostname' => $hostname]
            )
        );

        // Ajout des entêtes
        $response->headers->set('Content-Type', 'text/xml');

        // On envoie la réponse
        return $response;
    }
}