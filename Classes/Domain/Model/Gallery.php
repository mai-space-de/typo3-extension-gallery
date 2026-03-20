<?php

declare(strict_types=1);

namespace MaiSpace\Gallery\Domain\Model;

use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Gallery extends AbstractEntity
{
    protected string $title = '';

    protected ?\DateTimeInterface $date = null;

    protected string $projectReference = '';

    protected ?GalleryCategory $category = null;

    protected string $description = '';

    protected string $slug = '';

    /**
     * @var ObjectStorage<FileReference>
     */
    #[Lazy]
    protected ObjectStorage $media;

    public function __construct()
    {
        $this->media = new ObjectStorage();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): void
    {
        $this->date = $date;
    }

    public function getYear(): ?int
    {
        if ($this->date === null) {
            return null;
        }
        return (int)$this->date->format('Y');
    }

    public function getProjectReference(): string
    {
        return $this->projectReference;
    }

    public function setProjectReference(string $projectReference): void
    {
        $this->projectReference = $projectReference;
    }

    public function getCategory(): ?GalleryCategory
    {
        return $this->category;
    }

    public function setCategory(?GalleryCategory $category): void
    {
        $this->category = $category;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    /**
     * @return ObjectStorage<FileReference>
     */
    public function getMedia(): ObjectStorage
    {
        return $this->media;
    }

    /**
     * @param ObjectStorage<FileReference> $media
     */
    public function setMedia(ObjectStorage $media): void
    {
        $this->media = $media;
    }

    public function addMedia(FileReference $fileReference): void
    {
        $this->media->attach($fileReference);
    }

    public function removeMedia(FileReference $fileReference): void
    {
        $this->media->detach($fileReference);
    }

    public function getFirstMedia(): ?FileReference
    {
        $this->media->rewind();
        return $this->media->current() ?: null;
    }
}
