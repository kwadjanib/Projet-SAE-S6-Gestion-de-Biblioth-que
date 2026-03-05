<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateSortie = null;

    #[ORM\Column(length: 255)]
    private ?string $langue = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoCouverture = null;

    #[ORM\ManyToMany(targetEntity: Auteur::class, inversedBy: 'livres')]
    private Collection $auteurs;

    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'livres')]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'livre', targetEntity: Emprunt::class)]
    private Collection $emprunts;

    public function __construct()
    {
        $this->auteurs = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->emprunts = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getTitre(): ?string { return $this->titre; }
    public function setTitre(string $titre): static { $this->titre = $titre; return $this; }

    public function getDateSortie(): ?\DateTimeInterface { return $this->dateSortie; }
    public function setDateSortie(\DateTimeInterface $dateSortie): static { $this->dateSortie = $dateSortie; return $this; }

    public function getLangue(): ?string { return $this->langue; }
    public function setLangue(string $langue): static { $this->langue = $langue; return $this; }

    public function getPhotoCouverture(): ?string { return $this->photoCouverture; }
    public function setPhotoCouverture(?string $photoCouverture): static { $this->photoCouverture = $photoCouverture; return $this; }

    /** @return Collection<int, Auteur> */
    public function getAuteurs(): Collection { return $this->auteurs; }

    public function addAuteur(Auteur $auteur): static
    {
        if (!$this->auteurs->contains($auteur)) {
            $this->auteurs->add($auteur);
            $auteur->addLivre($this);
        }
        return $this;
    }

    public function removeAuteur(Auteur $auteur): static
    {
        if ($this->auteurs->removeElement($auteur)) {
            $auteur->removeLivre($this);
        }
        return $this;
    }

    /** @return Collection<int, Categorie> */
    public function getCategories(): Collection { return $this->categories; }

    public function addCategory(Categorie $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addLivre($this);
        }
        return $this;
    }

    public function removeCategory(Categorie $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removeLivre($this);
        }
        return $this;
    }

    /** @return Collection<int, Emprunt> */
    public function getEmprunts(): Collection { return $this->emprunts; }

    public function addEmprunt(Emprunt $emprunt): static
    {
        if (!$this->emprunts->contains($emprunt)) {
            $this->emprunts->add($emprunt);
            $emprunt->setLivre($this);
        }
        return $this;
    }

    public function removeEmprunt(Emprunt $emprunt): static
    {
        if ($this->emprunts->removeElement($emprunt)) {
            if ($emprunt->getLivre() === $this) {
                $emprunt->setLivre(null);
            }
        }
        return $this;
    }

    public function __toString(): string { return $this->titre ?? 'Livre sans titre'; }
}
