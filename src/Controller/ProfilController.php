<?php

namespace App\Controller;

use App\Form\ProfilType;
use App\Repository\UserRepository;
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

    #[Route('/{id}', name: 'profil', requirements: ['id' => '\d+'])]
    public function profil(int $id, UserRepository $repository, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher, SluggerInterface $slugger): Response
    {
        // récupére le profil et affiche dan sle formulaire
        $user = $repository->find($id);
        $user->getCampus();
        $user->getPassword();

        $userForm = $this->createForm(ProfilType::class, $user);

        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {

                $newPass = $userForm->get('password')->getData();
                $picture = $userForm->get('picture')->getData();

                if ($newPass == null) {
                    $user->setPassword($user->getPassword());
                    $entityManager->persist($user);
                    $entityManager->flush();
                } else {
                    $newPass = $passwordHasher->hashPassword($user, $newPass);
                    $user->setPassword($newPass);
                }

                // this condition is needed because the 'brochure' field is not required
                // so the PDF file must be processed only when a file is uploaded
                /** @var UploadedFile $picture */
                if ($picture) {

                    $originalFilename = pathinfo($picture->getClientOriginalName(), PATHINFO_FILENAME);
                    // this is needed to safely include the file name as part of the URL
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' . $picture->guessExtension();

                    // Move the file to the directory where brochures are stored
                    try {
                        $picture->move(
                            $this->getParameter('brochures_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        // ... handle exception if something happens during file upload
                    }
                    // updates the 'brochureFilename' property to store the file name
                    // instead of its contents
                    $user->setPicture($newFilename);
//                    $user->setPicture(new File($this->getParameter('brochures_directory').'/'.$user->getPicture()));

                    //    if ($this->getParameter('brochures_directory').includeFile($originalFilename)){
//                        $oldPicture = $this->getParameter('brochures_directory');
//                        $entityManager->remove($oldPicture);
//                    }

                }

                $entityManager->persist($user);
                $entityManager->flush();

                $this->addFlash('success', 'Modifications effectuées.');
        }else{
                $this->addFlash('error', 'Une erreur est survenue !');
        }

        return $this->render('user/profil.html.twig', [
            'user_profil' => $userForm->createView(),
            'user' => $user
        ]);
    }

}
