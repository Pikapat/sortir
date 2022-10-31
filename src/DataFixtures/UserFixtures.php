<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
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
//         $user->setCampus();
         $user->setPseudo('admin');
         $user->setTelephone('0606060606');
         $user->setActif(true);


         $manager->persist($user);

        $manager->flush();
    }
}
