<?php

namespace App\Entity;

use App\Repository\VilleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VilleRepository::class)]
class Ville
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['show_product', 'list_product'])]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'le nom de la ville ne peut pas être vide')]
    #[Assert\NotNull(message: 'Une erreur est survenue')]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'le nom de la ville doit contenir au moins 2 caractères',
        maxMessage: 'le nom de la ville  ne peut pas contenir plus de 50 caractères')]
    #[Groups(['show_product', 'list_product'])]
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[Assert\NotBlank (message: 'Le nombre maximum d\'inscripts doit être renseigné')]
    #[Assert\NotNull(message: 'Le nombre maximum d\'inscripts doit être renseigné')]
    #[Assert\range(
        min : '2',
        minMessage: 'Le nombre d\'inscriptions acceptées doit être supérieur à 2')]
    #[Groups(['show_product', 'list_product'])]
    #[ORM\Column]
    private ?int $codePostal = null;


    #[ORM\OneToMany(mappedBy: 'ville', targetEntity: Lieu::class, orphanRemoval: true)]
    private Collection $lieus;

    public function __construct()
    {
        $this->lieus = new ArrayCollection();
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

    public function getCodePostal(): ?int
    {
        return $this->codePostal;
    }

    public function setCodePostal(int $codePostal): self
    {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * @return Collection<int, Lieu>
     */
    public function getLieus(): Collection
    {
        return $this->lieus;
    }

    public function addLieu(Lieu $lieu): self
    {
        if (!$this->lieus->contains($lieu)) {
            $this->lieus->add($lieu);
            $lieu->setVille($this);
        }

        return $this;
    }

    public function removeLieu(Lieu $lieu): self
    {
        if ($this->lieus->removeElement($lieu)) {
            // set the owning side to null (unless already changed)
            if ($lieu->getVille() === $this) {
                $lieu->setVille(null);
            }
        }

        return $this;
    }

}
