<?php

declare(strict_types=1);

namespace MaiSpace\Gallery\Domain\Repository;

use MaiSpace\Gallery\Domain\Model\GalleryCategory;
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
