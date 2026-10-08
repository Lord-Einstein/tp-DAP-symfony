<?php

namespace App\Service;

use App\Entity\Document;
use App\Entity\Enum\DocumentType;
use App\Repository\DocumentRepository;
use App\Service\Utils\AuditService;
use App\Service\Utils\DocumentStorageResolver;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\MimeTypes;

class DocumentService
{
    public function __construct(
        private readonly DocumentStorageResolver $storageResolver,
        private readonly AuditService $auditService,
        private readonly DocumentRepository $documentRepository,
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
    
}
