<?php

namespace App\Entity;

use App\Repository\ReservationsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationsRepository::class)]
class Reservations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $dateResa = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adherent $adherent = null;

    #[ORM\OneToOne(targetEntity: self::class, inversedBy: 'reservations', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?self $livre = null;

    #[ORM\OneToOne(targetEntity: self::class, mappedBy: 'livre', cascade: ['persist', 'remove'])]
    private ?self $reservations = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateResa(): ?\DateTime
    {
        return $this->dateResa;
    }

    public function setDateResa(\DateTime $dateResa): static
    {
        $this->dateResa = $dateResa;

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

    public function getLivre(): ?self
    {
        return $this->livre;
    }

    public function setLivre(self $livre): static
    {
        $this->livre = $livre;

        return $this;
    }

    public function getReservations(): ?self
    {
        return $this->reservations;
    }

    public function setReservations(self $reservations): static
    {
        // set the owning side of the relation if necessary
        if ($reservations->getLivre() !== $this) {
            $reservations->setLivre($this);
        }

        $this->reservations = $reservations;

        return $this;
    }
}
