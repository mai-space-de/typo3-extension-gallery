<?php

declare(strict_types=1);

namespace Maispace\MaiGallery\Tests\Unit\Domain\Model;

use Maispace\MaiGallery\Domain\Model\Gallery;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

final class GalleryTest extends TestCase
{
    // ── Default values ──────────────────────────────────────────────────────

    #[Test]
    public function defaultTitleIsEmptyString(): void
    {
        $gallery = new Gallery();
        self::assertSame('', $gallery->getTitle());
    }

    #[Test]
    public function defaultDescriptionIsEmptyString(): void
    {
        $gallery = new Gallery();
        self::assertSame('', $gallery->getDescription());
    }

    #[Test]
    public function defaultYearIsZero(): void
    {
        $gallery = new Gallery();
        self::assertSame(0, $gallery->getYear());
    }

    #[Test]
    public function constructorInitializesImagesAsObjectStorage(): void
    {
        $gallery = new Gallery();
        self::assertInstanceOf(ObjectStorage::class, $gallery->getImages());
    }

    #[Test]
    public function constructorInitializesCategoriesAsObjectStorage(): void
    {
        $gallery = new Gallery();
        self::assertInstanceOf(ObjectStorage::class, $gallery->getCategories());
    }

    #[Test]
    public function constructorCreatesEmptyImagesStorage(): void
    {
        $gallery = new Gallery();
        self::assertCount(0, $gallery->getImages());
    }

    #[Test]
    public function constructorCreatesEmptyCategoriesStorage(): void
    {
        $gallery = new Gallery();
        self::assertCount(0, $gallery->getCategories());
    }

    // ── initializeObject ────────────────────────────────────────────────────

    #[Test]
    public function initializeObjectCreatesFreshImagesStorage(): void
    {
        $gallery = new Gallery();
        $original = $gallery->getImages();
        $gallery->initializeObject();
        self::assertInstanceOf(ObjectStorage::class, $gallery->getImages());
        self::assertNotSame($original, $gallery->getImages());
    }

    #[Test]
    public function initializeObjectCreatesFreshCategoriesStorage(): void
    {
        $gallery = new Gallery();
        $original = $gallery->getCategories();
        $gallery->initializeObject();
        self::assertInstanceOf(ObjectStorage::class, $gallery->getCategories());
        self::assertNotSame($original, $gallery->getCategories());
    }

    #[Test]
    public function imagesStorageIsEmptyAfterInitializeObject(): void
    {
        $gallery = new Gallery();
        $gallery->initializeObject();
        self::assertCount(0, $gallery->getImages());
    }

    #[Test]
    public function categoriesStorageIsEmptyAfterInitializeObject(): void
    {
        $gallery = new Gallery();
        $gallery->initializeObject();
        self::assertCount(0, $gallery->getCategories());
    }

    // ── title getter / setter ───────────────────────────────────────────────

    #[Test]
    public function setTitleStoresTheValue(): void
    {
        $gallery = new Gallery();
        $gallery->setTitle('Summer 2023');
        self::assertSame('Summer 2023', $gallery->getTitle());
    }

    #[Test]
    public function setTitleOverwritesPreviousValue(): void
    {
        $gallery = new Gallery();
        $gallery->setTitle('First title');
        $gallery->setTitle('Second title');
        self::assertSame('Second title', $gallery->getTitle());
    }

    #[Test]
    public function setTitleAcceptsEmptyString(): void
    {
        $gallery = new Gallery();
        $gallery->setTitle('Non-empty');
        $gallery->setTitle('');
        self::assertSame('', $gallery->getTitle());
    }

    // ── description getter / setter ─────────────────────────────────────────

    #[Test]
    public function setDescriptionStoresTheValue(): void
    {
        $gallery = new Gallery();
        $gallery->setDescription('A collection of summer photos.');
        self::assertSame('A collection of summer photos.', $gallery->getDescription());
    }

    #[Test]
    public function setDescriptionOverwritesPreviousValue(): void
    {
        $gallery = new Gallery();
        $gallery->setDescription('First description');
        $gallery->setDescription('Second description');
        self::assertSame('Second description', $gallery->getDescription());
    }

    #[Test]
    public function setDescriptionAcceptsEmptyString(): void
    {
        $gallery = new Gallery();
        $gallery->setDescription('Non-empty');
        $gallery->setDescription('');
        self::assertSame('', $gallery->getDescription());
    }

    // ── year getter / setter ────────────────────────────────────────────────

    #[Test]
    public function setYearStoresTheValue(): void
    {
        $gallery = new Gallery();
        $gallery->setYear(2023);
        self::assertSame(2023, $gallery->getYear());
    }

    #[Test]
    public function setYearOverwritesPreviousValue(): void
    {
        $gallery = new Gallery();
        $gallery->setYear(2022);
        $gallery->setYear(2023);
        self::assertSame(2023, $gallery->getYear());
    }

    #[Test]
    public function setYearAcceptsZero(): void
    {
        $gallery = new Gallery();
        $gallery->setYear(2023);
        $gallery->setYear(0);
        self::assertSame(0, $gallery->getYear());
    }

    // ── images getter / setter ──────────────────────────────────────────────

    #[Test]
    public function setImagesStoresTheObjectStorage(): void
    {
        $gallery = new Gallery();
        $storage = new ObjectStorage();
        $gallery->setImages($storage);
        self::assertSame($storage, $gallery->getImages());
    }

    #[Test]
    public function twoGalleryInstancesHaveIndependentImageStorages(): void
    {
        $gallery1 = new Gallery();
        $gallery2 = new Gallery();
        self::assertNotSame($gallery1->getImages(), $gallery2->getImages());
    }

    // ── categories getter / setter ──────────────────────────────────────────

    #[Test]
    public function setCategoriesStoresTheObjectStorage(): void
    {
        $gallery = new Gallery();
        $storage = new ObjectStorage();
        $gallery->setCategories($storage);
        self::assertSame($storage, $gallery->getCategories());
    }

    #[Test]
    public function twoGalleryInstancesHaveIndependentCategoryStorages(): void
    {
        $gallery1 = new Gallery();
        $gallery2 = new Gallery();
        self::assertNotSame($gallery1->getCategories(), $gallery2->getCategories());
    }

    // ── getCoverImage ───────────────────────────────────────────────────────

    #[Test]
    public function getCoverImageReturnsNullWhenImagesAreEmpty(): void
    {
        $gallery = new Gallery();
        self::assertNull($gallery->getCoverImage());
    }
}
