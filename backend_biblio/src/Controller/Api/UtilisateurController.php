<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;

#[Route('/api')]
class UtilisateurController extends AbstractController
{
    #[Route('/utilisateurs', name: 'app_api_utilisateur_index', methods: ['GET'])]
    public function index(UtilisateurRepository $UtilisateurRepository): JsonResponse
    {
        $utilisateur = $UtilisateurRepository->findAll();
        return $this->json($utilisateur, 200, [], ['groups' => 'utilisateur:read']);
    }

    #[Route('/utilisateur/{id}', name: 'app_api_utilisateur_show', methods: ['GET'])]
    public function show(Utilisateur $utilisateur): JsonResponse
    {
        return $this->json($utilisateur, 200, [], ['groups' => 'utilisateur:read']);
    }
    #[Route('/utilisateur', name: 'app_api_utilisateur_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $utilisateur = new Utilisateur();
        $utilisateur->setNom($data['nom']);
        $utilisateur->setPrenom($data['prenom']);
        $utilisateur->setEmail($data['email']);
        $entityManager->persist($utilisateur);
        $entityManager->flush();
        return $this->json($utilisateur, 201, [], ['groups' => 'utilisateur:read']);
    }
}
