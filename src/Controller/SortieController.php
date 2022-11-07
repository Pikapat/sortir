<?php

namespace App\Controller;


use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Form\AjouterSortieType;
use App\Form\AnnulerSortieType;
use App\Form\LieuType;
use App\Form\ModifierSortieType;
use App\Form\Model\SortieFilters;
use App\Form\SortieFiltersFormType;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use App\Service\EtatUpdateService;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[isGranted('ROLE_USER')]
#[Route('/sortie')]
class SortieController extends AbstractController
{

    #[Route('/', name: 'sorties')]
    public function list(Request $request, SortieRepository $sortieRepository, UserRepository $repository, EtatUpdateService $etatUpdateService): Response
    {
        // Actualise l'état des sorties
        //$etatUpdateService->updateEtats();

        $sortieFilters = new SortieFilters();
        $sortiesFilterForm = $this->createForm(SortieFiltersFormType::class, $sortieFilters);

        $sortiesFilterForm->handleRequest($request);

        if($sortiesFilterForm->isSubmitted()&& $sortiesFilterForm->isValid())
        {
            $user=($this->getUser());
            $sorties = $sortieRepository->findByFilters($sortieFilters, $user);
        }
        else{
            $sorties = $sortieRepository->findAllPubliee();
        }

        return $this->render('sortie/list.html.twig', [
            'sortiesFiltersForm' => $sortiesFilterForm->createView(),
            'sorties' => $sorties,
            'now' => new \DateTime()
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

        $lieu = new Lieu();
        $lieuForm = $this->createForm(LieuType::class, $lieu);
        $lieuForm->handleRequest($request);

        $sortieForm = $this->createForm(AjouterSortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        dump($sortieForm);

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

        if($lieuForm->isSubmitted())
        {
            $em->persist($lieu);
            $em->flush();

            $this->addFlash('success', 'Le lieu a bien été créé');
        }

        return $this->render('sortie/new.html.twig', [
            'sortieForm' => $sortieForm->createView(),
            'lieuForm' => $lieuForm->createView()
          ]);
    }

    #[Route('/modify/{id}', name: 'modifierSortie', requirements: ['id' => '\d+'])]
    public function modifier(EtatRepository $etatRepository, EntityManagerInterface $em,Request $request,Sortie $sortie): Response
    {
        if ($this->getUser() != $sortie->getOrganisateur()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas l\'organisateur de cette sortie');
        }
        
        $sortieForm = $this->createForm(ModifierSortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        $lieu = new Lieu();
        $lieuForm = $this->createForm(LieuType::class, $lieu);
        $lieuForm->handleRequest($request);

        if($sortieForm->isSubmitted() && $sortieForm->isValid()){

            if($sortieForm->get('enregistrer')->isClicked()){
                $etat = $etatRepository->findOneBy(['libelle' => 'Enregistrée']);
                $sortie->setEtat($etat);
                $sortie->setMotif(null);
            }
            elseif ($sortieForm->get('publier')->isClicked()) {
                $etat = $etatRepository->findOneBy(['libelle' => 'Publiée']);
                $sortie->setEtat($etat);
                $sortie->setMotif(null);
            }
            elseif ($sortieForm->get('annulerLaSortie')->isClicked()) {

                $this->redirectToRoute('annuler', ['id' => $sortie->getId()]);
            }

            $em->flush();

            $this->addFlash('success', 'La sortie a bien été modifié');

            return $this->redirectToRoute('afficherSortie', ['id' => $sortie->getId()]);
        }

        if($lieuForm->isSubmitted())
        {
            $em->persist($lieu);
            $em->flush();

            $this->addFlash('success', 'Le lieu a bien été créé');
        }


        return $this->render('sortie/modifierSortie.html.twig',[
            'sortie' => $sortie,
            'sortieForm' => $sortieForm->createView(),
            'lieuForm' => $lieuForm->createView()
        ]);
    }


    #[isGranted('ROLE_ADMIN')]
    #[Route('/delete/{id}', name: 'deleteSortie', requirements: ['id' => '\d+'])]
    public function deleteSortie(Request $request, EntityManagerInterface $em, Sortie $sortie): Response
    {
        if($this->isCsrfTokenValid('delete'. $sortie->getId(), $request->request->get('_token'))){
            $em->remove($sortie);
            $em->flush();
            $this->addFlash('success', 'La sortie a été supprimé !');
        }
        else{
            $this->addFlash('error', 'Le token CSRF est invalide !');
        }
        return $this->redirectToRoute('sorties');
    }


    #[Route('/annuler/{id}', name: 'annuler', requirements: ['id' => '\d+'])]
    public function annulerSortie(Request $request, EntityManagerInterface $em, Sortie $sortie, EtatRepository $etatRepository): Response
    {
            $sortieForm = $this->createForm(AnnulerSortieType::class, $sortie);

            $sortieForm->handleRequest($request);

            if($sortieForm->isSubmitted() && $sortieForm->isValid()){

                if($sortieForm->get('enregistrer')->isClicked()){

                    $etat = $etatRepository->findOneBy(['libelle' => 'Annulée']);
                    $sortie->setEtat($etat);
                }

                $em->flush();

                $this->addFlash('success', 'La sortie a bien été annulée');

                return $this->redirectToRoute('sorties');
            }

        return $this->render('sortie/annulerSortie.html.twig',[
            'sortie' => $sortie,
            'sortieForm' => $sortieForm->createView()
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

    #[Route('/listeLieu/{id}', name: 'listeLieu')]
    public function listeLieuDesVille(Request $request, LieuRepository $lieuRepository,VilleRepository $villeRepository, $id = 1)
    {

        $lieux = $lieuRepository->createQueryBuilder("q")
            ->where("q.ville = :villeid")
            ->setParameter("villeid", $id)
            ->getQuery()
            ->getResult();


        return $this->json($lieux, 200, [],  ['groups' => 'show_product',

        ]);

    }
}
