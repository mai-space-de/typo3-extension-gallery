<?php

defined("TYPO3") or die();

(static function (): void {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        "Gallery",
        "GalleryList",
        "Gallery: Galerieübersicht & Detailansicht",
        "EXT:mai_gallery/Resources/Public/Icons/Extension.svg",
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        "Gallery",
        "GalleryArchive",
        "Gallery: Jahresarchiv",
        "EXT:mai_gallery/Resources/Public/Icons/Extension.svg",
    );

    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
        "Gallery",
        "GalleryRetrospective",
        "Gallery: Rückblick-Listenansicht",
        "EXT:mai_gallery/Resources/Public/Icons/Extension.svg",
    );

    // FlexForm for GalleryList plugin
    $GLOBALS["TCA"]["tt_content"]["types"]["gallery_gallerylist"]["showitem"] =
        "--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:general," .
        "--palette--;;general," .
        "--palette--;;headers," .
        "pi_flexform," .
        "--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:appearance," .
        "--palette--;;frames," .
        "--palette--;;appearanceLinks," .
        "--div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access," .
        "--palette--;;hidden," .
        "--palette--;;access";

    $GLOBALS["TCA"]["tt_content"]["types"]["gallery_gallerylist"]["columnsOverrides"]["pi_flexform"]["config"]["ds"] =
        "FILE:EXT:mai_gallery/Configuration/FlexForms/GalleryList.xml";
})();
