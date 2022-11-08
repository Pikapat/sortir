<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Ville;
use App\Form\CampusType;
use App\Form\VilleType;
use App\Repository\CampusRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[isGranted('ROLE_ADMIN')]
#[Route('/admin')]
class AdminController extends AbstractController
{
    #[Route('/villes', name: 'villes')]
    public function villes(Request $request,VilleRepository $villeRepository, EntityManagerInterface $em): Response
    {
        $villes = $villeRepository->findAll();

        $ville = new Ville();

        $villesForm = $this->createForm(VilleType::class, $ville);

        $villesForm->handleRequest($request);

       if($villesForm->isSubmitted() && $villesForm->isValid()){

           $em->persist($ville);
           $em->flush();

           $this->addFlash('success', 'La ville a bien été ajoutée');

           $villes = $villeRepository->findAll();
       }

        return $this->render('admin/villes.html.twig',[
            'villesForm' => $villesForm->createView(),
            'villes' => $villes
        ]);
    }

    #[Route('/deleteVille/{id}', name: 'ville_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function deleteVille(Request $request, EntityManagerInterface $em, Ville $ville): Response
    {
        if($this->isCsrfTokenValid('delete'. $ville->getId(), $request->request->get('_token'))){
            $em->remove($ville);
            $em->flush();
            $this->addFlash('success', 'La ville a été supprimée !');
        }
        else{
            $this->addFlash('error', 'Le token CSRF est invalide !');
        }
        return $this->redirectToRoute('villes');
    }


    #[Route('/campus', name: 'campus')]
    public function campus(Request $request,CampusRepository $campusRepository, EntityManagerInterface $em): Response
    {
        $campuss = $campusRepository->findAll();

        $campus = new Campus();

        $campusForm = $this->createForm(CampusType::class, $campus);

        $campusForm->handleRequest($request);

        if($campusForm->isSubmitted() && $campusForm->isValid()){

            $em->persist($campus);
            $em->flush();

            $this->addFlash('success', 'Le campus a bien été ajouté');

            $campuss = $campusRepository->findAll();
        }

        return $this->render('admin/campus.html.twig',[
            'campusForm' => $campusForm->createView(),
            'campus' => $campuss
        ]);
    }

    #[Route('/deleteCampus/{id}', name: 'campus_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function deleteCampus(Request $request, EntityManagerInterface $em, Campus $campus): Response
    {
        if($this->isCsrfTokenValid('delete'. $campus->getId(), $request->request->get('_token'))){
            $em->remove($campus);
            $em->flush();
            $this->addFlash('success', 'Le campus a été supprimé !');
        }
        else{
            $this->addFlash('error', 'Le token CSRF est invalide !');
        }
        return $this->redirectToRoute('campus');
    }


    #[Route('/modifierCampus/{id}', name: 'campus_modify', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function modifierCampus(Request $request, EntityManagerInterface $em, Campus $campus): Response
    {
    if($this->isCsrfTokenValid('update'. $campus->getId(), $request->request->get('_token'))){
        $em->persist($campus);
        $em->flush();
        $this->addFlash('success', 'Le campus a été modifié !');
    }
    else{
        $this->addFlash('error', 'Le token CSRF est invalide !');
    }
    return $this->redirectToRoute('campus');
    }
}