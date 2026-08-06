<?php

declare(strict_types=1);

namespace Maispace\MaiGallery\Controller;

use Maispace\MaiBase\Controller\AbstractActionController;
use Maispace\MaiBase\Controller\Traits\DetailActionTrait;
use Maispace\MaiGallery\Domain\Model\Gallery;
use Maispace\MaiGallery\Domain\Repository\GalleryRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Pagination\QueryBuilderPaginator;
use TYPO3\CMS\Core\Pagination\SimplePagination;

class GalleryController extends AbstractActionController
{
    use DetailActionTrait;

    public function __construct(
        private readonly GalleryRepository $galleryRepository,
    ) {}

    public function listAction(int $year = 0, int $category = 0, int $page = 1): ResponseInterface
    {
        $settings = $this->getSettings();
        $itemsPerPage = (int) ($settings['limit'] ?? 10);

        $queryBuilder = $this->galleryRepository->createQueryBuilderForPagination($category, $year);

        $paginator = new QueryBuilderPaginator(
            $queryBuilder,
            $page,
            $itemsPerPage
        );

        $pagination = new SimplePagination($paginator);

        $years = $this->galleryRepository->findDistinctYears();

        $this->view->assignMultiple([
            'galleries' => $paginator->getPaginatedItems(),
            'years' => $years,
            'selectedYear' => $year,
            'selectedCategory' => $category,
            'pagination' => $pagination,
            'paginator' => $paginator,
            'currentPage' => $page,
            'settings' => $settings,
            'contentObject' => $this->getContentObjectData(),
        ]);

        return $this->htmlResponse();
    }

    public function showAction(): ResponseInterface
    {
        $gallery = $this->resolveDetailOrNotFound($this->galleryRepository);
        assert($gallery instanceof Gallery);

        $this->view->assignMultiple([
            'gallery' => $gallery,
            'settings' => $this->getSettings(),
            'contentObject' => $this->getContentObjectData(),
        ]);

        return $this->htmlResponse();
    }
}
