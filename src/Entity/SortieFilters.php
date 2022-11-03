<?php

namespace App\Entity;

use phpDocumentor\Reflection\Types\Boolean;

class SortieFilters
{
    private ?Campus $campus = null;

    private ?string $textFilter = null;

    private ?\DateTimeInterface $dateDebut = null;

    private ?\DateTimeInterface $dateFin = null;

    private ?bool $userOrga = null;

    private ?bool $userInscrit = null;

    private ?bool $userNonInscrit = null;

    private ?bool $sortiePassee = null;

    /**
     * @return Campus|null
     */
    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    /**
     * @param Campus|null $campus
     * @return SortieFilters
     */
    public function setCampus(?Campus $campus): SortieFilters
    {
        $this->campus = $campus;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTextFilter(): ?string
    {
        return $this->textFilter;
    }

    /**
     * @param string|null $textFilter
     * @return SortieFilters
     */
    public function setTextFilter(?string $textFilter): SortieFilters
    {
        $this->textFilter = $textFilter;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    /**
     * @param \DateTimeInterface|null $dateDebut
     * @return SortieFilters
     */
    public function setDateDebut(?\DateTimeInterface $dateDebut): SortieFilters
    {
        $this->dateDebut = $dateDebut;
        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    /**
     * @param \DateTimeInterface|null $dateFin
     * @return SortieFilters
     */
    public function setDateFin(?\DateTimeInterface $dateFin): SortieFilters
    {
        $this->dateFin = $dateFin;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getUserOrga(): ?bool
    {
        return $this->userOrga;
    }

    /**
     * @param bool|null $userOrga
     * @return SortieFilters
     */
    public function setUserOrga(?bool $userOrga): SortieFilters
    {
        $this->userOrga = $userOrga;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getUserInscrit(): ?bool
    {
        return $this->userInscrit;
    }

    /**
     * @param bool|null $userInscrit
     * @return SortieFilters
     */
    public function setUserInscrit(?bool $userInscrit): SortieFilters
    {
        $this->userInscrit = $userInscrit;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getUserNonInscrit(): ?bool
    {
        return $this->userNonInscrit;
    }

    /**
     * @param bool|null $userNonInscrit
     * @return SortieFilters
     */
    public function setUserNonInscrit(?bool $userNonInscrit): SortieFilters
    {
        $this->userNonInscrit = $userNonInscrit;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getSortiePassee(): ?bool
    {
        return $this->sortiePassee;
    }

    /**
     * @param bool|null $sortiePassee
     * @return SortieFilters
     */
    public function setSortiePassee(?bool $sortiePassee): SortieFilters
    {
        $this->sortiePassee = $sortiePassee;
        return $this;
    }


}