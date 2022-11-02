<?php

namespace App\DataFixtures;

use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VilleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $ville = new Ville();
        $ville->setNom('Chateau Thebaud');
        $ville->setCodePostal('44900');
        $this->addReference('ville', $ville);
        $manager->persist($ville);

        $ville2 = new Ville();
        $ville2->setNom('Rennes');
        $ville2->setCodePostal('35900');
        $this->addReference('ville2', $ville2);
        $manager->persist($ville2);

        $ville3 = new Ville();
        $ville3->setNom('Port-Saint-Père');
        $ville3->setCodePostal('44710');
        $this->addReference('ville3', $ville3);
        $manager->persist($ville3);


        $manager->flush();
    }
}
