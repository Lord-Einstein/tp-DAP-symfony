<?php

namespace App\Service;

use App\Entity\Document;
use App\Entity\Enum\DocumentType;
use App\Exception\Document\DocumentNotFoundException;
use App\Repository\DocumentRepository;
use App\Service\Utils\AuditService;
use App\Service\Utils\DocumentStorageResolver;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\MimeTypes;
use Symfony\Component\Uid\Uuid;
use CoopTilleuls\UrlSignerBundle\UrlSigner\UrlSignerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class DocumentService
{
    public function __construct(
        private readonly DocumentStorageResolver $storageResolver,
        private readonly AuditService $auditService,
        private readonly DocumentRepository $documentRepository,
        private readonly UrlSignerInterface $urlSigner,
        private readonly UrlGeneratorInterface $urlGenerator,
    ){}

    /**
     * Writes the uploaded file to the storage of its type, and builds its document,
     * persisted but not flushed.
    */
    public function store(UploadedFile $file, DocumentType $type): Document
    {
        // 1. Type MIME et extraction de la première extension correspondante
        $mimeType = $file->getMimeType();
        $extensions = MimeTypes::getDefault()->getExtensions($mimeType);
        $extension = $extensions[0] ?? 'bin';

        // 2. Tronquer le nom d'origine à 255 caractères
        $originalName = mb_substr($file->getClientOriginalName(), 0, 255);

        // 3. Instanciation du Document
        $document = new Document()
            ->setOriginalName($originalName)
            ->setType($type)
            ->setMimeType($mimeType)
            ->setSize($file->getSize());

        // 4. Construction de la storageKey ({id}.{extension})
        $storageKey = sprintf('%s.%s', $document->getId(), $extension);
        $document->setStorageKey($storageKey);

        // 5. Résolution du stockage et écriture via un flux (stream)
        $storage = $this->storageResolver->resolve($type);
        $stream = fopen($file->getPathname(), 'rb');
        
        $storage->writeStream($storageKey, $stream);

        if (is_resource($stream)) {
            fclose($stream);
        }

        // 6. Audit, persistance sans flush
        $this->auditService->stampCreation($document);
        $this->documentRepository->persist($document);

        return $document;
    }

    /**
     * Marks this document as deleted. Its file stays on the storage.
    */
    public function softDelete(Document $document): void
    {
        $this->auditService->markDeleted($document);
    }

    /**
     * Returns the document carrying this identifier.
     *
     * @throws DocumentNotFoundException when no live document carries this identifier
    */
    public function findOneById(Uuid $id): Document
    {
        $document = $this->documentRepository->find($id);

        if($document === null) {
            throw new DocumentNotFoundException();
        }

        return $document;
    }

    /**
     * Opens a read stream on the stored file of this document.
     *
     * @return resource
     *
     * @throws DocumentNotFoundException when the file is missing from its storage
    */
    public function openStream(Document $document)
    {
        $storage = $this->storageResolver->resolve($document->getType());
        
        if ($storage->fileExists($document->getStorageKey()) === false) {
            throw new DocumentNotFoundException();
        }
            
        $stream = $storage->readStream($document->getStorageKey());
        return $stream;
    }

    /**
    * Builds the absolute, signed and expiring download URL of this document.
    */
    public function toSignedUrl(Document $document): string
    {
        $url = $this->urlGenerator->generate(
                'document_download', 
                ['id' => $document->getId(),], 
                UrlGeneratorInterface::ABSOLUTE_URL
        );

        /** @disregard */
        return $this->urlSigner->sign($url, null);
    }
    
}
