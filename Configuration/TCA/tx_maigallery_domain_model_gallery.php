<?php

declare(strict_types=1);

return [
    "ctrl" => [
        "title" =>
            "LLL:EXT:mai_gallery/Resources/Private/Language/locallang_db.xlf:tx_maigallery_domain_model_gallery",
        "label" => "title",
        "label_alt" => "date",
        "label_alt_force" => true,
        "tstamp" => "tstamp",
        "crdate" => "crdate",
        "sortby" => "sorting",
        "delete" => "deleted",
        "enablecolumns" => [
            "disabled" => "hidden",
            "starttime" => "starttime",
            "endtime" => "endtime",
        ],
        "transOrigPointerField" => "l10n_parent",
        "transOrigDiffSourceField" => "l10n_diffsource",
        "languageField" => "sys_language_uid",
        "translationSource" => "l10n_source",
        "searchFields" => "title,project_reference,description",
        "iconfile" =>
            "EXT:mai_gallery/Resources/Public/Icons/tx_maigallery_domain_model_gallery.svg",
    ],
    "types" => [
        "1" => [
            "showitem" => '
                --div--;LLL:EXT:mai_gallery/Resources/Private/Language/locallang_db.xlf:tabs.general,
                    title,
                    date,
                    category,
                    project_reference,
                    description,
                    media,
                    slug,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:access,
                    --palette--;;hidden,
                    starttime,endtime,
                --div--;LLL:EXT:core/Resources/Private/Language/Form/locallang_tabs.xlf:language,
                    --palette--;;language,
            ',
        ],
    ],
    "palettes" => [
        "hidden" => [
            "showitem" => "hidden",
        ],
        "language" => [
            "showitem" => "sys_language_uid, l10n_parent, l10n_diffsource",
        ],
    ],
    "columns" => [
        "sys_language_uid" => [
            "exclude" => true,
            "label" =>
                "LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language",
            "config" => [
                "type" => "language",
            ],
        ],
        "l10n_parent" => [
            "displayCond" => "FIELD:sys_language_uid:>:0",
            "label" =>
                "LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent",
            "config" => [
                "type" => "select",
                "renderType" => "selectSingle",
                "items" => [["label" => "", "value" => 0]],
                "foreign_table" => "tx_maigallery_domain_model_gallery",
                "foreign_table_where" =>
                    "AND {#tx_maigallery_domain_model_gallery}.{#pid}=###CURRENT_PID### AND {#tx_maigallery_domain_model_gallery}.{#sys_language_uid} IN (-1,0)",
                "default" => 0,
            ],
        ],
        "l10n_diffsource" => [
            "config" => [
                "type" => "passthrough",
            ],
        ],
        "hidden" => [
            "exclude" => true,
            "label" =>
                "LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible",
            "config" => [
                "type" => "check",
                "renderType" => "checkboxToggle",
                "items" => [
                    [
                        "label" => "",
                        "invertStateDisplay" => true,
                    ],
                ],
            ],
        ],
        "starttime" => [
            "exclude" => true,
            "label" =>
                "LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime",
            "config" => [
                "type" => "datetime",
                "default" => 0,
                "behaviour" => [
                    "allowLanguageSynchronization" => true,
                ],
            ],
        ],
        "endtime" => [
            "exclude" => true,
            "label" =>
                "LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime",
            "config" => [
                "type" => "datetime",
                "default" => 0,
                "range" => [
                    "upper" => mktime(0, 0, 0, 1, 1, 2106),
                ],
                "behaviour" => [
                    "allowLanguageSynchronization" => true,
                ],
            ],
        ],
        "title" => [
            "exclude" => false,
            "label" =>
                "LLL:EXT:mai_gallery/Resources/Private/Language/locallang_db.xlf:tx_maigallery_domain_model_gallery.title",
            "config" => [
                "type" => "input",
                "size" => 50,
                "max" => 255,
                "eval" => "trim",
                "required" => true,
            ],
        ],
        "date" => [
            "exclude" => false,
            "label" =>
                "LLL:EXT:mai_gallery/Resources/Private/Language/locallang_db.xlf:tx_maigallery_domain_model_gallery.date",
            "config" => [
                "type" => "datetime",
                "format" => "date",
                "default" => 0,
            ],
        ],
        "project_reference" => [
            "exclude" => true,
            "label" =>
                "LLL:EXT:mai_gallery/Resources/Private/Language/locallang_db.xlf:tx_maigallery_domain_model_gallery.project_reference",
            "config" => [
                "type" => "input",
                "size" => 50,
                "max" => 255,
                "eval" => "trim",
            ],
        ],
        "category" => [
            "exclude" => true,
            "label" =>
                "LLL:EXT:mai_gallery/Resources/Private/Language/locallang_db.xlf:tx_maigallery_domain_model_gallery.category",
            "config" => [
                "type" => "select",
                "renderType" => "selectSingle",
                "items" => [["label" => "", "value" => 0]],
                "foreign_table" => "tx_maigallery_domain_model_gallerycategory",
                "foreign_table_where" =>
                    "ORDER BY tx_maigallery_domain_model_gallerycategory.sorting ASC",
                "default" => 0,
                "minitems" => 0,
                "maxitems" => 1,
            ],
        ],
        "description" => [
            "exclude" => true,
            "label" =>
                "LLL:EXT:mai_gallery/Resources/Private/Language/locallang_db.xlf:tx_maigallery_domain_model_gallery.description",
            "config" => [
                "type" => "text",
                "cols" => 40,
                "rows" => 5,
                "eval" => "trim",
                "enableRichtext" => true,
            ],
        ],
        "media" => [
            "exclude" => false,
            "label" =>
                "LLL:EXT:mai_gallery/Resources/Private/Language/locallang_db.xlf:tx_maigallery_domain_model_gallery.media",
            "config" => [
                "type" => "file",
                "allowed" => "common-image-types",
                "appearance" => [
                    "createNewRelationLinkTitle" =>
                        "LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:images.addFileReference",
                    "showPossibleLocalizationRecords" => true,
                    "useSortable" => true,
                    "headerThumbnail" => [
                        "field" => "uid_local",
                        "height" => "45m",
                        "width" => "45m",
                    ],
                ],
                "overrideChildTca" => [
                    "types" => [
                        "0" => [
                            "showitem" => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette',
                        ],
                        \TYPO3\CMS\Core\Resource\File::FILETYPE_IMAGE => [
                            "showitem" => '
                                --palette--;;imageoverlayPalette,
                                --palette--;;filePalette',
                        ],
                    ],
                ],
                "minitems" => 0,
                "maxitems" => 99,
            ],
        ],
        "slug" => [
            "exclude" => true,
            "label" =>
                "LLL:EXT:core/Resources/Private/Language/locallang_tca.xlf:pages.slug",
            "config" => [
                "type" => "slug",
                "size" => 50,
                "generatorOptions" => [
                    "fields" => ["title"],
                    "replacements" => [
                        "/" => "-",
                    ],
                ],
                "fallbackCharacter" => "-",
                "eval" => "uniqueInPid",
                "default" => "",
            ],
        ],
    ],
];
