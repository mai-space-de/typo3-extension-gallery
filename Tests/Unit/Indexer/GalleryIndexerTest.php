<?php

declare(strict_types=1);

namespace Maispace\MaiGallery\Tests\Unit\Indexer;

use Maispace\MaiGallery\Domain\Model\Gallery;
use Maispace\MaiGallery\Indexer\GalleryIndexer;
use Maispace\MaiSearch\Domain\Service\SearchBackendInterface;
use Maispace\MaiSearch\Service\BackendRegistry;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class GalleryIndexerTest extends TestCase
{
    private GalleryIndexer $subject;
    private BackendRegistry&MockObject $backendRegistry;
    private SearchBackendInterface&MockObject $activeBackend;

    protected function setUp(): void
    {
        $this->subject = new GalleryIndexer();

        $this->activeBackend = $this->createMock(SearchBackendInterface::class);
        $this->backendRegistry = $this->createMock(BackendRegistry::class);
        $this->backendRegistry->method('getActive')->willReturn($this->activeBackend);
        $this->subject->injectBackendRegistry($this->backendRegistry);
    }

    #[Test]
    public function removeRecordDelegatesToActiveBackend(): void
    {
        $this->activeBackend
            ->expects(self::once())
            ->method('removeDocument')
            ->with('gallery', 42);

        $this->subject->removeRecord(42, 'tx_maigallery_gallery');
    }

    #[Test]
    public function removeRecordIsNoOpForUnsupportedTable(): void
    {
        $this->activeBackend->expects(self::never())->method('removeDocument');

        $this->subject->removeRecord(42, 'tx_mainews_news');
    }

    #[Test]
    public function getTypeReturnsGallery(): void
    {
        self::assertSame('gallery', $this->subject->getType());
    }

    #[Test]
    public function supportsGalleryTable(): void
    {
        self::assertTrue($this->subject->supports('tx_maigallery_gallery'));
    }

    #[Test]
    public function doesNotSupportOtherTables(): void
    {
        self::assertFalse($this->subject->supports('tx_mainews_news'));
        self::assertFalse($this->subject->supports('pages'));
        self::assertFalse($this->subject->supports('tt_content'));
    }

    #[Test]
    public function getIconReturnsExpectedValue(): void
    {
        self::assertSame('content-gallery', $this->subject->getIcon('gallery'));
    }

    #[Test]
    public function buildContentStripsHtmlTags(): void
    {
        $gallery = new Gallery();
        $gallery->setTitle('Summer Exhibition');
        $gallery->setDescription('<p>Gallery description with <strong>bold</strong> text.</p>');

        $content = $this->invokeBuildContent($gallery);

        self::assertStringNotContainsString('<p>', $content);
        self::assertStringNotContainsString('<strong>', $content);
        self::assertStringContainsString('Gallery description with', $content);
        self::assertStringContainsString('bold', $content);
    }

    #[Test]
    public function buildContentReturnsEmptyStringForNonGalleryRecord(): void
    {
        $content = $this->invokeBuildContent(new \stdClass());

        self::assertSame('', $content);
    }

    #[Test]
    public function formatResultReturnsSearchResultWithCorrectType(): void
    {
        $solrDoc = [
            'title_s' => 'Summer Exhibition',
            'content_t' => 'A wonderful gallery exhibit.',
            'url_s' => '/gallery/summer',
            'score' => 2.5,
        ];

        $result = $this->subject->formatResult($solrDoc);

        self::assertSame('gallery', $result->type);
        self::assertSame('Summer Exhibition', $result->title);
        self::assertSame('/gallery/summer', $result->url);
        self::assertSame('content-gallery', $result->icon);
        self::assertSame(2.5, $result->score);
    }

    #[Test]
    public function formatResultDefaultsToEmptyStringsWhenFieldsAreMissing(): void
    {
        $result = $this->subject->formatResult([]);

        self::assertSame('', $result->title);
        self::assertSame('', $result->url);
        self::assertSame(0.0, $result->score);
        self::assertNull($result->date);
    }

    private function invokeBuildContent(object $record): string
    {
        $reflection = new \ReflectionMethod($this->subject, 'buildContent');
        $reflection->setAccessible(true);

        /** @var string $result */
        return $reflection->invoke($this->subject, $record);
    }
}
