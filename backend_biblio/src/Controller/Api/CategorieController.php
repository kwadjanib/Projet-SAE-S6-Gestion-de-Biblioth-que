<?php

namespace App\Controller\Api;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LivreRepository;

#[Route('/api')]
class CategorieController extends AbstractController
{
    #[Route('/categories', name: 'app_api_categorie_index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): JsonResponse
    {
        $categories = $categorieRepository->findAll();
        return $this->json($categories, 200, [], ['groups' => 'categorie:read']);
    }

    #[Route('/categories/{id}', name: 'app_api_categorie_show', methods: ['GET'])]
    public function show(Categorie $categorie): JsonResponse
    {
        return $this->json($categorie, 200, [], ['groups' => 'categorie:read']);
    }
    #[Route('/categorie', name: 'app_api_categorie_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $categorie = new Categorie();
        $categorie->setNom($data['nom']);
        $entityManager->persist($categorie);
        $entityManager->flush();
        return $this->json($categorie, 201, [], ['groups' => 'categorie:read']);
    }

}
