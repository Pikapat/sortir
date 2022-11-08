<?php

namespace App\Repository;

use App\Entity\Sortie;

use App\Form\Model\SortieFilters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\AST\Functions\DateAddFunction;
use Doctrine\ORM\Query\Expr\Join;
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

    public function findSortiesWithEtats()
    {
        //Query Builder
        return $this
            ->createQueryBuilder('s')
            ->join('s.etat','e')
            ->addSelect('e')
            ->getQuery()
            ->getResult();
    }

    public function findByFilters(SortieFilters $filters, UserInterface $user)
    {

        $campus = ($filters->getCampus()) ? $filters->getCampus() : $user->getCampus();
        $textfilter = $filters->getTextFilter();
        $dateDebutSortie = $filters->getDateDebut();
        $dateFinSortie = $filters->getDateFin();
        $userOrga = $filters->getUserOrga();
        $userInscrit = $filters->getUserInscrit();
        $userNonInscrit = $filters->getUserNonInscrit();
        $sortiePassee = $filters->getSortiePassee();


        //Query Builder
        $qb = $this->createQueryBuilder('s')
            ->join('s.etat', 'e')
            ->addSelect('e')
            ->andWhere("e.code IN('PUB','CLO','ENC','TER','ANN')")
            ->join('s.organisateur', 'u')
            ->addSelect('u')
            ->andWhere('s.organisateur = u.id')
            ->leftJoin('s.usersInscrits', 'i')
            ->addSelect('i')
            ->join('s.siteOrganisateur', 'c')
            ->addSelect('c')
            ->andWhere('s.siteOrganisateur = :campus')
            ->setParameter('campus', $campus);

        if ($textfilter) {
            $qb->andWhere("s.titre LIKE :textfilter")
                ->setParameter('textfilter', '%' . $textfilter . '%');
        }

        if ($dateDebutSortie) {
            $qb->andWhere("s.dateHeureDebut >= :dateDebut")
                ->setParameter('dateDebut', $dateDebutSortie);
        }

        if ($dateFinSortie){
            $qb->andWhere("DATE_ADD(s.dateHeureDebut, s.duree, 'HOUR') <= :dateFin")
            ->setParameter('dateFin', $dateFinSortie);
        }

        if ($userOrga) {
            $qb->andWhere("s.organisateur = :orga")
                ->setParameter('orga', $user);
        }

        if ($userInscrit) {
            $qb->andWhere(':user member of s.usersInscrits')
                ->setParameter('user', $user);
        }

        if ($userNonInscrit) {
            $qb->andWhere(':user not member of s.usersInscrits')
                ->setParameter('user', $user);
        }

        if ($sortiePassee) {
            $qb->andWhere("e.code = 'TER'");
        }
        else {
            $qb->andWhere("e.code IN('PUB','CLO','ENC','TER')");
        }

        return $qb->getQuery()->getResult();
    }

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
