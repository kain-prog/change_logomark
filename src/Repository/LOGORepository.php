<?php

namespace App\Repository;

use App\Entity\LOGO;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LOGO>
 *
 * @method LOGO|null find($id, $lockMode = null, $lockVersion = null)
 * @method LOGO|null findOneBy(array $criteria, array $orderBy = null)
 * @method LOGO[]    findAll()
 * @method LOGO[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LOGORepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LOGO::class);
    }

//    /**
//     * @return LOGO[] Returns an array of LOGO objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LOGO
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
