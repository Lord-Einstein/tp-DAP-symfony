<?php

namespace App\Repository;

use App\Entity\Ticket;
use App\Entity\User;
use App\Trait\EntityRepositorySaverTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ticket>
 */
class TicketRepository extends ServiceEntityRepository
{
    use EntityRepositorySaverTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    /**
     * Returns the tickets issued by the user.
     * @return Ticket[]
     */
    public function findFor(User $user): array
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.createdBy = :user')
            ->orderBy('t.createdAt', 'desc')
            ->setParameter('user', $user)
            ->getQuery()
            ->getResult();
    }

}
