<?php

namespace App\Repository;

use App\Entity\RecoverHash;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method RecoverHash|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecoverHash|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecoverHash[]    findAll()
 * @method RecoverHash[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecoverHashRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecoverHash::class);
    }

    // /**
    //  * @return RecoverHash[] Returns an array of RecoverHash objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?RecoverHash
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
