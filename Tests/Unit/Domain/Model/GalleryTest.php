<?php

declare(strict_types=1);

namespace Maispace\MaiGallery\Tests\Unit\Domain\Model;

use Maispace\MaiGallery\Domain\Model\Gallery;
use Maispace\MaiGallery\Domain\Model\GalleryCategory;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class GalleryTest extends TestCase
{
    private Gallery $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new Gallery();
    }

    public function testTitleIsEmptyByDefault(): void
    {
        self::assertSame('', $this->subject->getTitle());
    }

    public function testSetAndGetTitle(): void
    {
        $this->subject->setTitle('Sommerfest 2024');
        self::assertSame('Sommerfest 2024', $this->subject->getTitle());
    }

    public function testDateIsNullByDefault(): void
    {
        self::assertNull($this->subject->getDate());
    }

    public function testSetAndGetDate(): void
    {
        $date = new \DateTime('2024-07-15');
        $this->subject->setDate($date);
        self::assertSame($date, $this->subject->getDate());
    }

    public function testGetYearReturnsNullWhenDateNotSet(): void
    {
        self::assertNull($this->subject->getYear());
    }

    public function testGetYearReturnsCorrectYear(): void
    {
        $this->subject->setDate(new \DateTime('2024-07-15'));
        self::assertSame(2024, $this->subject->getYear());
    }

    public function testProjectReferenceIsEmptyByDefault(): void
    {
        self::assertSame('', $this->subject->getProjectReference());
    }

    public function testSetAndGetProjectReference(): void
    {
        $this->subject->setProjectReference('Projekt-001');
        self::assertSame('Projekt-001', $this->subject->getProjectReference());
    }

    public function testCategoryIsNullByDefault(): void
    {
        self::assertNull($this->subject->getCategory());
    }

    public function testSetAndGetCategory(): void
    {
        $category = new GalleryCategory();
        $category->setTitle('Veranstaltung');
        $this->subject->setCategory($category);
        self::assertSame($category, $this->subject->getCategory());
    }

    public function testDescriptionIsEmptyByDefault(): void
    {
        self::assertSame('', $this->subject->getDescription());
    }

    public function testSetAndGetDescription(): void
    {
        $this->subject->setDescription('Eine tolle Galerie');
        self::assertSame('Eine tolle Galerie', $this->subject->getDescription());
    }

    public function testMediaIsObjectStorageByDefault(): void
    {
        self::assertInstanceOf(ObjectStorage::class, $this->subject->getMedia());
    }

    public function testMediaIsEmptyByDefault(): void
    {
        self::assertCount(0, $this->subject->getMedia());
    }

    public function testSlugIsEmptyByDefault(): void
    {
        self::assertSame('', $this->subject->getSlug());
    }

    public function testSetAndGetSlug(): void
    {
        $this->subject->setSlug('sommerfest-2024');
        self::assertSame('sommerfest-2024', $this->subject->getSlug());
    }

    public function testGetFirstMediaReturnsNullWhenNoMedia(): void
    {
        self::assertNull($this->subject->getFirstMedia());
    }
}
