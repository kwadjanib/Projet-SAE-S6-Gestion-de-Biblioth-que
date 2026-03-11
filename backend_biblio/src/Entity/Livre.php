<?php

namespace App\Entity;

use App\Repository\LivreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: LivreRepository::class)]
class Livre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['livre:read', 'categorie:read', 'auteur:read', 'emprunt:read', 'reservation:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['livre:read', 'categorie:read', 'auteur:read', 'emprunt:read', 'reservation:read'])]
    private ?string $titre = null;

    #[ORM\Column]
    #[Groups(['livre:read', 'auteur:read'])]
    private ?\DateTime $dateSortie = null;

    #[ORM\Column(length: 255)]
    #[Groups(['livre:read', 'emprunt:read'])]
    private ?string $langue = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['livre:read', 'emprunt:read'])]
    private ?string $photoCouverture = null;

    /**
     * @var Collection<int, Auteur>
     */
    #[ORM\ManyToMany(targetEntity: Auteur::class, inversedBy: 'livres')]
    #[Groups(['livre:read'])]
    private Collection $auteurs;

    #[ORM\ManyToOne(inversedBy: 'livres')]
    #[Groups(['livre:read'])]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private ?Emprunt $emprunt = null;


    /**
     * @var Collection<int, Categorie>
     */
    #[ORM\ManyToMany(targetEntity: Categorie::class, inversedBy: 'livres')]
    #[Groups(['livre:read'])]
    private Collection $categories;

    public function __construct()
    {
        $this->auteurs = new ArrayCollection();
        $this->categories = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateSortie(): ?\DateTime
    {
        return $this->dateSortie;
    }

    public function setDateSortie(\DateTime $dateSortie): static
    {
        $this->dateSortie = $dateSortie;

        return $this;
    }

    public function getLangue(): ?string
    {
        return $this->langue;
    }

    public function setLangue(string $langue): static
    {
        $this->langue = $langue;

        return $this;
    }

    public function getPhotoCouverture(): ?string
    {
        return $this->photoCouverture;
    }

    public function setPhotoCouverture(?string $photoCouverture): static
    {
        $this->photoCouverture = $photoCouverture;

        return $this;
    }

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

    public function getEmprunt(): ?Emprunt
    {
        return $this->emprunt;
    }

    public function setEmprunt(?Emprunt $emprunt): static
    {
        $this->emprunt = $emprunt;

        return $this;
    }

    /**
     * @return Collection<int, Categorie>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

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
    public function __toString(): string { return $this->titre; }
}
