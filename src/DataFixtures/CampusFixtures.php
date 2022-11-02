<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CampusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $campus = new Campus();
        $campus->setNom('Saint-Herblain');
        $this->addReference('campus-nantes', $campus);
        $manager->persist($campus);


        $campus2 = new Campus();
        $campus2->setNom('Chartres de Bretagne');
        $this->addReference('campus-rennes', $campus2);
        $manager->persist($campus2);

        $campus3 = new Campus();
        $campus3->setNom('La Roche sur Yon');
        $this->addReference('campus-roche', $campus3);
        $manager->persist($campus3);

        $manager->flush();
    }
}
