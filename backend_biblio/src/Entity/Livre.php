<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\Column]
    private ?\DateTime $dateSortie = null;

    #[ORM\Column(length: 255)]
    private ?string $langue = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoCouverture = null;

<<<<<<< HEAD
    /**
     * @var Collection<int, Auteur>
     */
    #[ORM\ManyToMany(targetEntity: Auteur::class, mappedBy: 'livre')]
    private Collection $auteurs;

    public function __construct()
    {
        $this->auteurs = new ArrayCollection();
=======
    #[ORM\ManyToOne(inversedBy: 'livre')]
    private ?Emprunt $emprunt = null;

    /**
     * @var Collection<int, Categorie>
     */
    #[ORM\ManyToMany(targetEntity: Categorie::class, mappedBy: 'livres')]
    private Collection $categories;

    private function __construct()
    {
        $this->categories = new ArrayCollection();
>>>>>>> f384afe350dd1fc94f0339c7ee4d1e256faee60f
    }

    private function getId(): ?int
    {
        return $this->id;
    }

    private function getTitre(): ?string
    {
        return $this->titre;
    }

    private function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    private function getDateSortie(): ?\DateTime
    {
        return $this->dateSortie;
    }

    private function setDateSortie(\DateTime $dateSortie): static
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    private function getLangue(): ?string
    {
        return $this->langue;
    }

    private function setLangue(string $langue): static
    {
        $this->langue = $langue;

        return $this;
    }

    private function getPhotoCouverture(): ?string
    {
        return $this->photoCouverture;
    }

    private function setPhotoCouverture(?string $photoCouverture): static
    {
        $this->photoCouverture = $photoCouverture;

        return $this;
    }

<<<<<<< HEAD
<<<<<<< HEAD
    /**
     * @return Collection<int, Auteur>
     */
    public function getAuteurs(): Collection
    {
        return $this->auteurs;
    }

    public function addAuteur(Auteur $auteur): static
    {
        if (!$this->auteurs->contains($auteur)) {
            $this->auteurs->add($auteur);
            $auteur->addLivre($this);
=======
    public function getEmprunt(): ?Emprunt
=======
    private function getEmprunt(): ?Emprunt
>>>>>>> bb5c5dbf568f4e94e2318c4bb0e778f371b38e0e
    {
        return $this->emprunt;
    }

    private function setEmprunt(?Emprunt $emprunt): static
    {
        $this->emprunt = $emprunt;

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    private function getCategories(): Collection
    {
        return $this->categories;
    }

    private function addCategory(Categorie $category): static
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->addLivre($this);
>>>>>>> f384afe350dd1fc94f0339c7ee4d1e256faee60f
        }

        return $this;
    }

<<<<<<< HEAD
<<<<<<< HEAD
    public function removeAuteur(Auteur $auteur): static
    {
        if ($this->auteurs->removeElement($auteur)) {
            $auteur->removeLivre($this);
=======
    public function removeCategory(Categorie $category): static
=======
    private function removeCategory(Categorie $category): static
>>>>>>> bb5c5dbf568f4e94e2318c4bb0e778f371b38e0e
    {
        if ($this->categories->removeElement($category)) {
            $category->removeLivre($this);
>>>>>>> f384afe350dd1fc94f0339c7ee4d1e256faee60f
        }

        return $this;
    }
}
