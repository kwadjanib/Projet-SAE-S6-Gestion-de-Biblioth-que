<?php

namespace App\Controller\Api;

use App\Entity\Livre;
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
        // On ne récupère que les réservations de l'utilisateur connecté
        $reservations = $reservationsRepository->findBy(['utilisateur' => $this->getUser()]);
        return $this->json($reservations, 200, [], ['groups' => 'reservation:read']);
    }

    #[Route('/reservation/{id}', name: 'app_api_reservation_show', methods: ['GET'])]
    #[IsGranted('ROLE_ADHERENT')]
    public function show(Reservations $reservation): JsonResponse
    {
        // Vérification de sécurité : l'adhérent possède-t-il cette réservation ?
        if ($reservation->getUtilisateur() !== $this->getUser()) {
            return $this->json(['error' => 'Accès refusé'], 403);
        }
        return $this->json($reservation, 200, [], ['groups' => 'reservation:read']);
    }
#[Route('/reservation', name: 'app_api_reservation_create', methods: ['POST'])]
#[IsGranted('ROLE_ADHERENT')]
public function create(
    Request $request,
    EntityManagerInterface $entityManager,
    LivreRepository $livreRepository
): JsonResponse {
    $data = json_decode($request->getContent(), true);

    // On récupère 'livreId' (correspondant au TypeScript)
    $idDuLivre = $data['livreId'] ?? null;

    if (!$idDuLivre) {
        return $this->json(['error' => 'ID du livre manquant'], 400);
    }

    $livre = $livreRepository->find($idDuLivre);

    if (!$livre) {
        return $this->json(['error' => 'Livre non trouvé'], 404);
    }

    $reservation = new Reservations();
    $reservation->setDateResa(new \DateTime());
    $reservation->setLivre($livre);
    $reservation->setUtilisateur($this->getUser());

    $entityManager->persist($reservation);
    $entityManager->flush();

    return $this->json($reservation, 201, [], ['groups' => 'reservation:read']);
}   #[Route('/reservation/{id}', name: 'app_api_reservation_delete', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADHERENT')]
    public function delete(Reservations $reservation, EntityManagerInterface $entityManager): JsonResponse
    {
        if ($reservation->getUtilisateur() !== $this->getUser()) {
            return $this->json(['error' => 'Vous ne pouvez pas supprimer cette réservation'], 403);
        }

        $entityManager->remove($reservation);
        $entityManager->flush();

        return $this->json(['message' => 'Réservation supprimée avec succès'], 200);
    }
}
