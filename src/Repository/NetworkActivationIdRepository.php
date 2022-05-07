<?php

namespace App\Repository;

use App\Entity\NetworkActivationId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NetworkActivationId|null find($id, $lockMode = null, $lockVersion = null)
 * @method NetworkActivationId|null findOneBy(array $criteria, array $orderBy = null)
 * @method NetworkActivationId[]    findAll()
 * @method NetworkActivationId[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NetworkActivationIdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NetworkActivationId::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(NetworkActivationId $entity, bool $flush = true): void
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
    public function remove(NetworkActivationId $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return NetworkActivationId[] Returns an array of NetworkActivationId objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NetworkActivationId
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
