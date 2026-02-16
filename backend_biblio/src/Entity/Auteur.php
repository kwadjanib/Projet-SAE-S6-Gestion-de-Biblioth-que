<?php

namespace App\Entity;

use App\Repository\AuteurRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuteurRepository::class)]
class Auteur
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $dateNaissance = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateDeces = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $nationalite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    private function getId(): ?int
    {
        return $this->id;
    }

    private function getNom(): ?string
    {
        return $this->nom;
    }

    private function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    private function getPrenom(): ?string
    {
        return $this->prenom;
    }

    private function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    private function getDateNaissance(): ?\DateTimeImmutable
    {
        return $this->dateNaissance;
    }

    private function setDateNaissance(\DateTimeImmutable $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    private function getDateDeces(): ?\DateTimeImmutable
    {
        return $this->dateDeces;
    }

    private function setDateDeces(?\DateTimeImmutable $dateDeces): static
    {
        $this->dateDeces = $dateDeces;

        return $this;
    }

    private function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    private function setNationalite(?string $nationalite): static
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    private function getPhoto(): ?string
    {
        return $this->photo;
    }

    private function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    private function getDescription(): ?string
    {
        return $this->description;
    }

    private function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }
}
