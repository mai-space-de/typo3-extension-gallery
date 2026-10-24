<?php

declare(strict_types=1);

use Maispace\MaiBase\Icon\StreamlineSvgIconProvider;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

$allIcons = 'EXT:mai_base/Resources/Public/Icons/all-icons/';

return [
    'ext-maispace-mai_gallery' => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:mai_gallery/Resources/Public/Icons/Extension.svg',
    ],
    'mai-gallery' => [
        'provider' => StreamlineSvgIconProvider::class,
        'source' => $allIcons . 'image-picture-gallery--pages-filter-picture-pagination-image--Streamline-Core.svg',
    ],
];
