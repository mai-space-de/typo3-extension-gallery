<?php

declare(strict_types=1);

namespace Maispace\MaiGallery\Tests\Unit\Domain\Repository;

use Maispace\MaiGallery\Domain\Repository\GalleryRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

final class GalleryRepositoryTest extends TestCase
{
    #[Test]
    public function repositoryExtendsTYPO3BaseRepository(): void
    {
        self::assertTrue(
            is_subclass_of(GalleryRepository::class, Repository::class),
            GalleryRepository::class . ' must extend ' . Repository::class,
        );
    }

    #[Test]
    public function defaultOrderingsContainYearDescending(): void
    {
        $reflection = new \ReflectionClass(GalleryRepository::class);
        $defaults = $reflection->getDefaultProperties();

        self::assertArrayHasKey('defaultOrderings', $defaults);
        self::assertIsArray($defaults['defaultOrderings']);
        self::assertArrayHasKey('year', $defaults['defaultOrderings']);
        self::assertSame(QueryInterface::ORDER_DESCENDING, $defaults['defaultOrderings']['year']);
    }

    #[Test]
    public function defaultOrderingsContainCrdateDescending(): void
    {
        $reflection = new \ReflectionClass(GalleryRepository::class);
        $defaults = $reflection->getDefaultProperties();

        self::assertArrayHasKey('defaultOrderings', $defaults);
        self::assertIsArray($defaults['defaultOrderings']);
        self::assertArrayHasKey('crdate', $defaults['defaultOrderings']);
        self::assertSame(QueryInterface::ORDER_DESCENDING, $defaults['defaultOrderings']['crdate']);
    }

    #[Test]
    public function defaultOrderingsContainExactlyTwoSortKeys(): void
    {
        $reflection = new \ReflectionClass(GalleryRepository::class);
        $defaults = $reflection->getDefaultProperties();

        self::assertCount(2, $defaults['defaultOrderings']);
    }
}
