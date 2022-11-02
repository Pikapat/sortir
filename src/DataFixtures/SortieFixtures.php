<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\This;

class SortieFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $sortie = new Sortie();
        $sortie->setTitre('Escalade');
        $sortie->setDateHeureDebut(new \DateTime());
        $sortie->setDateLimiteInscription(new \DateTime());
        $sortie->setDuree(5);
        $sortie->setInfosSortie('escalade au bort de la rivière');
        $sortie->setNbInscriptionsMax(20);
        $sortie->setSiteOrganisateur($this->getReference('campus-nantes'));
        $sortie->setLieu($this->getReference('pont-caf'));
        $sortie->setEtat($this->getReference('publier'));
        $sortie->setOrganisateur($this->getReference('admin'));
        $manager->persist($sortie);

        $sortie2 = new Sortie();
        $sortie2->setTitre('Zoo');
        $sortie2->setDateHeureDebut(new \DateTime());
        $sortie2->setDateLimiteInscription(new \DateTime());
        $sortie2->setDuree(6);
        $sortie2->setInfosSortie('safari en voiture guidée');
        $sortie2->setNbInscriptionsMax(20);
        $sortie2->setSiteOrganisateur($this->getReference('campus-nantes'));
        $sortie2->setLieu($this->getReference('planete-sauvage'));
        $sortie2->setEtat($this->getReference('publier'));
        $sortie2->setOrganisateur($this->getReference('admin'));
        $manager->persist($sortie2);

        $sortie3 = new Sortie();
        $sortie3->setTitre('Cinéma');
        $sortie3->setDateHeureDebut(new \DateTime());
        $sortie3->setDateLimiteInscription(new \DateTime());
        $sortie3->setDuree(2);
        $sortie3->setInfosSortie('film 4dx');
        $sortie3->setNbInscriptionsMax(20);
        $sortie3->setSiteOrganisateur($this->getReference('campus-rennes'));
        $sortie3->setLieu($this->getReference('gaumont'));
        $sortie3->setEtat($this->getReference('publier'));
        $sortie3->setOrganisateur($this->getReference('admin'));
        $manager->persist($sortie3);



        $manager->flush();
    }

    public function getDependencies()
    {
        return [LieuFixtures::class, EtatFixtures::class];
    }
}
