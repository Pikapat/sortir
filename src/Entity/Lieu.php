<?php

namespace App\Entity;

use App\Repository\LieuRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: LieuRepository::class)]
class Lieu
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['show_product', 'list_product'])]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'le nom du lieu ne peut pas être vide')]
    #[Assert\NotNull(message: 'Une erreur est survenue')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'le nom du lieu doit contenir au moins 2 caractères',
        maxMessage: 'le nom du lieu  ne peut pas contenir plus de 50 caractères')]
    #[Groups(['show_product', 'list_product'])]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[Assert\NotBlank(message: 'Le nom de la rue doit être indiqué')]
    #[Assert\NotNull(message: 'Une erreur est survenue')]
    #[Assert\Length(
        min: 5,
        max: 50,
        minMessage: 'le nom dela rue doit contenir au moins 5 caractères',
        maxMessage: 'le nom de la rue ne peut pas contenir plus de 50 caractères')]
    #[Groups(['show_product', 'list_product'])]
    #[ORM\Column(length: 255)]
    private ?string $rue = null;

    #[Groups(['show_product', 'list_product'])]
    #[ORM\Column(nullable: true)]
    private ?float $latitude = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['show_product', 'list_product'])]
    private ?float $longitude = null;


    #[ORM\OneToMany(mappedBy: 'lieu', targetEntity: Sortie::class, orphanRemoval: true)]
    private Collection $sorties;

    #[Assert\NotBlank(message: 'le motif d\'annulation doit être indiqué')]
    #[Assert\NotNull(message: 'Une erreur est survenue')]
    #[Assert\Length(
        min: 2,
        max: 20 ,
        minMessage: 'le nom de la ville doit avoir au moins 2 caractères',
        maxMessage: 'le nom de la ville ne peut pas contenir plus de 20 caractères')]
    #[ORM\ManyToOne(inversedBy: 'lieus')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['show_product', 'list_product'])]
    private ?Ville $ville = null;

    public function __construct()
    {
        $this->sorties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getRue(): ?string
    {
        return $this->rue;
    }

    public function setRue(string $rue): self
    {
        $this->rue = $rue;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, Sortie>
     */
    public function getSorties(): Collection
    {
        return $this->sorties;
    }

    public function addSorty(Sortie $sorty): self
    {
        if (!$this->sorties->contains($sorty)) {
            $this->sorties->add($sorty);
            $sorty->setLieu($this);
        }

        return $this;
    }

    public function removeSorty(Sortie $sorty): self
    {
        if ($this->sorties->removeElement($sorty)) {
            // set the owning side to null (unless already changed)
            if ($sorty->getLieu() === $this) {
                $sorty->setLieu(null);
            }
        }

        return $this;
    }

    public function getVille(): ?Ville
    {
        return $this->ville;
    }

    public function setVille(?Ville $ville): self
    {
        $this->ville = $ville;

        return $this;
    }


}
