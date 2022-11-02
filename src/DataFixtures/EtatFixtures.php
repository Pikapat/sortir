<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EtatFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $etat = new Etat();
        $etat->setLibelle('Enregistrée');
        $this->addReference('enregistrer', $etat);
        $manager->persist($etat);

        $etat2 = new Etat();
        $etat2->setLibelle('Publiée');
        $this->addReference('publier', $etat2);
        $manager->persist($etat2);

        $etat3 = new Etat();
        $etat3->setLibelle('Cloturée');
        $this->addReference('cloturer', $etat3);
        $manager->persist($etat3);

        $etat4 = new Etat();
        $etat4->setLibelle('En cours');
        $this->addReference('en-cours', $etat4);
        $manager->persist($etat4);

        $etat5 = new Etat();
        $etat5->setLibelle('Terminée');
        $this->addReference('terminer', $etat5);
        $manager->persist($etat5);

        $etat6 = new Etat();
        $etat6->setLibelle('Annulée');
        $this->addReference('annuler', $etat6);
        $manager->persist($etat6);

        $etat7 = new Etat();
        $etat7->setLibelle('Archivée');
        $this->addReference('archiver', $etat7);
        $manager->persist($etat7);

        $manager->flush();
    }
}
