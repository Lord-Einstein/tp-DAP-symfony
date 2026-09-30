<?php

namespace App\Service\Utils;

use App\Entity\Impl\AbstractEntity;
use DateTimeImmutable;
use Symfony\Bundle\SecurityBundle\Security;

class AuditService
{
    public function __construct(
        private readonly Security $security,
    ) {
    }

    /**
     * Stamps an entity as created now, by the current user when there is one.
     */
    public function stampCreation(AbstractEntity $entity): void
    {
        $entity->setCreatedBy($this->security->getUser());
        $entity->setCreatedAt(new DateTimeImmutable());
    }

    /**
     * Stamps an entity as updated now, by the current user when there is one.
     */
    public function stampUpdate(AbstractEntity $entity): void
    {
        $entity->setUpdatedBy($this->security->getUser());
        $entity->setUpdatedAt(new DateTimeImmutable());
    }   

    /**
     * Marks an entity as deleted now, by the current user when there is one.
     */
    public function markDeleted(AbstractEntity $entity): void
    {
        $entity->setDeletedBy($this->security->getUser());
        $entity->setDeletedAt(new DateTimeImmutable());
    }
}