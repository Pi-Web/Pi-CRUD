<?php

declare(strict_types=1);

namespace PiWeb\PiCRUD\Model\StructuredData;

use DateTime;
use PiWeb\PiCRUD\Enum\StructuredData\TypeEnum;

final class NewsArticle extends AbstractStructuredData
{
    protected string $headline;
    private ?DateTime $datePublished;
    private ?DateTime $dateModified;
    private string $image;
    private Person $author;
    private Person $publisher;

    public function __construct()
    {
        $this->type = TypeEnum::NEWS_ARTICLE;
    }

    public function getHeadline(): string
    {
        return $this->headline;
    }

    public function setHeadline(string $headline): self
    {
        $this->headline = $headline;

        return $this;
    }

    public function getDatePublished(): ?DateTime
    {
        return $this->datePublished;
    }

    public function setDatePublished(?DateTime $datePublished): self
    {
        $this->datePublished = $datePublished;

        return $this;
    }

    public function getDateModified(): ?DateTime
    {
        return $this->dateModified;
    }

    public function setDateModified(?DateTime $dateModified): self
    {
        $this->dateModified = $dateModified;

        return $this;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getAuthor(): Person
    {
        return $this->author;
    }

    public function setAuthor(Person $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getPublisher(): Person
    {
        return $this->publisher;
    }

    public function setPublisher(Person $publisher): self
    {
        $this->publisher = $publisher;

        return $this;
    }
}
