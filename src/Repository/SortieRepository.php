<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Entity\User;
use App\Form\Model\SortieFilters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\AST\Functions\DateAddFunction;
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

    public function findByFilters(SortieFilters $filters, UserInterface $user)
    {

        $campus = $filters->getCampus();
        $textfilter = $filters->getTextFilter();
        $dateDebut = $filters->getDateDebut();
        $dateLimiteinscription = $filters->getDateFin();
        $userOrga = $filters->getUserOrga();
        $userInscrit = $filters->getUserInscrit();
        $userNonInscrit = $filters->getUserNonInscrit();
        $sortiePassee = $filters->getSortiePassee();
        $now = new \DateTime();


        //Query Builder
        $qb = $this->createQueryBuilder('s');

        $qb->andWhere("s.siteOrganisateur = :campusId")
            ->setParameter('campusId', $campus);

//        if($sortiePassee) {
//
//            $qb->addSelect('e')
//                ->from('etat', 'e')
//                ->innerJoin('e.id', 's')
//                ->andWhere('s.etat = 3');
//        }
//        else{
//            $qb->andWhere('s.etat = publié');
//        }

        if ($textfilter != null) {
            $qb->andWhere("s.titre LIKE :textfilter")
                ->setParameter('textfilter', '%'.$textfilter.'%');
        }

        if ($dateDebut != null && $dateLimiteinscription != null) {
            $qb->andWhere("s.dateHeureDebut BETWEEN :dateDebut AND :dateFin")
                ->setParameter('dateDebut', $dateDebut)
                ->setParameter('dateFin', $dateLimiteinscription);
        }

        if ($userOrga) {
            $qb->andWhere("s.organisateur = :orga")
                ->setParameter('orga', $user);
        }

        if($userInscrit) {
            $qb->andWhere(':user member of s.usersInscrits')
            ->setParameter('user', $user);
        }

        if($userNonInscrit) {
            $qb->andWhere(':user not member of s.usersInscrits')
                ->setParameter('user', $user);
        }


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
