<?php

namespace App\Repository;

use App\Entity\GenreSerie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method GenreSerie|null find($id, $lockMode = null, $lockVersion = null)
 * @method GenreSerie|null findOneBy(array $criteria, array $orderBy = null)
 * @method GenreSerie[]    findAll()
 * @method GenreSerie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenreSerieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GenreSerie::class);
    }

    // /**
    //  * @return GenreSerie[] Returns an array of GenreSerie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GenreSerie
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
