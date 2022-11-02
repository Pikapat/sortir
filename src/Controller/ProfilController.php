<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil')]
class ProfilController extends AbstractController
{

    // le nom de la route et la route on étét modifé pour pouvoir testre l'affichage de la page profil
    #[Route('/{id}', name: 'profil', requirements: ['id' =>'\d+'])]
    public function profil(int $id, UserRepository $repository, Request $request, EntityManagerInterface $entityManager, ManagerRegistry $doctrine ): Response
    {
        // récupére le profil et affiche dan sle formulaire
        $user = $repository->find($id);
        $userForm = $this->createForm(ProfilType::class, $user);

        $userForm->handleRequest($request);

        if ($userForm->isSubmitted()){
            if($userForm->get('password')->isEmpty()){
                $userForm->get('password')->setData($user->getPassword());
            }

                $entityManager->persist($user);
                $entityManager->flush($user);
        }

        return $this->render('user/profil.html.twig', [
            'user_profil' => $userForm->createView(),
            'user' => $user
        ]);
    }
}
