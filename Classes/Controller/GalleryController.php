<?php

declare(strict_types=1);

namespace Maispace\MaiGallery\Controller;

use Maispace\MaiBase\Controller\AbstractActionController;
use Maispace\MaiBase\Controller\Traits\DetailActionTrait;
use Maispace\MaiBase\Controller\Traits\PaginationTrait;
use Maispace\MaiGallery\Domain\Model\Gallery;
use Maispace\MaiGallery\Domain\Repository\GalleryRepository;
use Psr\Http\Message\ResponseInterface;

class GalleryController extends AbstractActionController
{
    use PaginationTrait;
    use DetailActionTrait;

    public function __construct(
        private readonly GalleryRepository $galleryRepository,
    ) {}

    public function listAction(int $year = 0, int $category = 0): ResponseInterface
    {
        $galleries = $this->galleryRepository->findFilteredAndSorted($category, $year);
        $years = $this->galleryRepository->findDistinctYears();
        $pagination = $this->paginateQueryResult($galleries);

        $this->view->assignMultiple([
            'galleries' => $galleries,
            'years' => $years,
            'selectedYear' => $year,
            'selectedCategory' => $category,
            'pagination' => $pagination['pagination'],
            'paginator' => $pagination['paginator'],
            'settings' => $this->getSettings(),
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
