<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function save(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByFilters(Request $request, UserInterface $user)
    {
        $form = $request->get('sortie_filters_form');

        $campusId = $form['campus'];
        $textfilter = $form['textFilter'];

//        $textfilter = $request->get('textFilter');
//        $dateDebut = $request->get('dateDebut');
//        $dateFin = $request->get('dateFin');
//        $userOrga = $request->get('userOrga');
//        $userInscrit = $request->get('userIncrit');
//        $userNonInscrit = $request->get('userNonInscrit');
//        $sortiePassee = $request->get('sortiePassee');


//        $dateDebut =  $form['dateDebut'];
//        $dateFin =  $form['dateFin'];
//        $userOrga = $form['userOrga'];
//        $userInscrit = $form['userOrga'];
//        $userNonInscrit = $form['userNonInscrit'];
//        $sortiePassee = $form['sortiePassee'];

//        dump($campusId);


        //Query Builder
        $qb = $this->createQueryBuilder('s');

        $qb->andWhere("s.siteOrganisateur = :campusId")
            ->setParameter('campusId', $campusId);

        if ($textfilter != null)
        {
//            $qb->andWhere($qb->expr()->like('s.titre', $textfilter))->orderBy('s.titre', 'ASC');

//                "s.titre LIKE %:textfilter%")
//                ->setParameter('textfilter', $textfilter)
//                ->groupBy('s.titre', 'DESC');
        }
//
//        if ($dateDebut != null && $dateFin != null)
//        {
//            $qb->andWhere("s.dateHeureDebut BETWEEN :dateDebut AND :dateFin")
//                ->setParameter('dateDebut', $dateDebut)
//                ->setParameter('dateFin', $dateFin);
//        }
//
//        if ($userOrga)
//        {
//            $qb->andWhere("s.organisateur = :orga")
//                ->setParameter('orga', $user);
//        }
//
//        if($userInscrit)
//        {
//            $qb->innerJoin('User', 'u')
//                ->where('s.usersInscrits LIKE %u.id%');
//        }
//
//        if($userNonInscrit)
//        {
//        }
//
//        if($sortiePassee)
//        {
//        }
//
        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
