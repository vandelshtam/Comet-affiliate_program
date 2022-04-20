<?php

namespace App\Repository;

use App\Entity\Pakege;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pakege|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pakege|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pakege[]    findAll()
 * @method Pakege[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PakegeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pakege::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Pakege $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Pakege $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

     /**
      * @return Pakege[] Returns an array of Pakege objects
      */
    
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.referral_networks_id = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10000)
            ->getQuery()
            ->getResult()
        ;
    }
    

     /**
      * @return Pakege[] Returns an array of Pakege objects
      */
    
      public function findByExampleClientField($value)
      {
          return $this->createQueryBuilder('p')
              ->andWhere('p.client_code = :val')
              ->setParameter('val', $value)
              ->orderBy('p.id', 'ASC')
              ->setMaxResults(10000)
              ->getQuery()
              ->getResult()
          ;
      }
      

    
    public function findOneBySomeField($value): ?Pakege
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.referral_networks_id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findByPakageActionField($name_multi_pakage, $user_id, $multi_pakage_day)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.name = :val','r.user_id = :uid', 'r.created_at < :day')
            ->setParameter('val', $name_multi_pakage)
            ->setParameter('day', $multi_pakage_day)
            ->setParameter('uid', $user_id)
            ->orderBy ('r.id', 'ASC')
            ->setMaxResults(10000)
            ->getQuery()
            ->getResult()
        ;
    }
    
}
