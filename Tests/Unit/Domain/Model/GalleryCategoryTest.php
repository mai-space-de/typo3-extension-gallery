<?php

declare(strict_types=1);

namespace MaiSpace\Gallery\Tests\Unit\Domain\Model;

use MaiSpace\Gallery\Domain\Model\GalleryCategory;
use PHPUnit\Framework\TestCase;

class GalleryCategoryTest extends TestCase
{
    private GalleryCategory $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new GalleryCategory();
    }

    public function testTitleIsEmptyByDefault(): void
    {
        self::assertSame('', $this->subject->getTitle());
    }

    public function testSetAndGetTitle(): void
    {
        $this->subject->setTitle('Veranstaltung');
        self::assertSame('Veranstaltung', $this->subject->getTitle());
    }

    public function testDescriptionIsEmptyByDefault(): void
    {
        self::assertSame('', $this->subject->getDescription());
    }

    public function testSetAndGetDescription(): void
    {
        $this->subject->setDescription('Kategorie für Veranstaltungen');
        self::assertSame('Kategorie für Veranstaltungen', $this->subject->getDescription());
    }

    public function testSlugIsEmptyByDefault(): void
    {
        self::assertSame('', $this->subject->getSlug());
    }

    public function testSetAndGetSlug(): void
    {
        $this->subject->setSlug('veranstaltung');
        self::assertSame('veranstaltung', $this->subject->getSlug());
    }

    public function testSortingIsZeroByDefault(): void
    {
        self::assertSame(0, $this->subject->getSorting());
    }

    public function testSetAndGetSorting(): void
    {
        $this->subject->setSorting(10);
        self::assertSame(10, $this->subject->getSorting());
    }
}
