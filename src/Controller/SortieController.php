<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\AjouterSortieType;
use App\Form\SortieFiltersFormType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

#[Route('/sortie')]
class SortieController extends AbstractController
{
    #[Route('/', name: 'sorties')]
    public function list(Request $request, SortieRepository $sortieRepository): Response
    {
        $sortiesFilterForm = $this->createForm(SortieFiltersFormType::class);

        $sortiesFilterForm->handleRequest($request);

        $campusId = $sortiesFilterForm->get('campus')->getData();

        if($sortiesFilterForm->isSubmitted())
        {
            dump($campusId);
        }


        $sorties = $sortieRepository->findAll();

        return $this->render('sortie/list.html.twig', [
            'sortiesFiltersForm' => $sortiesFilterForm->createView(),
            'sorties' => $sorties
        ]);
    }

    #[Route('/{id}', name: 'afficherSortie', requirements: ['id' => '\d+'])]
    public function afficher(Sortie $sortie): Response
    {
        return $this->render('sortie/afficherSortie.html.twig',[
            'sortie' => $sortie
        ]);
    }

    #[Route('/modify/{id}', name: 'modifierSortie', requirements: ['id' => '\d+'])]
    public function modifier(Sortie $sortie): Response
    {
        return $this->render('sortie/modifierSortie.html.twig',[
            'sortie' => $sortie
        ]);
    }

    #[Route('/new', name: 'newSortie')]
    public function new(Request $request, EntityManagerInterface $em, EtatRepository $etatRepository): Response
    {
        $sortie = new Sortie();
        $sortie->setOrganisateur($this->getUser());


        $sortieForm = $this->createForm(AjouterSortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()){

            if($sortieForm->get('enregistrer')->isClicked()){
                $etat = $etatRepository->findOneBy(['libelle' => 'Enregistrée']);
                $sortie->setEtat($etat);
            }
            elseif ($sortieForm->get('publier')->isClicked()) {
                $etat = $etatRepository->findOneBy(['libelle' => 'Publiée']);
                $sortie->setEtat($etat);
            }


            $em->persist($sortie);
            $em->flush();

            $this->addFlash('success', 'La sortie a bien été créée');
        }



        return $this->render('sortie/new.html.twig', [
            'sortieForm' =>$sortieForm->createView()
          ]);
    }

    #[Route('/listeLieu/{id}', name: 'listeLieu')]
    public function listeLieuDesVille(Request $request, LieuRepository $lieus, $id = 1)
    {
       $result = $lieus->createQueryBuilder("q")
            ->where("q.ville = :villeid")
            ->setParameter("villeid", $id)
            ->getQuery()
            ->getResult();

       return $this->json($result, 200, [],  ['groups' => 'show_product',

           ]);

    }

    #[Route('/inscrire/{id}', name: 'sInscrireSortie', requirements: ['id' => '\d+'])]
    public function inscrire(UserRepository $userRepository, $id, SortieRepository $sortieRepository)
    {
        $user = $this->getUser();
        $user->addSortiesInscrit($sortieRepository->find($id));
        $userRepository->save($user, true);

        return $this->redirectToRoute('sorties');
    }

    #[Route('/desist/{id}', name: 'seDesisterSortie', requirements: ['id' => '\d+'])]
    public function desist(UserRepository $userRepository, SortieRepository $sortieRepository, $id)
    {
        $user = $this->getUser();
        $user->removeSortiesInscrit($sortieRepository->find($id));
        $userRepository->save($user, true);

        return $this->redirectToRoute('sorties');
    }
}
