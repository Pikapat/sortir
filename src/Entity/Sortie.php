<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SortieRepository::class)]
class Sortie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'l\'email ne peut pas être vide')]
    #[Assert\NotNull(message: 'Une erreur est survenue')]
    #[Assert\Length(
        min: 2,
        max: 15,
        minMessage: 'Le titre doit contenir au moins 2 caractères',
        maxMessage: 'Le titre ne peut pas contenir plus de 15 caractères')]
    #[ORM\Column(length: 255)]
    private ?string $titre = null;

    #[Assert\DateTimeInterface (format: 'd-m-Y H:i', message: 'Le format sasie n\'est pas reconu')]
    #[Assert\Expression(
        'this.getDateHeureDebut() > this.getDateLimiteInscription()',
        message: 'Le début de l\'éveenement doit être ultérieur à la date de limite de des inscriptions')]
    #[Assert\GreaterThanOrEqual('today',  message: 'Le début de l\'évènement ne doit pas être antérieur
     à la date et heure actuele')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateHeureDebut = null;

    #[Assert\NotBlank (message: 'La durée doit être renseigné')]
    #[Assert\NotNull(message: 'La durée doit être renseigné')]
    #[Assert\Range(
        minMessage: 'La durée de ne peut pas être inférieur à 1 heure',
        maxMessage: 'La durée de ne peut pas être supérieur à 24 heures',
        min: '1',
        max: '24')]
    #[ORM\Column]
    private ?int $duree = null;

    #[Assert\DateTimeInterface (format: 'd-m-Y H:i', message: 'Le format sasie n\'est pas reconu')]
    #[Assert\Expression(
        ' this.getDateLimiteInscription() < this.getDateHeureDebut()',
        message: 'La date limite des inscriptions doit être antèrieur à la date de début de l\'évènement ')]
    #[Assert\GreaterThanOrEqual('today',  message: 'La date limite des inscriptions ne doit pas être antérieur
     à la date et heure actuele')]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateLimiteInscription = null;

    #[Assert\NotBlank (message: 'Le nombre maximum d\'inscripts doit être renseigné')]
    #[Assert\NotNull(message: 'Le nombre maximum d\'inscripts doit être renseigné')]
    #[Assert\range(
        min : '2',
        minMessage: 'Le nombre d\'inscriptions acceptées doit être supérieur à 2')]
    #[ORM\Column]
    private ?int $nbInscriptionsMax = null;

    #[Assert\NotBlank(message: 'l\'email ne peut pas être vide')]
    #[Assert\NotNull(message: 'Une erreur est survenue')]
    #[Assert\Length(
        min: 5,
        max: 100 ,
        minMessage: 'Le champ de description doit contenir au moins 5 caractères',
        maxMessage: 'Le champ de description ne peut pas contenir plus de 100 caractères')]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $infosSortie = null;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'sortiesInscrits')]
    private Collection $usersInscrits;

    #[ORM\ManyToOne(inversedBy: 'sortiesOrganisees')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $organisateur = null;

    #[Assert\NotBlank(message: 'le campus doit être renseigné')]
    #[Assert\NotNull(message: 'Une erreur est survenue')]
    #[ORM\ManyToOne(inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Campus $siteOrganisateur = null;

    #[ORM\ManyToOne(inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Etat $etat = null;

//    #[Assert\NotBlank(message: 'le lieu doit être renseigné')]
//    #[Assert\NotNull(message: 'Une erreur est survenue')]
    #[ORM\ManyToOne(inversedBy: 'sorties')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Lieu $lieu = null;

//    #[Assert\NotBlank(message: 'le motif d\'annulation doit être indiqué')]
//    #[Assert\NotNull(message: 'Une erreur est survenue')]
    #[Assert\Length(
        min: 5,
        max: 50 ,
        minMessage: 'le motif d\'annulation moins 5 caractères',
        maxMessage: 'le motif d\'annulation ne peut pas contenir plus de 50 caractères')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motif = null;

    public function __construct()
    {
        $this->usersInscrits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDateHeureDebut(): ?\DateTimeInterface
    {
        return $this->dateHeureDebut;
    }

    public function setDateHeureDebut(\DateTimeInterface $dateHeureDebut): self
    {
        $this->dateHeureDebut = $dateHeureDebut;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getDateLimiteInscription(): ?\DateTimeInterface
    {
        return $this->dateLimiteInscription;
    }

    public function setDateLimiteInscription(\DateTimeInterface $dateLimiteInscription): self
    {
        $this->dateLimiteInscription = $dateLimiteInscription;

        return $this;
    }

    public function getNbInscriptionsMax(): ?int
    {
        return $this->nbInscriptionsMax;
    }

    public function setNbInscriptionsMax(int $nbInscriptionsMax): self
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;

        return $this;
    }

    public function getInfosSortie(): ?string
    {
        return $this->infosSortie;
    }

    public function setInfosSortie(string $infosSortie): self
    {
        $this->infosSortie = $infosSortie;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsersInscrits(): Collection
    {
        return $this->usersInscrits;
    }

    public function addUsersInscrit(User $usersInscrit): self
    {
        if (!$this->usersInscrits->contains($usersInscrit)) {
            $this->usersInscrits->add($usersInscrit);
            $usersInscrit->addSortiesInscrit($this);
        }

        return $this;
    }

    public function removeUsersInscrit(User $usersInscrit): self
    {
        if ($this->usersInscrits->removeElement($usersInscrit)) {
            $usersInscrit->removeSortiesInscrit($this);
        }

        return $this;
    }

    public function getOrganisateur(): ?User
    {
        return $this->organisateur;
    }

    public function setOrganisateur(?User $organisateur): self
    {
        $this->organisateur = $organisateur;

        return $this;
    }

    public function getSiteOrganisateur(): ?Campus
    {
        return $this->siteOrganisateur;
    }

    public function setSiteOrganisateur(?Campus $siteOrganisateur): self
    {
        $this->siteOrganisateur = $siteOrganisateur;

        return $this;
    }

    public function getEtat(): ?Etat
    {
        return $this->etat;
    }

    public function setEtat(?Etat $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(?string $motif): self
    {
        $this->motif = $motif;

        return $this;
    }
}
