<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Entity;

use PiWeb\PiCRUD\Repository\DocumentRepository;
use PiWeb\PiCRUD\Model\IdTrait;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\ORM\Mapping as ORM;
use App\Model\LabelTrait;
use PiWeb\PiCRUD\Model\AuthorTrait;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: DocumentRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[Vich\Uploadable]
class Document
{
    use IdTrait;
    use LabelTrait;
    use AuthorTrait;

    #[Vich\UploadableField(
        mapping: 'content_file',
        fileNameProperty: 'filename',
    )]
    protected ?File $documentFile = null;

    #[ORM\Column(type: 'string')]
    protected ?string $filename = '';

    public function setDocumentFile(?File $documentFile = null): self
    {
        $this->documentFile = $documentFile;

        return $this;
    }

    public function getDocumentFile(): ?File
    {
        return $this->documentFile;
    }

    public function setFilename(?string $filename): void
    {
        $this->filename = $filename;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }
}
