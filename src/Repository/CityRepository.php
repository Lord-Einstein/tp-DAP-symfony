<?php

namespace App\Repository;

use App\Entity\City;
use App\Trait\EntityRepositorySaverTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<City>
 */
class CityRepository extends ServiceEntityRepository
{
    use EntityRepositorySaverTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, City::class);
    }

    public function search(?string $query = null, ?int $limit = 20): array
    {
        $qb = $this->createQueryBuilder('c')
            ->orderBy('c.name', 'ASC')
            // ->andWhere('c.deletedAt IS NULL')
            ->setMaxResults($limit);

        if ($query) {
            $qb->andWhere('LOWER(c.name) LIKE LOWER(:query)')
                ->setParameter('query', '%' . $query . '%');
        }

        //ici on laisse le moteur SQL mettre en lower un élément puis on gère depuis le code PHP le second
        // if ($query) {
        //     $qb->andWhere('LOWER(c.name) LIKE LOWER(:query)')
        // ->setParameter('query', '%' . mb_strtolower($query) . '%');
        // }

        return $qb->getQuery()->getResult();
    }
}
