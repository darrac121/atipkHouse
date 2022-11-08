<?php

namespace App\Repository;

use App\Entity\LebelleOptionAnnonce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LebelleOptionAnnonce>
 *
 * @method LebelleOptionAnnonce|null find($id, $lockMode = null, $lockVersion = null)
 * @method LebelleOptionAnnonce|null findOneBy(array $criteria, array $orderBy = null)
 * @method LebelleOptionAnnonce[]    findAll()
 * @method LebelleOptionAnnonce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LebelleOptionAnnonceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LebelleOptionAnnonce::class);
    }

    public function save(LebelleOptionAnnonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(LebelleOptionAnnonce $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return LebelleOptionAnnonce[] Returns an array of LebelleOptionAnnonce objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LebelleOptionAnnonce
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}