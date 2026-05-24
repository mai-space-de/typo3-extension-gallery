<?php

declare(strict_types=1);

namespace Maispace\MaiGallery\Tests\Unit\Controller;

use Maispace\MaiBase\Controller\AbstractActionController;
use Maispace\MaiGallery\Controller\GalleryController;
use Maispace\MaiGallery\Domain\Repository\GalleryRepository;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

final class GalleryControllerTest extends TestCase
{
    #[Test]
    public function controllerExtendsAbstractActionController(): void
    {
        self::assertTrue(
            is_subclass_of(GalleryController::class, AbstractActionController::class),
        );
    }

    #[Test]
    public function constructorRequiresGalleryRepository(): void
    {
        $params = (new \ReflectionMethod(GalleryController::class, '__construct'))
            ->getParameters();

        $names = array_map(static fn(\ReflectionParameter $p) => $p->getName(), $params);
        self::assertContains('galleryRepository', $names);

        $repoParam = array_values(array_filter(
            $params,
            static fn(\ReflectionParameter $p) => $p->getName() === 'galleryRepository',
        ))[0];

        $type = $repoParam->getType();
        self::assertInstanceOf(\ReflectionNamedType::class, $type);
        self::assertSame(GalleryRepository::class, $type->getName());
    }

    // ── listAction ────────────────────────────────────────────────────────────

    #[Test]
    public function listActionMethodExists(): void
    {
        self::assertTrue(
            method_exists(GalleryController::class, 'listAction'),
        );
    }

    #[Test]
    public function listActionReturnsResponseInterface(): void
    {
        $returnType = (new \ReflectionMethod(GalleryController::class, 'listAction'))
            ->getReturnType();

        self::assertInstanceOf(\ReflectionNamedType::class, $returnType);
        self::assertSame(ResponseInterface::class, $returnType->getName());
    }

    #[Test]
    public function listActionAcceptsYearParameter(): void
    {
        $params = (new \ReflectionMethod(GalleryController::class, 'listAction'))
            ->getParameters();

        $names = array_map(static fn(\ReflectionParameter $p) => $p->getName(), $params);
        self::assertContains('year', $names);

        $yearParam = array_values(array_filter(
            $params,
            static fn(\ReflectionParameter $p) => $p->getName() === 'year',
        ))[0];

        $type = $yearParam->getType();
        self::assertInstanceOf(\ReflectionNamedType::class, $type);
        self::assertSame('int', $type->getName());
        self::assertTrue($yearParam->isDefaultValueAvailable());
        self::assertSame(0, $yearParam->getDefaultValue());
    }

    #[Test]
    public function listActionAcceptsCategoryParameter(): void
    {
        $params = (new \ReflectionMethod(GalleryController::class, 'listAction'))
            ->getParameters();

        $names = array_map(static fn(\ReflectionParameter $p) => $p->getName(), $params);
        self::assertContains('category', $names);

        $categoryParam = array_values(array_filter(
            $params,
            static fn(\ReflectionParameter $p) => $p->getName() === 'category',
        ))[0];

        $type = $categoryParam->getType();
        self::assertInstanceOf(\ReflectionNamedType::class, $type);
        self::assertSame('int', $type->getName());
        self::assertTrue($categoryParam->isDefaultValueAvailable());
        self::assertSame(0, $categoryParam->getDefaultValue());
    }

    // ── showAction ────────────────────────────────────────────────────────────

    #[Test]
    public function showActionMethodExists(): void
    {
        self::assertTrue(
            method_exists(GalleryController::class, 'showAction'),
        );
    }

    #[Test]
    public function showActionReturnsResponseInterface(): void
    {
        $returnType = (new \ReflectionMethod(GalleryController::class, 'showAction'))
            ->getReturnType();

        self::assertInstanceOf(\ReflectionNamedType::class, $returnType);
        self::assertSame(ResponseInterface::class, $returnType->getName());
    }
}
