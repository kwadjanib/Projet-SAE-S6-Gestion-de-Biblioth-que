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

<<<<<<< HEAD
    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adherent $adherent = null;

    #[ORM\OneToOne(targetEntity: self::class, inversedBy: 'reservations', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?self $livre = null;

    #[ORM\OneToOne(targetEntity: self::class, mappedBy: 'livre', cascade: ['persist', 'remove'])]
    private ?self $reservations = null;

    public function getId(): ?int
=======
    private function getId(): ?int
>>>>>>> bb5c5dbf568f4e94e2318c4bb0e778f371b38e0e
    {
        return $this->id;
    }

    private function getDateResa(): ?\DateTime
    {
        return $this->dateResa;
    }

    private function setDateResa(\DateTime $dateResa): static
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
