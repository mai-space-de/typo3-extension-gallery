<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Mai Gallery',
    'description' => 'Image gallery and retrospectives extension using TYPO3 FAL. Supports year archives and category filtering via TYPO3 `sys_category`, sharing the same tree as `mai_news`, `mai_faq`, and `mai_timeline`.',
    'category' => 'module',
    'author' => 'Maispace',
    'author_email' => '',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '13.4.0-14.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
