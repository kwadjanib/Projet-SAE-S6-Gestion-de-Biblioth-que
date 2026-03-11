<?php

namespace App\Controller\Api;

use App\Entity\Reservations;
use App\Repository\LivreRepository;
use App\Repository\ReservationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api')]
class ReservationController extends AbstractController
{
    #[Route('/reservations', name: 'app_api_reservation_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADHERENT')]
    public function index(ReservationsRepository $reservationsRepository): JsonResponse
    {
        $cutoff = (new \DateTimeImmutable())->modify('-7 days');
        $reservationsRepository->purgeExpired($cutoff);
        $reservations = $reservationsRepository->findActiveByUser($this->getUser(), $cutoff);
        return $this->json($reservations, 200, [], ['groups' => 'reservation:read']);
    }

    #[Route('/reservation/{id}', name: 'app_api_reservation_show', methods: ['GET'])]
    #[IsGranted('ROLE_ADHERENT')]
    public function show(Reservations $reservation): JsonResponse
    {
        if ($reservation->getUtilisateur() !== $this->getUser()) {
            return $this->json(['message' => 'Acces refuse'], 403);
        }

        return $this->json($reservation, 200, [], ['groups' => 'reservation:read']);
    }

    #[Route('/reservation', name: 'app_api_reservation_create', methods: ['POST'])]
    #[IsGranted('ROLE_ADHERENT')]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager,
        LivreRepository $livreRepository,
        ReservationsRepository $reservationsRepository
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $idDuLivre = $data['livreId'] ?? null;
        if (!$idDuLivre) {
            return $this->json(['message' => 'ID du livre manquant'], 400);
        }

        $livre = $livreRepository->find($idDuLivre);
        if (!$livre) {
            return $this->json(['message' => 'Livre non trouve'], 404);
        }

        $user = $this->getUser();

        $cutoff = (new \DateTimeImmutable())->modify('-7 days');
        $reservationsRepository->purgeExpired($cutoff);

        $reservationCount = $reservationsRepository->countActiveForUser($user, $cutoff);
        if ($reservationCount >= 3) {
            return $this->json(['message' => 'Limite de 3 reservations atteinte'], 409);
        }

        if ($livre->getEmprunt() !== null) {
            return $this->json(['message' => 'Livre deja emprunte'], 409);
        }

        $existing = $reservationsRepository->findActiveByLivre($livre, $cutoff);
        if ($existing) {
            return $this->json(['message' => 'Livre deja reserve'], 409);
        }

        $reservation = new Reservations();
        $reservation->setDateResa(new \DateTime());
        $reservation->setLivre($livre);
        $reservation->setUtilisateur($user);

        $entityManager->persist($reservation);
        $entityManager->flush();

        return $this->json($reservation, 201, [], ['groups' => 'reservation:read']);
    }

    #[Route('/reservation/{id}', name: 'app_api_reservation_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADHERENT')]
    public function delete(Reservations $reservation, EntityManagerInterface $entityManager): JsonResponse
    {
        if ($reservation->getUtilisateur() !== $this->getUser()) {
            return $this->json(['message' => 'Vous ne pouvez pas supprimer cette reservation'], 403);
        }

        $entityManager->remove($reservation);
        $entityManager->flush();

        return $this->json(['message' => 'Reservation supprimee avec succes'], 200);
    }
}
