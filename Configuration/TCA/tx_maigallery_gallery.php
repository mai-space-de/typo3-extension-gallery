<?php

declare(strict_types=1);

use Maispace\MaiBase\TableConfigurationArray\FieldConfig\CategoryConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\FileConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\InputConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\NumberConfig;
use Maispace\MaiBase\TableConfigurationArray\FieldConfig\TextConfig;
use Maispace\MaiBase\TableConfigurationArray\Helper;
use Maispace\MaiBase\TableConfigurationArray\Table;

$lang = Helper::localLangHelperFactory('mai_gallery', 'Default/locallang_tca.xlf');

return (new Table($lang('table.tx_maigallery_gallery')))
    ->setDefaultConfig()
    ->setLabel('title')
    ->setIconFile('EXT:mai_gallery/Resources/Public/Icons/tx_maigallery_gallery.svg')
    ->setDefaultSorting('ORDER BY year DESC, crdate DESC')
    ->addColumn(
        'title',
        $lang('tx_maigallery_gallery.title'),
        (new InputConfig())->setSize(50)->setMax(255)->setEval('trim')->setRequired()
    )
    ->addColumn(
        'description',
        $lang('tx_maigallery_gallery.description'),
        (new TextConfig())->setRows(5)->setCols(50)->setEval('trim')
    )
    ->addColumn(
        'year',
        $lang('tx_maigallery_gallery.year'),
        (new NumberConfig())->setFormat('integer')->setRange(1900, 2100)
    )
    ->addColumn(
        'images',
        $lang('tx_maigallery_gallery.images'),
        (new FileConfig())
            ->setAllowed('common-image-types')
            ->setAppearance([
                'createNewRelationLinkTitle' => $lang('tx_maigallery_gallery.images.addFile'),
                'enabledControls' => ['info' => true, 'dragdrop' => true, 'sort' => true, 'hide' => true, 'delete' => true],
            ])
    )
    ->addColumn(
        'categories',
        $lang('tx_maigallery_gallery.categories'),
        new CategoryConfig()
    )
    ->addTypeShowItem(
        '0',
        'title, description, year, images, categories,
        --div--;' . $lang('tab.language') . ', --palette--;;language,
        --div--;' . $lang('tab.access') . ', --palette--;;hidden, --palette--;;access'
    )
    ->getConfig();
