<?php

declare(strict_types=1);

use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::registerPlugin(
    'mai_gallery',
    'Gallery',
    'LLL:EXT:mai_gallery/Resources/Private/Language/Default/locallang.xlf:plugin.gallery.title',
    'mai-content',
    'maispace_plugins_lists',
    'LLL:EXT:mai_gallery/Resources/Private/Language/Default/locallang.xlf:plugin.gallery.description',
);

$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['maigallery_gallery'] = 'pi_flexform';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['maigallery_gallery'] = 'layout,select_key,pages,recursive';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'maigallery_gallery',
    'FILE:EXT:mai_gallery/Configuration/FlexForms/GalleryPlugin.xml'
);
