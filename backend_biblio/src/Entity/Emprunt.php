<?php

namespace App\Entity;

use App\Repository\EmpruntRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpruntRepository::class)]
class Emprunt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateEmprunt = null;

    #[ORM\Column(type: 'datetime', nullable: true)] // Nullable tant que le livre n'est pas rendu
    private ?\DateTimeInterface $dateRetour = null;

    #[ORM\ManyToOne(targetEntity: Adherent::class, inversedBy: 'emprunts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adherent $adherent = null;

    #[ORM\ManyToOne(targetEntity: Livre::class, inversedBy: 'emprunts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Livre $livre = null;

    public function __construct()
    {
        $this->dateEmprunt = new \DateTime(); // Date du jour par défaut
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateEmprunt(): ?\DateTimeInterface
    {
        return $this->dateEmprunt;
    }

    public function setDateEmprunt(\DateTimeInterface $dateEmprunt): static
    {
        $this->dateEmprunt = $dateEmprunt;
        return $this;
    }

    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->dateRetour;
    }

    public function setDateRetour(?\DateTimeInterface $dateRetour): static
    {
        $this->dateRetour = $dateRetour;
        return $this;
    }

    public function getAdherent(): ?Adherent
    {
        return $this->adherent;
    }

    public function setAdherent(?Adherent $adherent): static
    {
        $this->adherent = $adherent;
        return $this;
    }

    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): static
    {
        $this->livre = $livre;
        return $this;
    }

    // Méthode utilitaire pour EasyAdmin
    public function __toString(): string
    {
        return sprintf('%s - %s (%s)',
            $this->adherent->getNom(),
            $this->livre->getTitre(),
            $this->dateEmprunt->format('d/m/Y')
        );
    }
}
