<?php

namespace App\Repository;

use App\Entity\TransactionTable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TransactionTable|null find($id, $lockMode = null, $lockVersion = null)
 * @method TransactionTable|null findOneBy(array $criteria, array $orderBy = null)
 * @method TransactionTable[]    findAll()
 * @method TransactionTable[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionTableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TransactionTable::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(TransactionTable $entity, bool $flush = true): void
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
    public function remove(TransactionTable $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    public function findByAddWalletField($usdt, $bitcoin)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.type >= :usdt', 't.type <= :bitcoin')
            ->setParameter('usdt', $usdt)
            ->setParameter('bitcoin', $bitcoin)
            
            ->orderBy ('t.id', 'ASC')
            ->setMaxResults(1000000)
            ->getQuery()
            ->getResult()
        ;
    }


    public function findByWithdrawalWalletField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.type = :val')
            ->setParameter('val', $value)
            ->orderBy ('t.id', 'ASC')
            ->setMaxResults(1000000)
            ->getQuery()
            ->getResult()
        ;
    }


    // /**
    //  * @return TransactionTable[] Returns an array of TransactionTable objects
    //  */
    
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.type = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10000)
            ->getQuery()
            ->getResult()
        ;
    }


    public function findByUserField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.user_id = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10000)
            ->getQuery()
            ->getResult()
        ;
    }


    public function findByNetworkField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.network_id = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10000)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByWalletField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.wallet_id = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10000)
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?TransactionTable
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
