<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'l\'email ne peut pas être vide')]
    #[Assert\NotNull(message: 'Une erreur est survenue')]
    #[Assert\Email(message: 'l\'email saisi n\'est pas valide.')]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */

//    #[SecurityAssert\UserPassword(message: 'Le mot de passe saisi est incorrect')]
    #[Assert\Regex(pattern: '^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{5,}^', message: 'le mot de passe doit avoir au moins
    5 caractères dont au moins un chiffre et une lettre')]
    #[ORM\Column]
        private ?string $password = null;

    #[Assert\NotBlank(message: 'le nom ne peut pas être vide')]
    #[Assert\NotNull(message: 'Une erreur est survenue')]
    #[Assert\Length(
        min: 2,
        max: 15,
        minMessage: 'Votre nom doit contenir au moins 2 caractères',
        maxMessage: 'Votre nom ne peut pas contenir plus de 15 caractères')]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;


    #[Assert\Length(
        min: 2,
        max: 15,
        minMessage: 'Votre prénom doit contenir au moins 2 caractères',
        maxMessage: 'Votre prénom ne peut pas contenir plus de 15 caractères')]
    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[Assert\Regex(pattern:'^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$^',
        message: 'Le numéro de téléphone doit contenir 10 chiffres et commencer par un zéro')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[Assert\NotBlank(message: 'le pseudo ne peut pas être vide')]
    #[Assert\NotNull(message: 'Une erreur est survenue')]
    #[Assert\Length(
        min: 2,
        max: 10,
        minMessage: 'Votre nom doit contenir au moins 2 caractères',
        maxMessage: 'Votre nom ne peut pas contenir plus de 10 caractères')]
    #[ORM\Column(length: 255)]
    private ?string $pseudo = null;

    #[ORM\Column(nullable: true)]
    private ?bool $actif = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $campus = null;


    #[ORM\ManyToMany(targetEntity: Sortie::class, inversedBy: 'usersInscrits')]
    private Collection $sortiesInscrits;


    #[ORM\OneToMany(mappedBy: 'organisateur', targetEntity: Sortie::class, orphanRemoval: true)]
    private Collection $sortiesOrganisees;

//    #[Assert\File(
//        maxSize: '2048k',
//        mimeTypes: ['application/png'],
//        mimeTypesMessage: 'Seuls les formats .jpg, .jpeg et .png sont acceptés ',
//    )]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $picture = null;

    public function __construct()
    {
        $this->sortiesInscrits = new ArrayCollection();
        $this->sortiesOrganisees = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(?bool $actif): self
    {
        $this->actif = $actif;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSortiesInscrits(): Collection
    {
        return $this->sortiesInscrits;
    }

    public function addSortiesInscrit(Sortie $sortiesInscrit): self
    {
        if (!$this->sortiesInscrits->contains($sortiesInscrit)) {
            $this->sortiesInscrits->add($sortiesInscrit);
        }

        return $this;
    }

    public function removeSortiesInscrit(Sortie $sortiesInscrit): self
    {
        $this->sortiesInscrits->removeElement($sortiesInscrit);

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSortiesOrganisees(): Collection
    {
        return $this->sortiesOrganisees;
    }

    public function addSortiesOrganisee(Sortie $sortiesOrganisee): self
    {
        if (!$this->sortiesOrganisees->contains($sortiesOrganisee)) {
            $this->sortiesOrganisees->add($sortiesOrganisee);
            $sortiesOrganisee->setOrganisateur($this);
        }

        return $this;
    }

    public function removeSortiesOrganisee(Sortie $sortiesOrganisee): self
    {
        if ($this->sortiesOrganisees->removeElement($sortiesOrganisee)) {
            // set the owning side to null (unless already changed)
            if ($sortiesOrganisee->getOrganisateur() === $this) {
                $sortiesOrganisee->setOrganisateur(null);
            }
        }

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }
}
