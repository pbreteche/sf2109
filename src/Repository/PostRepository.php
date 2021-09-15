<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @return Post[]
     */
    public function findByMonth(\DateTimeImmutable $month)
    {
        $start = $month->modify('first day of this month midnight');
        $end = $start->modify('+1 month');

        return $this->getEntityManager()->createQuery(
            'SELECT post FROM '.Post::class.' post'.
            ' WHERE post.createdAt >= :start'.
            ' AND post.createdAt < :end'.
            ' ORDER BY post.createdAt DESC'
        )->setParameters([
            'start' => $start,
            'end' => $end,
        ])->getResult();
    }

    /**
     * @return Post[]
     */
    public function findByMonth2(\DateTimeImmutable $month)
    {
        $start = $month->modify('first day of this month midnight');
        $end = $start->modify('+1 month');

        return $this->createQueryBuilder('post')
            ->andWhere('post.createdAt >= :start')
            ->andWhere('post.createdAt < :end')
            ->orderBy('post.createdAt', 'DESC')
            ->getQuery()
            ->setParameters([
            'start' => $start,
            'end' => $end,
        ])->getResult();
    }
    // /**
    //  * @return Post[] Returns an array of Post objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Post
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
