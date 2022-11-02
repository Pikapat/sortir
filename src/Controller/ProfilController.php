<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function profilTest(UserRepository $repository): Response
    {
        $user= new User();
        $userForm = $this->createForm(ProfilType::class, $user);

        return $this->render('user/profil.html.twig', [
            'user_profil' => $userForm->createView()
        ]);
    }


    // le nom de la route et la route on étét modifé pour pouvoir testre l'affichage de la page profil
    #[Route('/profile/{id}', name: 'profil', requirements: ['id' =>'\d+'])]
    public function profil(int $id, UserRepository $repository, Request $request, EntityManagerInterface $entityManager): Response
    {
        // récupére le profil et affiche dan sle formulaire
        $user = $repository->find($id);
        $userForm = $this->createForm(ProfilType::class, $user);

        $userForm->handleRequest($request);

//        if ($userForm->isSubmitted()){
//
//
//        }


        return $this->render('user/profil.html.twig', [
            'user_profil' => $userForm->createView()
        ]);
    }
}
