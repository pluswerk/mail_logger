<?php

/** @var string $_EXTKEY */
$EM_CONF[$_EXTKEY] = [
    'title' => '+Pluswerk: Mail Logger',
    'description' => 'This extension logs all your outgoing mails and provides email templates and debugging tools',
    'category' => 'module',
    'author' => 'Markus Hölzle',
    'author_email' => 'markus.hoelzle@pluswerk.ag',
    'state' => 'stable',
    'version' => \Composer\InstalledVersions::getPrettyVersion('pluswerk/mail-logger'),
    'constraints' => [
        'depends' => [
            'typo3' => '11.0.0-13.2.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
