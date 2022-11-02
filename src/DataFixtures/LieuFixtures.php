<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LieuFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {

        $lieu = new Lieu();
        $lieu->setNom('Pont Cafino');
        $lieu->setRue('Pont Caf rue des compagnons');
        $lieu->setVille($this->getReference('ville'));
        $lieu->setLatitude('000000000');
        $lieu->setLongitude('111111111');
        $this->addReference('pont-caf', $lieu);

        $manager->persist($lieu);


        $lieu2 = new Lieu();
        $lieu2->setNom('Gaumont');
        $lieu2->setRue('12 rue Yvonne Jean-Haffen');
        $lieu2->setVille($this->getReference('ville2'));
        $lieu2->setLatitude('000000000');
        $lieu2->setLongitude('111111111');
        $this->addReference('gaumont', $lieu2);
        $manager->persist($lieu2);

        $lieu3 = new Lieu();
        $lieu3->setNom('Planète Sauvage');
        $lieu3->setVille($this->getReference('ville3'));
        $lieu3->setRue('La Chevalerie 44710');
        $lieu3->setLatitude('000000000');
        $lieu3->setLongitude('111111111');
        $this->addReference('planete-sauvage', $lieu3);
        $manager->persist($lieu3);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [UserFixtures::class];
    }
}
