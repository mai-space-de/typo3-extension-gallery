<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Gallery - Bilder-Galerie & Rückblicke',
    'description' => 'Bilder-Galerie & Rückblicke mit FAL-Integration, Jahresarchiv und Kategoriefilter.',
    'category' => 'plugin',
    'author' => 'Joel Maximilian Mai',
    'author_email' => '',
    'author_company' => 'maispace',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-12.99.99',
            'extbase' => '12.4.0-12.99.99',
            'fluid' => '12.4.0-12.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
