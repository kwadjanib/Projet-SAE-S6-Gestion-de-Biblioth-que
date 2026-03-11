<?php

namespace App\Controller\Api;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class AuthController extends AbstractController
{
    #[Route('/login_check', name: 'api_login_check', methods: ['POST'])]
    public function login(): JsonResponse
    {
        // Route geree par le firewall json_login
        return $this->json(['message' => 'Missing credentials'], 401);
    }

    #[Route('/user/me', name: 'api_user_me', methods: ['GET'])]
    public function me(): JsonResponse
    {
        /** @var Utilisateur|null $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['message' => 'Not authenticated'], 401);
        }

        return $this->json($this->serializeUser($user));
    }

    #[Route('/user/me', name: 'api_user_update', methods: ['PUT'])]
    public function update(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        /** @var Utilisateur|null $user */
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['message' => 'Not authenticated'], 401);
        }

        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            return $this->json(['message' => 'Invalid payload'], 400);
        }

        if (array_key_exists('email', $data) && is_string($data['email'])) {
            $user->setEmail($data['email']);
        }
        if (array_key_exists('numTel', $data) && is_string($data['numTel'])) {
            $user->setNumTel($data['numTel']);
        }
        if (array_key_exists('adressePostale', $data) && is_string($data['adressePostale'])) {
            $user->setAdressePostale($data['adressePostale']);
        }

        $entityManager->flush();

        return $this->json($this->serializeUser($user));
    }

    private function serializeUser(Utilisateur $user): array
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'nom' => $user->getNom(),
            'prenom' => $user->getPrenom(),
            'dateNaiss' => $user->getDateNaiss() ?->format('Y-m-d'),
            'numTel' => $user->getNumTel(),
            'adressePostale' => $user->getAdressePostale(),
            'dateAdhesion' => $user->getDateAdhesion() ?->format('Y-m-d'),
            'photo' => $user->getPhoto(),
            'roles' => $user->getRoles(),
        ];
    }
}
