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
        $etat->setCode('ENR');
        $this->addReference('enregistrer', $etat);
        $manager->persist($etat);

        $etat2 = new Etat();
        $etat2->setLibelle('Publiée');
        $etat2->setCode('PUB');
        $this->addReference('publier', $etat2);
        $manager->persist($etat2);

        $etat3 = new Etat();
        $etat3->setLibelle('Cloturée');
        $etat3->setCode('CLO');
        $this->addReference('cloturer', $etat3);
        $manager->persist($etat3);

        $etat4 = new Etat();
        $etat4->setLibelle('En cours');
        $etat4->setCode('ENC');
        $this->addReference('en-cours', $etat4);
        $manager->persist($etat4);

        $etat5 = new Etat();
        $etat5->setLibelle('Terminée');
        $etat5->setCode('TER');
        $this->addReference('terminer', $etat5);
        $manager->persist($etat5);

        $etat6 = new Etat();
        $etat6->setLibelle('Annulée');
        $etat6->setCode('ANN');
        $this->addReference('annuler', $etat6);
        $manager->persist($etat6);

        $etat7 = new Etat();
        $etat7->setLibelle('Archivée');
        $etat7->setCode('ARC');
        $this->addReference('archiver', $etat7);
        $manager->persist($etat7);

        $manager->flush();
    }
}
