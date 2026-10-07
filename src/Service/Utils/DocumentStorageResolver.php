<?php

namespace App\Service\Utils;

use App\Entity\Enum\DocumentType;
use League\Flysystem\FilesystemOperator;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;

class DocumentStorageResolver
{
    public function __construct(
        #[AutowireLocator([
            DocumentType::ProfilePicture->value => new Autowire(service: 'profile_pictures.storage'),
        ])]
        private readonly ContainerInterface $storages,
    ){}

    /**
     * Returns the storage that holds the documents of this type.
     *
     * @throws \LogicException when no storage is configured for this type
    */
    public function resolve(DocumentType $type): FilesystemOperator
    {
        if (!$this->storages->has($type->value)) {
            throw new \LogicException("Aucun stockage n'est configuré pour ce type de document.");
        }
        return $this->storages->get($type->value);
    }
}
