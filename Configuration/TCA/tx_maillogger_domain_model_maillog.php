<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_maillog',
        'label' => 'mail_to',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,
        'hideTable' => true,

        'languageField' => 'sys_language_uid',
        'enablecolumns' => [],
        'searchFields' => 'typo_script_key,subject,message,mail_from,mail_to,mail_copy,mail_blind_copy,',
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid,tstamp,crdate,typo_script_key,subject,message,mail_from,mail_to,mail_copy,mail_blind_copy,result',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid,typo_script_key,subject,message,mail_from,mail_to,mail_copy,mail_blind_copy,result'],
    ],
    'palettes' => [
        '1' => ['showitem' => ''],
    ],
    'columns' => [
        'sys_language_uid' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'foreign_table' => 'sys_language',
                'foreign_table_where' => 'ORDER BY sys_language.title',
                'items' => [
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.allLanguages', -1],
                    ['LLL:EXT:lang/locallang_general.xlf:LGL.default_value', 0],
                ],
            ],
        ],
        'tstamp' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'crdate' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'size' => 13,
                'eval' => 'datetime',
                'checkbox' => 0,
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'typo_script_key' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_maillog.typo_script_key',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'subject' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_maillog.subject',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'message' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_maillog.message',
            'config' => [
                'readOnly' => 1,
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
        ],
        'mail_from' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_maillog.mail_from',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim',
            ],
        ],
        'mail_to' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_maillog.mail_to',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim',
            ],
        ],
        'mail_copy' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_maillog.mail_copy',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim',
            ],
        ],
        'mail_blind_copy' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_maillog.mail_blind_copy',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim',
            ],
        ],
        'result' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_maillog.result',
            'config' => [
                'readOnly' => 1,
                'type' => 'input',
                'size' => 10,
                'eval' => 'trim',
            ],
        ],
        'headers' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_maillog.headers',
            'config' => [
                'readOnly' => 1,
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
        ],

    ],
];
