<?php

$EM_CONF[$_EXTKEY] = [
    'title' => '+Pluswerk: Mail Logger',
    'description' => 'This extension logs all your outoing mails and provides email templates and debugging tools',
    'category' => 'module',
    'author' => 'Markus Hölzle',
    'author_email' => 'markus.hoelzle@pluswerk.ag',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '1.2.6',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-9.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
