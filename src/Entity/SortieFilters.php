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








}