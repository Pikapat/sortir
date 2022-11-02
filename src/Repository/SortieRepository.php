<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function findByFilters(
        int $campusId,
        string $textfilter = null,
        \DateTime $dateDebut = null,
        \DateTime $dateFin = null,
        bool $userOrga,
        bool $userInscrit,
        bool $userNonInscrit,
        bool $sortiePassee,
        User $currentUser)
    {
        //Query Builder
        $qb = $this->createQueryBuilder('s');

        $qb->andWhere("s.siteOrganisateur = :campusId")
            ->setParameter('campusId', $campusId);

        if ($textfilter != null)
        {
            $qb->andWhere("s.titre LIKE %:textfilter%")
                ->setParameter('textfilter', $textfilter);
        }

        if ($dateDebut != null && $dateFin != null)
        {
            $qb->andWhere("s.dateHeureDebut BETWEEN :dateDebut AND :dateFin")
                ->setParameter('dateDebut', $dateDebut)
                ->setParameter('dateFin', $dateFin);
        }

        if ($userOrga)
        {
            $qb->andWhere("s.organisateur = :orga")
                ->setParameter('orga', $currentUser->getId());
        }

        if($userInscrit)
        {
            $qb->innerJoin('User', 'u')
                ->where('s.usersInscrits LIKE %u.id%');
        }

        if($userNonInscrit)
        {
        }

        if($sortiePassee)
        {
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
