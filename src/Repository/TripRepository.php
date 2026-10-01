<?php

namespace App\Repository;

use App\Entity\City;
use App\Entity\Trip;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trip>
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    
    /** @return Trip[] */
    public function search(City $origin, City $destination, \DateTimeImmutable $day): array
    {
        $start = $day->setTime(0, 0, 0, 0);
        $end = $start->modify('+1 day');

        return $this->createQueryBuilder('t')
            ->andWhere('t.deletedAt IS NULL')
            ->andWhere('t.origin = :origin')
            ->andWhere('t.destination = :destination')
            ->andWhere('t.departureAt >= :start')
            ->andWhere('t.departureAt < :end')
            ->setParameter('origin', $origin)
            ->setParameter('destination', $destination)
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('t.departureAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
