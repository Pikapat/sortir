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

        $sortie4 = new Sortie();
        $sortie4->setTitre('Bar');
        $sortie4->setDateHeureDebut(new \DateTime("12/20/2022"));
        $sortie4->setDateLimiteInscription(new \DateTime("11/20/2022"));
        $sortie4->setDuree(3);
        $sortie4->setInfosSortie('une bonne binouze au V&B');
        $sortie4->setNbInscriptionsMax(15);
        $sortie4->setSiteOrganisateur($this->getReference('campus-rennes'));
        $sortie4->setLieu($this->getReference('V&B'));
        $sortie4->setEtat($this->getReference('enregistrer'));
        $sortie4->setOrganisateur($this->getReference('user'));
        $manager->persist($sortie4);

        $sortie5 = new Sortie();
        $sortie5->setTitre('Cinéma');
        $sortie5->setDateHeureDebut(new \DateTime("11/21/2022"));
        $sortie5->setDateLimiteInscription(new \DateTime("11/20/2022"));
        $sortie5->setDuree(2);
        $sortie5->setInfosSortie('Avatar');
        $sortie5->setNbInscriptionsMax(20);
        $sortie5->setSiteOrganisateur($this->getReference('campus-nantes'));
        $sortie5->setLieu($this->getReference('gaumont'));
        $sortie5->setEtat($this->getReference('publier'));
        $sortie5->setOrganisateur($this->getReference('nino'));
        $manager->persist($sortie5);

        $sortie6 = new Sortie();
        $sortie6->setTitre('Ballade');
        $sortie6->setDateHeureDebut(new \DateTime("08/21/2022"));
        $sortie6->setDateLimiteInscription(new \DateTime("07/21/2022"));
        $sortie6->setDuree(3);
        $sortie6->setInfosSortie('promener Tanguy');
        $sortie6->setNbInscriptionsMax(5);
        $sortie6->setSiteOrganisateur($this->getReference('campus-rennes'));
        $sortie6->setLieu($this->getReference('pont-caf'));
        $sortie6->setEtat($this->getReference('publier'));
        $sortie6->setOrganisateur($this->getReference('user'));
        $manager->persist($sortie6);

        $sortie7 = new Sortie();
        $sortie7->setTitre('Cinéma');
        $sortie7->setDateHeureDebut(new \DateTime());
        $sortie7->setDateLimiteInscription(new \DateTime("11/02/2022"));
        $sortie7->setDuree(2);
        $sortie7->setInfosSortie('film 3d');
        $sortie7->setNbInscriptionsMax(20);
        $sortie7->setSiteOrganisateur($this->getReference('campus-rennes'));
        $sortie7->setLieu($this->getReference('gaumont'));
        $sortie7->setEtat($this->getReference('en-cours'));
        $sortie7->setOrganisateur($this->getReference('nino'));
        $manager->persist($sortie7);



        //        // Utilisation de Faker
        $generator = Factory::create('fr_FR');
        $populator = new Populator($generator, $manager);
        $populator->addEntity(Sortie::class, 100, [
            'titre' => function() use ($generator) {
                return $generator->text(80);
            },
            'dateHeureDebut' => function() use ($generator) {
                return $generator->dateTimeBetween('now - 2 month', 'now + 1 month');
            },
            'dateLimiteInscription' => function() use ($generator) {
                return $generator->dateTimeBetween('now - 2 month', 'now + 1 month');
            },
            'duree' => function() use ($generator) {
                return $generator->numberBetween(1,5);
            },
            'nbInscriptionsMax' => function() use ($generator) {
                return $generator->numberBetween(3,20);
            },
            'infosSortie' => function() use ($generator) {
                return $generator->text;
            },
            'usersInscrits' => null,

            'organisateur' => function(){
            return $this->getReference('admin');
            },
            'siteOrganisateur' => function() {
                return ($this->getReference('campus-rennes'));
            },
            'etat' => function(){
               return $this->getReference('publier');
            },
            'lieu' => function(){
                return $this->getReference('gaumont');
            }
        ]);
        $populator->execute();

        $manager->flush();
    }

    public function getDependencies()
    {
        return [LieuFixtures::class, EtatFixtures::class];
    }
}
