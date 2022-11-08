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
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use App\Service\EtatUpdateService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use phpDocumentor\Reflection\Types\True_;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[isGranted('ROLE_USER')]
#[Route('/sortie')]
class SortieController extends AbstractController
{

    /**
     * @throws Exception
     */
    #[Route('/', name: 'sorties')]
    public function list(Request $request, SortieRepository $sortieRepository, UserRepository $repository, EtatUpdateService $etatUpdateService): Response
    {
//         Actualise l'état des sorties
        $etatUpdateService->updateEtats();

        $sortieFilters = new SortieFilters();
        $sortiesFilterForm = $this->createForm(SortieFiltersFormType::class, $sortieFilters);

        $sortiesFilterForm->handleRequest($request);

        $user=($this->getUser());
        $sorties = $sortieRepository->findByFilters($sortieFilters, $user);

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

        $lieuForm = $this->handleLieuForm($request, $em);

        $sortieForm = $this->createForm(AjouterSortieType::class, $sortie);

        $sortieForm->handleRequest($request);


        if($sortieForm->isSubmitted()){
            if ($sortieForm->isValid()){
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
            else{
                $this->addFlash('error', 'Une erreur est survenue');
            }
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

        $lieuForm = $this->handleLieuForm($request, $em);

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

                $this->redirectToRoute('annulerSortie', ['id' => $sortie->getId()]);
            }

            $em->flush();

            $this->addFlash('success', 'La sortie a bien été modifié');

            return $this->redirectToRoute('afficherSortie', ['id' => $sortie->getId()]);
        }


        return $this->render('sortie/modifierSortie.html.twig',[
            'sortie' => $sortie,
            'sortieForm' => $sortieForm->createView(),
            'lieuForm' => $lieuForm->createView()
        ]);
    }


    #[Route('/annuler/{id}', name: 'annulerSortie', requirements: ['id' => '\d+'])]
    public function annulerSortie(Request $request, EntityManagerInterface $em, Sortie $sortie, EtatRepository $etatRepository): Response
    {
        if ($this->getUser() != $sortie->getOrganisateur()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas l\'organisateur de cette sortie');
        }


        elseif ($sortie->getEtat()->getCode() == 'ENC' ||
            $sortie->getEtat()->getCode() == 'TER' ||
            $sortie->getEtat()->getCode() == 'ANN' ||
            $sortie->getEtat()->getCode() == 'ARC')
        {
            throw $this->createAccessDeniedException('Vous n\'êtes pas l\'organisateur de cette sortie');
        }

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

    private function handleLieuForm(Request $request, EntityManagerInterface $em): \Symfony\Component\Form\FormInterface
    {
        $lieu = new Lieu();
        $lieuForm = $this->createForm(LieuType::class, $lieu);
        $lieuForm->handleRequest($request);

        if($lieuForm->isSubmitted())
        {
            if ($lieuForm->isValid())
            {
                $em->persist($lieu);
                $em->flush();

                $this->addFlash('success', 'Le lieu a bien été créé');
            }
            else{
                $this->addFlash('error', 'Une erreur est survenue');
            }

        }

        return $lieuForm;
    }
}
