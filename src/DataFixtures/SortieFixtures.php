<?php

namespace App\DataFixtures;

use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;

class SortieFixtures extends Fixture implements DependentFixtureInterface
{


    public function load(ObjectManager $manager): void
    {
        $users = [$this->getReference('admin'), $this->getReference('user'), $this->getReference('nino'), $this->getReference('marcela'), $this->getReference('antonio')];
        $lieux = [$this->getReference('pont-caf'), $this->getReference('planete-sauvage'), $this->getReference('gaumont')];
        $etats = [$this->getReference('publier'), $this->getReference('enregistrer')];
        $campuses = [$this->getReference('campus-nantes'), $this->getReference('campus-rennes')];

//        $sortie = new Sortie();
//        $sortie->setTitre('Escalade');
//        $sortie->setDateHeureDebut(new \DateTime());
//        $sortie->setDateLimiteInscription(new \DateTime());
//        $sortie->setDuree(5);
//        $sortie->setInfosSortie('escalade au bort de la rivière');
//        $sortie->setNbInscriptionsMax(20);
//        $sortie->setSiteOrganisateur($this->getReference('campus-nantes'));
//        $sortie->setLieu($this->getReference('pont-caf'));
//        $sortie->setEtat($this->getReference('publier'));
//        $sortie->setOrganisateur($this->getReference('admin'));
//        $manager->persist($sortie);
//
//        $sortie2 = new Sortie();
//        $sortie2->setTitre('Zoo');
//        $sortie2->setDateHeureDebut(new \DateTime());
//        $sortie2->setDateLimiteInscription(new \DateTime());
//        $sortie2->setDuree(6);
//        $sortie2->setInfosSortie('safari en voiture guidée');
//        $sortie2->setNbInscriptionsMax(20);
//        $sortie2->setSiteOrganisateur($this->getReference('campus-nantes'));
//        $sortie2->setLieu($this->getReference('planete-sauvage'));
//        $sortie2->setEtat($this->getReference('publier'));
//        $sortie2->setOrganisateur($this->getReference('admin'));
//        $manager->persist($sortie2);
//
//        $sortie3 = new Sortie();
//        $sortie3->setTitre('Cinéma');
//        $sortie3->setDateHeureDebut(new \DateTime());
//        $sortie3->setDateLimiteInscription(new \DateTime());
//        $sortie3->setDuree(2);
//        $sortie3->setInfosSortie('film 4dx');
//        $sortie3->setNbInscriptionsMax(20);
//        $sortie3->setSiteOrganisateur($this->getReference('campus-rennes'));
//        $sortie3->setLieu($this->getReference('gaumont'));
//        $sortie3->setEtat($this->getReference('publier'));
//        $sortie3->setOrganisateur($this->getReference('admin'));
//        $manager->persist($sortie3);
//
//        $sortie4 = new Sortie();
//        $sortie4->setTitre('Bar');
//        $sortie4->setDateHeureDebut(new \DateTime("12/20/2022"));
//        $sortie4->setDateLimiteInscription(new \DateTime("11/20/2022"));
//        $sortie4->setDuree(3);
//        $sortie4->setInfosSortie('une bonne binouze au V&B');
//        $sortie4->setNbInscriptionsMax(15);
//        $sortie4->setSiteOrganisateur($this->getReference('campus-rennes'));
//        $sortie4->setLieu($this->getReference('V&B'));
//        $sortie4->setEtat($this->getReference('enregistrer'));
//        $sortie4->setOrganisateur($this->getReference('user'));
//        $manager->persist($sortie4);
//
//        $sortie5 = new Sortie();
//        $sortie5->setTitre('Cinéma');
//        $sortie5->setDateHeureDebut(new \DateTime("11/21/2022"));
//        $sortie5->setDateLimiteInscription(new \DateTime("11/20/2022"));
//        $sortie5->setDuree(2);
//        $sortie5->setInfosSortie('Avatar');
//        $sortie5->setNbInscriptionsMax(20);
//        $sortie5->setSiteOrganisateur($this->getReference('campus-nantes'));
//        $sortie5->setLieu($this->getReference('gaumont'));
//        $sortie5->setEtat($this->getReference('publier'));
//        $sortie5->setOrganisateur($this->getReference('nino'));
//        $manager->persist($sortie5);
//
//        $sortie7 = new Sortie();
//        $sortie7->setTitre('Cinéma');
//        $sortie7->setDateHeureDebut(new \DateTime());
//        $sortie7->setDateLimiteInscription(new \DateTime("11/02/2022"));
//        $sortie7->setDuree(2);
//        $sortie7->setInfosSortie('film 3d');
//        $sortie7->setNbInscriptionsMax(20);
//        $sortie7->setSiteOrganisateur($this->getReference('campus-rennes'));
//        $sortie7->setLieu($this->getReference('gaumont'));
//        $sortie7->setEtat($this->getReference('c'));
//        $sortie7->setOrganisateur($this->getReference('nino'));
//        $manager->persist($sortie7);
//
        $generator = Factory::create('fr_FR');

        for ($i=0; $i<100; $i++){
            $sortie = (new Sortie())
                ->setTitre($generator->text(35))
                ->setInfosSortie($generator->text(150))
                ->setDuree($generator->numberBetween(1,30))
                ->setEtat($etats[random_int(0,count($etats) - 1)])
                ->setLieu($lieux[random_int(0,count($lieux) - 1)])
                ->setNbInscriptionsMax($generator->numberBetween(1,20))
                ->setSiteOrganisateur($campuses[random_int(0,count($campuses) - 1)])
                ->setOrganisateur($users[random_int(0,count($users) - 1)]);

            $sortie->setDateHeureDebut($generator->dateTimeBetween('now - 1 month', 'now + 2 month'));
            $dateDebut = $sortie->getDateHeureDebut();
            $sortie->setDateLimiteInscription($generator->dateTimeBetween('now - 2 month', $dateDebut));

            $manager->persist($sortie);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [LieuFixtures::class, EtatFixtures::class];
    }
}
