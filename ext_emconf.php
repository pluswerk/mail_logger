<?php

/** @var string $_EXTKEY */
$EM_CONF[$_EXTKEY] = [
    'title' => '+Pluswerk: Mail Logger',
    'description' => 'This extension logs all your outgoing mails and provides email templates and debugging tools',
    'category' => 'module',
    'author' => 'Markus Hölzle',
    'author_email' => 'markus.hoelzle@pluswerk.ag',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => \Composer\InstalledVersions::getPrettyVersion('pluswerk/mail_logger'),
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.0-11.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
