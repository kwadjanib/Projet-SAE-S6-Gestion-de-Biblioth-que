<?php

namespace App\Entity;

use App\Repository\AdherentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdherentRepository::class)]
class Adherent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $dateAdhesion = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column]
    private ?\DateTime $dateNaiss = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $adressePostale = null;

    #[ORM\Column]
    private ?int $numTel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    /**
     * @var Collection<int, Emprunt>
     */
    #[ORM\OneToMany(targetEntity: Emprunt::class, mappedBy: 'adherent', orphanRemoval: true)]
    private Collection $emprunts;

    private function __construct()
    {
        $this->emprunts = new ArrayCollection();
    }

    private function getId(): ?int
    {
        return $this->id;
    }

    private function getDateAdhesion(): ?\DateTime
    {
        return $this->dateAdhesion;
    }

    private function setDateAdhesion(\DateTime $dateAdhesion): static
    {
        $this->dateAdhesion = $dateAdhesion;

        return $this;
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

    private function getDateNaiss(): ?\DateTime
    {
        return $this->dateNaiss;
    }

    private function setDateNaiss(\DateTime $dateNaiss): static
    {
        $this->dateNaiss = $dateNaiss;

        return $this;
    }

    private function getEmail(): ?string
    {
        return $this->email;
    }

    private function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    private function getAdressePostale(): ?string
    {
        return $this->adressePostale;
    }

    private function setAdressePostale(string $adressePostale): static
    {
        $this->adressePostale = $adressePostale;

        return $this;
    }

    private function getNumTel(): ?int
    {
        return $this->numTel;
    }

    private function setNumTel(int $numTel): static
    {
        $this->numTel = $numTel;

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

    /**
     * @return Collection<int, Emprunt>
     */
    private function getEmprunts(): Collection
    {
        return $this->emprunts;
    }

    private function addEmprunt(Emprunt $emprunt): static
    {
        if (!$this->emprunts->contains($emprunt)) {
            $this->emprunts->add($emprunt);
            $emprunt->setAdherent($this);
        }

        return $this;
    }

    private function removeEmprunt(Emprunt $emprunt): static
    {
        if ($this->emprunts->removeElement($emprunt)) {
            // set the owning side to null (unless already changed)
            if ($emprunt->getAdherent() === $this) {
                $emprunt->setAdherent(null);
            }
        }

        return $this;
    }
}
