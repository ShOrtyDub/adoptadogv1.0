<?php

namespace App\Repository;

use App\Entity\Correspondance;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Correspondance>
 *
 * @method Correspondance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Correspondance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Correspondance[]    findAll()
 * @method Correspondance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CorrespondanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Correspondance::class);
    }

    public function deleteOldCorrespondence(Utilisateur $utilisateur): void
    {
        $this->createQueryBuilder('c')
            ->delete()
            ->where('c.fk_id_utilisateur = :utilisateur')
            ->setParameter('utilisateur', $utilisateur)
            ->getQuery()
            ->execute();
    }

//    /**
//     * @return Correspondance[] Returns an array of Correspondance objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Correspondance
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
