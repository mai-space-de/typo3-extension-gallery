<?php

declare(strict_types=1);

namespace Maispace\MaiGallery\Controller;

use Maispace\MaiGallery\Domain\Model\Gallery;
use Maispace\MaiGallery\Domain\Model\GalleryCategory;
use Maispace\MaiGallery\Domain\Repository\GalleryCategoryRepository;
use Maispace\MaiGallery\Domain\Repository\GalleryRepository;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

class GalleryController extends ActionController
{
    public function __construct(
        private readonly GalleryRepository $galleryRepository,
        private readonly GalleryCategoryRepository $galleryCategoryRepository
    ) {
    }

    /**
     * List action: Galerieübersicht with optional year and category filter.
     */
    public function listAction(?int $year = null, ?GalleryCategory $category = null): ResponseInterface
    {
        $galleries = $this->galleryRepository->findByYearAndCategory($year, $category);
        $categories = $this->galleryCategoryRepository->findAll();
        $years = $this->galleryRepository->findDistinctYears();

        $this->view->assignMultiple([
            'galleries' => $galleries,
            'categories' => $categories,
            'years' => $years,
            'selectedYear' => $year,
            'selectedCategory' => $category,
        ]);

        return $this->htmlResponse();
    }

    /**
     * Show action: Lightbox-Detail view for a single gallery.
     */
    public function showAction(Gallery $gallery): ResponseInterface
    {
        $this->view->assign('gallery', $gallery);

        return $this->htmlResponse();
    }

    /**
     * Archive action: Jahresarchiv grouped by year.
     */
    public function archiveAction(?int $year = null): ResponseInterface
    {
        $years = $this->galleryRepository->findDistinctYears();

        if ($year === null && !empty($years)) {
            $year = $years[0];
        }

        $galleries = $year !== null
            ? $this->galleryRepository->findByYear($year)
            : $this->galleryRepository->findAll();

        $this->view->assignMultiple([
            'galleries' => $galleries,
            'years' => $years,
            'selectedYear' => $year,
        ]);

        return $this->htmlResponse();
    }

    /**
     * Retrospective action: Rückblick-Listenansicht grouped by year.
     */
    public function retrospectiveAction(?int $year = null): ResponseInterface
    {
        $years = $this->galleryRepository->findDistinctYears();
        $allGalleries = $this->galleryRepository->findAll();

        $galleriesByYear = [];
        foreach ($allGalleries as $gallery) {
            $galleryYear = $gallery->getYear();
            if ($galleryYear !== null) {
                $galleriesByYear[$galleryYear][] = $gallery;
            }
        }

        krsort($galleriesByYear);

        $this->view->assignMultiple([
            'galleriesByYear' => $galleriesByYear,
            'years' => $years,
            'selectedYear' => $year,
        ]);

        return $this->htmlResponse();
    }
}
