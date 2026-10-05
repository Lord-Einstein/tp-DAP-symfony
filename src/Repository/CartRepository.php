<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\Enum\CartStatus;
use App\Entity\User;
use App\Trait\EntityRepositorySaverTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cart>
 */
class CartRepository extends ServiceEntityRepository
{
    use EntityRepositorySaverTrait;
    
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function findActiveFor(User $user): ?Cart
    {
        return $this->createQueryBuilder('c')
            ->andwhere("c.status = :status")
            ->andWhere('c.createdBy = :user')
            ->setParameter('user', $user)
            ->setParameter('status', CartStatus::Pending)
            ->getQuery()
            ->getOneOrNullResult();
    }
  
}
