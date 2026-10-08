<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use ApiPlatform\OpenApi\Model\Parameter;
use ApiPlatform\OpenApi\Model\Response as OpenApiResponse;
use App\Entity\Enum\DocumentType;
use App\Entity\Impl\AbstractEntity;
use App\Repository\DocumentRepository;
use App\State\Document\DocumentDownloadProvider;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[ApiResource(
   operations: [
        new Get(
            uriTemplate: '/documents/{id}/download',
            name: 'document_download',
            provider: DocumentDownloadProvider::class,
            defaults: ['_signed' => true],
            openapi: new OpenApiOperation(
                // aucun jeton : c'est la signature de l'adresse qui protège
                security: [],
                parameters: [
                    new Parameter('expires', 'query', 'Date d\'expiration de l\'adresse (timestamp)', true, schema: ['type' => 'integer']),
                    new Parameter('signature', 'query', 'Signature de l\'adresse', true, schema: ['type' => 'string']),
                ],
                responses: [
                    '200' => new OpenApiResponse(description: 'Le fichier', content: new \ArrayObject([
                        'image/*' => ['schema' => ['type' => 'string', 'format' => 'binary']],
                    ])),
                    '403' => new OpenApiResponse(description: 'Signature absente, invalide ou expirée'),
                    '404' => new OpenApiResponse(description: 'Document introuvable'),
                ],
            ),
        ),
    ],
)]
class Document extends AbstractEntity
{
    #[ORM\Id]
    #[ORM\Column (
        type: UuidType::NAME,
        unique: true,
    )]
    private Uuid $id;

    #[ORM\Column(enumType: DocumentType::class)]
    private DocumentType $type;

    #[ORM\Column(length: 255)]
    private string $storageKey;

    #[ORM\Column(length: 255)]
    private string $originalName;

    #[ORM\Column(length: 255)]
    private string $mimeType;

    #[ORM\Column(type: Types::INTEGER)]
    private int $size;

    public function __construct()
    {
        $this->id = Uuid::v7();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getType(): DocumentType
    {
        return $this->type;
    }

    public function setType(DocumentType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStorageKey(): string
    {
        return $this->storageKey;
    }

    public function setStorageKey(string $storageKey): static
    {
        $this->storageKey = $storageKey;

        return $this;
    }

    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    public function setOriginalName(string $originalName): static
    {
        $this->originalName = $originalName;

        return $this;
    }

    public function getMimeType(): string
    {
        return $this->mimeType;
    }

    public function setMimeType(string $mimeType): static
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function setSize(int $size): static
    {
        $this->size = $size;

        return $this;
    }
}
