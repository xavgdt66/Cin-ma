<?php 

namespace App\Repository;

use App\Entity\DateDiffusion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DateDiffusion>
 *
 * @method DateDiffusion|null find($id, $lockMode = null, $lockVersion = null)
 * @method DateDiffusion|null findOneBy(array $criteria, array $orderBy = null)
 * @method DateDiffusion[]    findAll()
 * @method DateDiffusion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DateDiffusionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DateDiffusion::class);
    }

    //    /**
    //     * @return Movie[] Returns an array of DateDiffusion objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DateDiffusion
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
