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

    private function getEmprunt(): ?Emprunt
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
        }

        return $this;
    }

    private function removeCategory(Categorie $category): static
    {
        if ($this->categories->removeElement($category)) {
            $category->removeLivre($this);
        }

        return $this;
    }
}
