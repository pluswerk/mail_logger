<?php

$EM_CONF[\Pluswerk\MailLogger\Utility\ConfigurationUtility::EXTENSION_KEY] = [
    'title' => '+Pluswerk: Mail Logger',
    'description' => 'This extensions logs all your outoing mails and provides email templates and debugging tools',
    'category' => 'module',
    'author' => 'Markus Hölzle',
    'author_email' => 'markus.hoelzle@pluswerk.ag',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '7.6.0-8.99.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
