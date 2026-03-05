<?php

namespace App\Entity;

use App\Repository\AdherentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: AdherentRepository::class)]
class Adherent implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateAdhesion = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateNaiss = null;

    #[ORM\Column(length: 255)]
    private ?string $adressePostale = null;

    #[ORM\Column]
    private ?string $numTel = null; // Changé en string pour gérer le 0 au début

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\OneToMany(targetEntity: Reservations::class, mappedBy: 'adherent', orphanRemoval: true)]
    private Collection $reservations;

    #[ORM\OneToMany(targetEntity: Emprunt::class, mappedBy: 'adherent', orphanRemoval: true)]
    private Collection $emprunts;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->emprunts = new ArrayCollection();
        $this->dateAdhesion = new \DateTime(); // Date par défaut
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    /**
     * A visual identifier that represents this user.
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

    // --- Tes getters et setters d'origine ---

    public function getNom(): ?string
    {
        return $this->nom;
    }
    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }
    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getDateAdhesion(): ?\DateTimeInterface
    {
        return $this->dateAdhesion;
    }
    public function setDateAdhesion(\DateTimeInterface $dateAdhesion): static
    {
        $this->dateAdhesion = $dateAdhesion;
        return $this;
    }

    public function getDateNaiss(): ?\DateTimeInterface
    {
        return $this->dateNaiss;
    }
    public function setDateNaiss(\DateTimeInterface $dateNaiss): static
    {
        $this->dateNaiss = $dateNaiss;
        return $this;
    }

    public function getAdressePostale(): ?string
    {
        return $this->adressePostale;
    }
    public function setAdressePostale(string $adressePostale): static
    {
        $this->adressePostale = $adressePostale;
        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->numTel;
    }
    public function setNumTel(string $numTel): static
    {
        $this->numTel = $numTel;
        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }
    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;
        return $this;
    }

    /** @return Collection<int, Reservations> */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    /** @return Collection<int, Emprunt> */
    public function getEmprunts(): Collection
    {
        return $this->emprunts;
    }

    public function __toString(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }
}
