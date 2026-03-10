<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Emprunt;
use App\Repository\EmpruntRepository;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
    #[IsGranted('ROLE_ADHERENT')]
    #[Route('/emprunt', name: 'app_api_emprunt_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $emprunt = new Emprunt();
        $emprunt->setDateEmprunt(new \DateTime());
        $emprunt->setDateRetour(null);
        $emprunt->setLivre($data['livre']);
        $emprunt->setUtilisateur($data['utilisateur']);
        $entityManager->persist($emprunt);
        $entityManager->flush();

        return $this->json($emprunt, 201, [], ['groups' => 'emprunt:read']);
    }
}
