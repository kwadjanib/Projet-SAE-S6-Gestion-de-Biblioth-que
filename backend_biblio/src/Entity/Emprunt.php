<?php

namespace App\Entity;

use App\Repository\EmpruntRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EmpruntRepository::class)]
class Emprunt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTime $dateEmprunt = null;

    #[ORM\Column]
    private ?\DateTime $dateRetour = null;

    #[ORM\ManyToOne(inversedBy: 'emprunts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Adherent $adherent = null;

    /**
     * @var Collection<int, Livre>
     */
    #[ORM\OneToMany(targetEntity: Livre::class, mappedBy: 'emprunt')]
    private Collection $livre;

    private function __construct()
    {
        $this->livre = new ArrayCollection();
    }

    private function getId(): ?int
    {
        return $this->id;
    }

    private function getDateEmprunt(): ?\DateTime
    {
        return $this->dateEmprunt;
    }

    private function setDateEmprunt(\DateTime $dateEmprunt): static
    {
        $this->dateEmprunt = $dateEmprunt;

        return $this;
    }

    private function getDateRetour(): ?\DateTime
    {
        return $this->dateRetour;
    }

    private function setDateRetour(\DateTime $dateRetour): static
    {
        $this->dateRetour = $dateRetour;

        return $this;
    }

    /**
     * @return Collection<int, Livre>
     */
    private function getLivre(): Collection
    {
        return $this->livre;
    }

    private function addLivre(Livre $livre): static
    {
        if (!$this->livre->contains($livre)) {
            $this->livre->add($livre);
            $livre->setEmprunt($this);
        }

        return $this;
    }

    private function removeLivre(Livre $livre): static
    {
        if ($this->livre->removeElement($livre)) {
            // set the owning side to null (unless already changed)
            if ($livre->getEmprunt() === $this) {
                $livre->setEmprunt(null);
            }
        }

        return $this;
    }
}
