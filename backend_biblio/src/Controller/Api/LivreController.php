<?php

namespace App\Controller\Api;

use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Livre;

#[Route('/api')]
class LivreController extends AbstractController
{
    #[Route('/livres/recherche', name: 'app_api_livre_search', methods: ['GET'])]
    public function search(Request $request, LivreRepository $livreRepository): JsonResponse
    {
    // On récupère 'q' depuis l'URL : /api/livres/recherche?q=...
    $query = $request->query->get('q', '');

    if (empty($query)) {
        // Optionnel : retourner tous les livres ou une liste vide
        return $this->json($livreRepository->findAll(), 200, [], ['groups' => 'livre:read']);
    }

    $livres = $livreRepository->findByTitle($query);

    return $this->json($livres, 200, [], ['groups' => 'livre:read']);
    }
    #[Route('/livres', name: 'app_api_livre_index', methods: ['GET'])]
    public function index(LivreRepository $livreRepository): JsonResponse
    {
        $livres = $livreRepository->findAll();
        return $this->json($livres, 200, [], ['groups' => 'livre:read']);
    }

    #[Route('/livres/{id}', name: 'app_api_livre_show', methods: ['GET'])]
    public function show(Livre $livre): JsonResponse
    {
        return $this->json($livre, 200, [], ['groups' => 'livre:read']);
    }

}
