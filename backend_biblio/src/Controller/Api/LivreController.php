<?php

namespace App\Controller\Api;

use App\Repository\LivreRepository;
use App\Repository\RechercheRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Livre;

#[Route('/api')]
class LivreController extends AbstractController
{
    #[Route('/livres/recherche', name: 'app_api_livre_search', methods: ['GET'])]
    public function search(Request $request, RechercheRepository $rechercheRepository): JsonResponse
    {
        $titre = trim((string) $request->query->get('titre', ''));
        $auteur = trim((string) $request->query->get('auteur', ''));
        $categorie = trim((string) $request->query->get('categorie', ''));
        $langue = trim((string) $request->query->get('langue', ''));
        $dateMin = $this->parseDate($request->query->get('dateMin'));
        $dateMax = $this->parseDate($request->query->get('dateMax'));

        if ($request->query->has('dateMin') && $dateMin === null && $request->query->get('dateMin') !== null) {
            return $this->json(['message' => 'Invalid dateMin'], 400);
        }
        if ($request->query->has('dateMax') && $dateMax === null && $request->query->get('dateMax') !== null) {
            return $this->json(['message' => 'Invalid dateMax'], 400);
        }

        $livres = $rechercheRepository->searchLivres(
            $titre ?: null,
            $auteur ?: null,
            $categorie ?: null,
            $langue ?: null,
            $dateMin,
            $dateMax
        );

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

    private function parseDate(mixed $value): ?\DateTimeInterface
    {
        if ($value === null || $value === '') {
            return null;
        }

        try {
            return new \DateTime((string) $value);
        } catch (\Exception) {
            return null;
        }
    }
}
