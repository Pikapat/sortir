<?php

namespace App\Controller;

use App\Form\ProfilType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;


#[IsGranted('ROLE_USER')]
#[Route('/profil')]
class ProfilController extends AbstractController
{

    // le nom de la route et la route on étét modifé pour pouvoir testre l'affichage de la page profil
    #[Route('/{id}', name: 'profil', requirements: ['id' =>'\d+'])]
    public function profil(int $id, UserRepository $repository, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher ): Response
    {
        // récupére le profil et affiche dan sle formulaire
        $user = $repository->find($id);
        $user->getCampus();
        $user->getPassword();

        $userForm = $this->createForm(ProfilType::class, $user);

        $userForm->handleRequest($request);


        if ($userForm->isSubmitted()){

                $newPass = $userForm->get('password')->getData();

                if ($newPass == null){
                $user->setPassword($user->getPassword());
                    $entityManager->persist($user);
                    $entityManager->flush();
                 }

                else{
                $newPass = $passwordHasher->hashPassword($user,$newPass);
                $user->setPassword($newPass);
                }

                $entityManager->persist($user);
                $entityManager->flush();
        }

        return $this->render('user/profil.html.twig', [
            'user_profil' => $userForm->createView(),
            'user' => $user
        ]);
    }

}
