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
    #[Route('/livre', name: 'app_api_livre_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $livre = new Livre();
        $livre->setTitre($data['titre']);
        $livre->setDateSortie(new \DateTime());
        $livre->setLangue($data['langue']);
        $livre->setPhotoCouverture($data['photoCouverture']);

        $entityManager->persist($livre);
        $entityManager->flush();

        return $this->json($livre, 201, [], ['groups' => 'livre:read']);
    }
}
