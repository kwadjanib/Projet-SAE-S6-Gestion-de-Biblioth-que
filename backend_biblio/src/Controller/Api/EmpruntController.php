<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Emprunt;
use App\Repository\EmpruntRepository;

#[Route('/api')]
class EmpruntController extends AbstractController
{
    #[Route('/emprunts', name: 'app_api_emprunt_index', methods: ['GET'])]
    public function index(EmpruntRepository $EmpruntRepository): JsonResponse
    {
        $emprunt = $EmpruntRepository->findAll();
        return $this->json($emprunt, 200, [], ['groups' => 'emprunt:read']);
    }

    #[Route('/emprunt/{id}', name: 'app_api_emprunt_show', methods: ['GET'])]
    public function show(Emprunt $emprunt): JsonResponse
    {
        return $this->json($emprunt, 200, [], ['groups' => 'emprunt:read']);
    }
}
