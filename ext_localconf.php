<?php

declare(strict_types=1);

use Maispace\MaiGallery\Controller\GalleryController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::configurePlugin(
    'mai_gallery',
    'Gallery',
    [
        GalleryController::class => 'list, show',
    ],
    [
        GalleryController::class => 'list',
    ],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT,
);
