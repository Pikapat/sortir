<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\User;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use phpDocumentor\Reflection\Types\This;
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


        $user2 = new User();
        $user2->setPrenom('user');
        $user2->setNom('user');
        $user2->setRoles(['ROLE_USER']);
        $user2->setPassword($this->hasher->hashpassword($user2, "user2"));
        $user2->setEmail('user@gmail.com');
        $user2->setCampus($this->getReference('campus-rennes'));
        $user2->setPseudo('user');
        $user2->setTelephone('0102030405');
        $user2->setActif(true);

        $this->addReference('user', $user2);

        $manager->persist($user2);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [CampusFixtures::class, VilleFixtures::class];
    }
}
