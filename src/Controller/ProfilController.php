<?php

namespace App\Controller;

use App\Form\ProfilType;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use App\Service\FileUploaderService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use function Composer\Autoload\includeFile;


#[IsGranted('ROLE_USER')]
#[Route('/profil')]
class ProfilController extends AbstractController
{

    #[Route('/modifier/{id}', name: 'modifierProfil', requirements: ['id' => '\d+'])]
    public function modifierProfil(int $id, UserRepository $repository, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, FileUploaderService $fileUploader): Response
    {
        // Deny access if ID doesn't correspond to connected User
        if ($this->getUser() !== $repository->find($id)) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas accéder à ce profil.');
        }

        // récupére le profil et affiche dans le formulaire
        $user = $repository->find($id);
//        $user = $repository->findAllAboutUser($id);

//        $user->getCampus();
//        $user->getPassword();

        $userForm = $this->createForm(ProfilType::class, $user);

        $userForm->handleRequest($request);

        if ($userForm->isSubmitted()) {
            if ($userForm->isValid()) {
                $newPass = $userForm->get('password')->getData();

                if ($newPass == null) {
                    $user->setPassword($user->getPassword());
                    $entityManager->persist($user);
                    $entityManager->flush();
                } else {
                    $newPass = $passwordHasher->hashPassword($user, $newPass);
                    $user->setPassword($newPass);
                }

                /** @var UploadedFile $pictureFile */
                $pictureFile = $userForm->get('picture')->getData();
                if ($pictureFile) {
                    $picture = $fileUploader->upload($pictureFile);
                    $user->setPicture($picture);
                }

                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'Modifications effectuées.');

                return $this->render('user/afficherProfil.html.twig', [
                    'id' => $user->getId(),
                    'user_profil' => $userForm->createView(),
                    'user' => $user
                ]);

            }
            else{
                $this->addFlash('error', 'Une erreur est survenue !');
            }
        }

        return $this->render('user/modifyProfil.html.twig', [
            'user_profil' => $userForm->createView(),
            'user' => $user
        ]);
    }

    #[Route('/afficher/{id}', name: 'afficherProfil', requirements: ['id' => '\d+'])]
    public function affficherProfil(UserRepository $userRepository, int $id)
    {
//        $user = $userRepository->findAllAboutUser($id);

        $user = $userRepository->find($id);

        return $this->render('user/afficherProfil.html.twig', [
            'user' => $user
        ]);
    }

}
