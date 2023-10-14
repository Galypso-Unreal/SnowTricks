<?php

namespace App\Repository;

use App\Entity\Trick;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @extends ServiceEntityRepository<Picture>
 *
 * @method Trick|null find($id, $lockMode = null, $lockVersion = null)
 * @method Trick|null findOneBy(array $criteria, array $orderBy = null)
 * @method Trick[]    findAll()
 * @method Trick[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrickRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trick::class);
    }

    public function findTenTrick($page){
        $page = $page;
        $limit = 15;
        $start = ($page * $limit) - $limit;
        $query = $this
               ->createQueryBuilder('t')
               ->getQuery();
        $result = $query->setFirstResult($start)->setMaxResults($limit);
        return $result;
    }

    public function getAllTricksCount(){

        $query = $this
               ->createQueryBuilder('t')
               ->select('count(t.id)')
               ->getQuery()
               ->getSingleScalarResult();
        return $query;
    }

    public function findOneBySomeField($value): ?Trick
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.slug = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

//    /**
//     * @return Trick[] Returns an array of Trick objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }


}
