<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Adherent;
use App\Repository\AdherentRepository;

#[Route('/api')]
class AdherentController extends AbstractController
{
    #[Route('/adherents', name: 'app_api_adherent_index', methods: ['GET'])]
    public function index(AdherentRepository $AdherentRepository): JsonResponse
    {
        $adherent = $AdherentRepository->findAll();
        return $this->json($adherent, 200, [], ['groups' => 'adherent:read']);
    }

    #[Route('/adherent/{id}', name: 'app_api_adherent_show', methods: ['GET'])]
    public function show(Adherent $adherent): JsonResponse
    {
        return $this->json($adherent, 200, [], ['groups' => 'adherent:read']);
    }
    #[Route('/adherent', name: 'app_api_adherent_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $adherent = new Adherent();
        $adherent->setNom($data['nom']);
        $adherent->setPrenom($data['prenom']);
        $adherent->setEmail($data['email']);
        $entityManager->persist($adherent);
        $entityManager->flush();
        return $this->json($adherent, 201, [], ['groups' => 'adherent:read']);
    }
}
