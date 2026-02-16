<?php

namespace App\Entity;

use App\Repository\AuteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

<<<<<<< HEAD
    /**
     * @var Collection<int, Livre>
     */
    #[ORM\ManyToMany(targetEntity: Livre::class, inversedBy: 'auteurs')]
    private Collection $livre;

    public function __construct()
    {
        $this->livre = new ArrayCollection();
    }

    public function getId(): ?int
=======
    private function getId(): ?int
>>>>>>> bb5c5dbf568f4e94e2318c4bb0e778f371b38e0e
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

    /**
     * @return Collection<int, Livre>
     */
    public function getLivre(): Collection
    {
        return $this->livre;
    }

    public function addLivre(Livre $livre): static
    {
        if (!$this->livre->contains($livre)) {
            $this->livre->add($livre);
        }

        return $this;
    }

    public function removeLivre(Livre $livre): static
    {
        $this->livre->removeElement($livre);

        return $this;
    }
}
