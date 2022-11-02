<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\User;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function  __construct(private UserPasswordHasherInterface $hasher)
    {

    }


    public function load(ObjectManager $manager): void
    {
         $user = new User();
         $user->setPrenom('admin');
         $user->setNom('admin');
         $user->setRoles(['ROLE_ADMIN']);
         $user->setPassword($this->hasher->hashpassword($user, "admin"));
         $user->setEmail('admin@gmail.com');
         $user->setCampus($this->getReference('campus-rennes'));
         $user->setPseudo('admin');
         $user->setTelephone('0606060606');
         $user->setActif(true);

         $this->addReference('admin', $user);


         $manager->persist($user);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [CampusFixtures::class, VilleFixtures::class];
    }
}
