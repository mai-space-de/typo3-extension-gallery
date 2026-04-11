<?php

declare(strict_types=1);

use Maispace\MaiBase\TableConfigurationArray\Helper;
use Maispace\MaiBase\TableConfigurationArray\Table;

$lang = Helper::localLangHelperFactory('mai_gallery', 'Default/locallang_tca.xlf');

return (new Table($lang('table.tx_maigallery_gallery')))
    ->setDefaultConfig()
    ->setLabel('title')
    ->setSearchFields('title, description')
    ->setIconFile('EXT:mai_gallery/Resources/Public/Icons/tx_maigallery_gallery.svg')
    ->setDefaultSorting('ORDER BY year DESC, crdate DESC')
    ->addColumn(
        'title',
        $lang('tx_maigallery_gallery.title'),
        ['type' => 'input', 'size' => 50, 'max' => 255, 'eval' => 'trim,required']
    )
    ->addColumn(
        'description',
        $lang('tx_maigallery_gallery.description'),
        ['type' => 'text', 'rows' => 5, 'cols' => 50, 'eval' => 'trim']
    )
    ->addColumn(
        'year',
        $lang('tx_maigallery_gallery.year'),
        [
            'type' => 'number',
            'format' => 'integer',
            'range' => ['lower' => 1900, 'upper' => 2100],
        ]
    )
    ->addColumn(
        'images',
        $lang('tx_maigallery_gallery.images'),
        [
            'type' => 'file',
            'allowed' => 'common-image-types',
            'appearance' => [
                'createNewRelationLinkTitle' => $lang('tx_maigallery_gallery.images.addFile'),
                'enabledControls' => ['info' => true, 'dragdrop' => true, 'sort' => true, 'hide' => true, 'delete' => true],
            ],
        ]
    )
    ->addColumn(
        'categories',
        $lang('tx_maigallery_gallery.categories'),
        ['type' => 'category']
    )
    ->addTypeShowItem(
        '0',
        'title, description, year, images, categories,
        --div--;' . $lang('tab.language') . ', --palette--;;language,
        --div--;' . $lang('tab.access') . ', --palette--;;hidden, --palette--;;access'
    )
    ->getConfig();
