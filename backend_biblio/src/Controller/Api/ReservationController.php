<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Reservations;
use App\Repository\ReservationsRepository;

#[Route('/api')]
class ReservationController extends AbstractController
{
    #[Route('/reservations', name: 'app_api_reservation_index', methods: ['GET'])]
    public function index(ReservationsRepository $reservationsRepository): JsonResponse
    {
        $reservations = $reservationsRepository->findAll();
        return $this->json($reservations, 200, [], ['groups' => 'reservation:read']);
    }

    #[Route('/reservations/{id}', name: 'app_api_reservation_show', methods: ['GET'])]
    public function show(Reservations $reservations): JsonResponse
    {
        return $this->json($reservations, 200, [], ['groups' => 'reservation:read']);
    }
}
