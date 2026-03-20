<?php

declare(strict_types=1);

namespace Maispace\MaiGallery\Domain\Repository;

use Maispace\MaiGallery\Domain\Model\GalleryCategory;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

/**
 * @extends Repository<GalleryCategory>
 */
class GalleryCategoryRepository extends Repository
{
    protected $defaultOrderings = [
        'sorting' => QueryInterface::ORDER_ASCENDING,
        'title' => QueryInterface::ORDER_ASCENDING,
    ];
}
