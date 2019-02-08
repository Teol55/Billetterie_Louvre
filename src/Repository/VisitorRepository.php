<?php

namespace App\Repository;

use App\Entity\Visitor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Visitor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visitor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visitor[]    findAll()
 * @method Visitor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisitorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Visitor::class);
    }
    public function countByDateVisit($date)
    {
        return $this->createQueryBuilder('v')
            ->innerJoin('v.ticket','t')
            ->select('COUNT(v.id)')
            ->andWhere('t.dateVisit>= :date_start')
            ->andWhere('t.dateVisit <= :date_end')
            ->setParameter('date_start', $date->format('Y-m-d 00:00:00'))
            ->setParameter('date_end',   $date->format('Y-m-d 23:59:59'))
            ->getQuery()
            ->getSingleScalarResult();

    }
    // /**
    //  * @return Visitor[] Returns an array of Visitor objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Visitor
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
