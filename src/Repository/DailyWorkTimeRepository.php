<?php

namespace App\Repository;

use App\Entity\DailyWorkTime;
use App\Entity\Employee;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DailyWorkTime>
 */
class DailyWorkTimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DailyWorkTime::class);
    }

    public function findByEmployeeAndDateRange(
        Employee $employee,
        DateTimeInterface $dateFrom,
        DateTimeInterface $dateTo
    ): array {
        return $this->createQueryBuilder('d')
            ->andWhere('d.employee = :employee')
            ->andWhere('d.date >= :dateFrom')
            ->andWhere('d.date <= :dateTo')
            ->setParameter('employee', $employee)
            ->setParameter('dateFrom', $dateFrom)
            ->setParameter('dateTo', $dateTo)
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return DailyWorkTime[] Returns an array of DailyWorkTime objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?DailyWorkTime
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
