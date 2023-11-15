<?php

namespace App\Repository;

use App\Entity\ContactExtendedFields;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ContactExtendedFields>
 *
 * @method ContactExtendedFields|null find($id, $lockMode = null, $lockVersion = null)
 * @method ContactExtendedFields|null findOneBy(array $criteria, array $orderBy = null)
 * @method ContactExtendedFields[]    findAll()
 * @method ContactExtendedFields[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactExtendedFieldsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ContactExtendedFields::class);
    }

//    /**
//     * @return ContactExtendedFields[] Returns an array of ContactExtendedFields objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ContactExtendedFields
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
