<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class ApiLoginController extends AbstractController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST', 'GET'])]
    public function index(): Response
    {
        
        $user = $this->getUser();
        if (null === $user) {
                return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }
            
        return $this->json([
            'user' => $user->getUserIdentifier(),
            'role' => $user->getRoles(),
            'nom' => $user->getName(),
            'prenom' => $user->getFirstname()
            
        ]);
    }
}