<?php

namespace App\Repository;

use App\Entity\ReferralNetwork;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReferralNetwork|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReferralNetwork|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReferralNetwork[]    findAll()
 * @method ReferralNetwork[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferralNetworkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReferralNetwork::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ReferralNetwork $entity, bool $flush = true): void
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
    public function remove(ReferralNetwork $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return ReferralNetwork[] Returns an array of ReferralNetwork objects
     */
    
    public function findByExampleField()
    {
        return $this->createQueryBuilder('r')
            //->andWhere('r.exampleField = :val')
            //->setParameter('val', $value)
            ->orderBy ('r.id', 'DESC')
            ->setMaxResults(2)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return ReferralNetwork[] Returns an array of ReferralNetwork objects
     */
    
    public function findByIdLastField()
    {
        return $this->createQueryBuilder('r')
            //->andWhere('r.exampleField = :val')
            //->setParameter('val', $value)
            ->orderBy ('r.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return ReferralNetwork[] Returns an array of ReferralNetwork objects
     */
    
    public function findByIdFirstField()
    {
        return $this->createQueryBuilder('r')
            //->andWhere('r.exampleField = :val')
            //->setParameter('val', $value)
            ->orderBy ('r.id', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }
    
    /**
     * @return ReferralNetwork[] Returns an array of ReferralNetwork objects
     */
    
    public function findByLeftField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user_status = :val')
            ->setParameter('val', $value)
            ->orderBy ('r.id', 'DESC')
            ->setMaxResults(10000)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return ReferralNetwork[] Returns an array of ReferralNetwork objects
     */
    
    public function findByRightField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user_status = :val')
            ->setParameter('val', $value)
            ->orderBy ('r.id', 'ASC')
            ->setMaxResults(10000)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByBalanceField($value, $balance)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user_status = :val', 'r.balance > :balance')
            ->setParameter('val', $value)
            ->setParameter('balance', $balance)
            ->orderBy ('r.id', 'ASC')
            ->setMaxResults(10000)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByBalanceToPointField($value,$balance,$id)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user_status = :val', 'r.balance > :balance', 'r.id < :id')
            //->where('r.id = ?40')
            //->where('r.id = ?42')
            ->setParameter('val', $value)
            ->setParameter('balance', $balance)
            ->orderBy ('r.id', 'ASC')
            ->setParameter('id', $id) 
            //->setFirstResult( 40 )
            ->setMaxResults(1000)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByBalanceFromPointField($value,$balance,$id)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user_status = :val', 'r.balance > :balance', 'r.id > :id')
            //->where('r.id = ?40')
            //->where('r.id = ?42')
            ->setParameter('val', $value)
            ->setParameter('balance', $balance)
            ->orderBy ('r.id', 'ASC')
            ->setParameter('id', $id) 
            //->setFirstResult( 40 )
            ->setMaxResults(1000)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?ReferralNetwork
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.user_status = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return ReferralNetwork[] Returns an array of ReferralNetwork objects
     */
    
    public function findByCountField()
    {

        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT COUNT (r.id)
            FROM App\Entity\ReferralNetwork r'
        )->getSingleScalarResult();

        // returns an array of Product objects
        return $query;
    }
    
}
