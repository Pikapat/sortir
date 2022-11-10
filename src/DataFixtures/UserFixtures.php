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
        $user2->setPassword($this->hasher->hashpassword($user2, "user"));
        $user2->setEmail('user@gmail.com');
        $user2->setCampus($this->getReference('campus-rennes'));
        $user2->setPseudo('user');
        $user2->setTelephone('0102030405');
        $user2->setActif(true);

        $this->addReference('user', $user2);

        $manager->persist($user2);

        $user3 = new User();
        $user3->setPrenom('Nino');
        $user3->setNom('PONT');
        $user3->setRoles(['ROLE_USER']);
        $user3->setPassword($this->hasher->hashpassword($user3, "nino"));
        $user3->setEmail('nino@gmail.com');
        $user3->setCampus($this->getReference('campus-rennes'));
        $user3->setPseudo('Nino');
        $user3->setTelephone('050281215208');
        $user3->setActif(true);

        $this->addReference('nino', $user3);
        $manager->persist($user3);

        $user4 = new User();
        $user4->setPrenom('Marcela');
        $user4->setNom('Dorel');
        $user4->setRoles(['ROLE_USER']);
        $user4->setPassword($this->hasher->hashpassword($user3, "marcela"));
        $user4->setEmail('marcela@gmail.com');
        $user4->setCampus($this->getReference('campus-rennes'));
        $user4->setPseudo('Marcela');
        $user4->setTelephone('054551215208');
        $user4->setActif(true);

        $this->addReference('marcela', $user4);
        $manager->persist($user4);

        $user5 = new User();
        $user5->setPrenom('Antonio');
        $user5->setNom('Gozalo');
        $user5->setRoles(['ROLE_USER']);
        $user5->setPassword($this->hasher->hashpassword($user3, "antonio"));
        $user5->setEmail('antonio@gmail.com');
        $user5->setCampus($this->getReference('campus-rennes'));
        $user5->setPseudo('antonio');
        $user5->setTelephone('054551215208');
        $user5->setActif(true);

        $this->addReference('antonio', $user5);
        $manager->persist($user5);



        $manager->flush();
    }

    public function getDependencies()
    {
        return [CampusFixtures::class, VilleFixtures::class];
    }
}
