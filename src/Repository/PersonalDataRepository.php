<?php

namespace App\Repository;

use App\Entity\PersonalData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PersonalData|null find($id, $lockMode = null, $lockVersion = null)
 * @method PersonalData|null findOneBy(array $criteria, array $orderBy = null)
 * @method PersonalData[]    findAll()
 * @method PersonalData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonalDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PersonalData::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PersonalData $entity, bool $flush = true): void
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
    public function remove(PersonalData $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return PersonalData[] Returns an array of PersonalData objects
     */
    
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
    

    
    public function findOneBySomeField($value): ?PersonalData
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.user_id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    
}
