<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 *
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function findByLimitComment($page, $value)
    {
        $page = $page;
        $limit = 10;
        $start = ($page * $limit) - $limit;
        $query = $this
            ->createQueryBuilder('c')
            ->select('c.id, c.content, c.is_valid, c.created_at,u.username, u.picture')
            ->innerJoin('c.user', 'u')
            ->andWhere('c.trick = :val')
            ->setParameter('val', $value)
            ->orderBy('c.created_at', 'DESC')
            ->getQuery();
        $result = $query->setFirstResult($start)->setMaxResults($limit);
        return $result;
    }



    public function getAllCommentCount($value)
    {

        $query = $this
            ->createQueryBuilder('c')
            ->select('count(c.id)')
            ->andWhere('c.trick = :val')
            ->setParameter('val', $value)
            ->orderBy('c.created_at', 'DESC')
            ->getQuery()
            ->getSingleScalarResult();
        return $query;
    }
}
