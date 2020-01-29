<?php

namespace App\Repository;

use App\Entity\Movie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @method Movie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Movie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Movie[]    findAll()
 * @method Movie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MovieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Movie::class);
    }

    // /**
    //  * @return Movie[] Returns an array of Movie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /**
     * Get paginated movies without criteria
     */
    public function getMoviesPagination(PaginatorInterface $paginator, int $page) {
        //prepare the request
        $query = $this->createQueryBuilder('m')->getQuery();
        $pagination =$paginator->paginate($query, $page, 30);

        return $pagination;

    }

    public function getMoviesByGenre(PaginatorInterface $paginator, int $id, int $page)
    {
        $qb = $this->createQueryBuilder('movie');
        $qb->join('movie.genres', 'genre')
            ->where($qb->expr()->eq('genre.id', $id));
        $query = $qb->getQuery();
        $pagination =$paginator->paginate($query, $page, 30);
        return $pagination;
    }

    /*
    public function findOneBySomeField($value): ?Movie
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
