<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Form\AjouterSortieType;
use App\Form\SortieFiltersFormType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Context;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

#[Route('/sortie')]
class SortieController extends AbstractController
{
    #[Route('/', name: 'sorties')]
    public function list(SortieRepository $sortieRepository): Response
    {
        $sortiesFilterForm = $this->createForm(SortieFiltersFormType::class);

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
    public function listeLieuDesVille(Request $request, LieuRepository $lieus, $id)
    {
       $result = $lieus->createQueryBuilder("q")
            ->where("q.ville = :villeid")
            ->setParameter("villeid", $id)
            ->getQuery()
            ->getResult();

       return $this->json($result, 200, [],  ['groups' => 'show_product',
           'id' => $id
           ]);

    }

    #[Route('/delete', name: 'deleteSortie')]
    public function delete(): Response
    {
        return $this->render('sortie/delete.html.twig');
    }
}
