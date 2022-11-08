<?php

namespace App\Service;

use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class EtatUpdateService
{
    private EntityManagerInterface $em;
    private SortieRepository $sortieRepository;
    private EtatRepository $etatRepository;

    public function __construct(EntityManagerInterface $em, SortieRepository $sortieRepository, EtatRepository $etatRepository)
    {
        $this->em = $em;
        $this->sortieRepository = $sortieRepository;
        $this->etatRepository = $etatRepository;
    }


    /**
     * @throws Exception
     */
    public function updateEtats(): void
    {
//        // Clôture des sorties en cours quand la date de find d'inscription est dépassée
//       $qb =  $em->createQuery(
//           'UPDATE sortie s SET s.etat_id = 3
//                WHERE s.date_limite_inscription < now() AND s.etat_id IN (1,2)');
//
//       // Passage à l'état en cours de toutes les sorties dont la date de début
//        $qb =  $em->createQuery(
//            'UPDATE sortie s SET s.etat_id = 4
//                 WHERE s.etat_id = 3
//                 AND now() BETWEEN s.date_heure_debut AND DATE_ADD(s.date_heure_debut, INTERVAL s.duree HOUR)');
//
//        // Passage à terminé quand la date de fin est dépassée
//        $qb =  $em->createQuery(
//            'UPDATE sortie s SET s.etat_id = 7 WHERE s.etat_id = 5 AND now() < DATE_ADD(s.date_heure_debut, INTERVAL 1 MONTH)');

        $sorties = $this->sortieRepository->findAll();
        $etats = $this->etatRepository->findAll();
        $now = new \DateTime();

        foreach ($sorties as $sortie){
            $dateFinClone = clone $sortie->getDateHeureDebut();
            $dateFin = $dateFinClone->add(new \DateInterval('PT'.$sortie->getDuree().'H'));

            $shouldArchive = $now > $dateFin->add(new \DateInterval('P1M'));

            // Clôture des sorties en cours quand la date de find d'inscription est dépassée
            if($sortie->getDateLimiteInscription() < $now && ($sortie->getEtat() === $etats[0] || $sortie->getEtat() === $etats[1])){
                $sortie->setEtat($etats[2]);
                $this->em->persist($sortie);
            }
            // Passage à l'état en cours de toutes les sorties dont la date de début
            if($sortie->getEtat() === $etats[2] && ($sortie->getDateHeureDebut() < $now && $dateFin > $now )) {
                $sortie->setEtat($etats[3]);
                $this->em->persist($sortie);
            }
            // Passage à terminé quand la date de fin est dépassée
            if ($sortie->getEtat() === $etats[3] && $dateFin < $now){
                $sortie->setEtat($etats[4]);
                $this->em->persist($sortie);
            }
            // Archivage au bout d'un mois
            if($sortie->getEtat() === $etats[4] && $shouldArchive){
                $sortie->setEtat($etats[6]);
                $this->em->persist($sortie);
            }
            $this->em->flush();
        }
    }
}