<?php

namespace App\Controller\Api;

use App\Entity\Auteur;
use App\Repository\AuteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class AuteurController extends AbstractController
{
    #[Route('/auteurs', name: 'app_api_auteur_index', methods: ['GET'])]
    public function index(AuteurRepository $auteurRepository): JsonResponse
    {
        $auteurs = $auteurRepository->findAll();
        return $this->json($auteurs, 200, [], ['groups' => 'auteur:read']);
    }
    #[Route('/auteurs/{id}', name: 'app_api_auteur_show', methods: ['GET'])]
    public function show(Auteur $auteur): JsonResponse
    {
        return $this->json($auteur, 200, [], ['groups' => 'auteur:read']);
    }
}
