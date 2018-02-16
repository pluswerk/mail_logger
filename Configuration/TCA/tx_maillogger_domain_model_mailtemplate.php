<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_mailtemplate',
        'label' => 'title',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'dividers2tabs' => true,

        'versioningWS' => true,
        'languageField' => 'sys_language_uid',
        'transOrigPointerField' => 'l10n_parent',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'title,typo_script_key,subject,message,mail_from_name,mail_from_address,mail_to_names,mail_to_addresses,mail_copy_addresses,mail_blind_copy_addresses,',
        'iconfile' => 'EXT:mail_logger/Resources/Public/Icons/MailTemplate.svg',
    ],
    'interface' => [
        'showRecordFieldList' => 'sys_language_uid, l10n_parent, l10n_diffsource, hidden, title,typo_script_key,subject,message,mail_from_name,mail_from_address,mail_to_names,mail_to_addresses,mail_copy_addresses,mail_blind_copy_addresses',
    ],
    'types' => [
        '1' => ['showitem' => 'sys_language_uid;;;;1-1-1, l10n_parent, l10n_diffsource, title,typo_script_key,subject,message,--div--;LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_mailtemplate.mailingOptions,mail_from_name,mail_from_address,mail_to_names,mail_to_addresses,mail_copy_addresses,mail_blind_copy_addresses, --div--;LLL:EXT:cms/locallang_ttc.xlf:tabs.access, hidden, --palette--;;1, starttime, endtime'],
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
                'default' => 0,
                'showIconTable' => true, // Legacy support for TYPO3 version <= 7.6
                'fieldWizard' => [
                    'selectIcons' => [
                        'disabled' => false,
                    ],
                ],
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_maillogger_domain_model_mailtemplate',
                'foreign_table_where' => 'AND tx_maillogger_domain_model_mailtemplate.pid=###CURRENT_PID### AND tx_maillogger_domain_model_mailtemplate.sys_language_uid IN (-1,0)',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],

        't3ver_label' => [
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.versionLabel',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'max' => 255,
            ],
        ],

        'hidden' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.hidden',
            'config' => [
                'type' => 'check',
            ],
        ],
        'starttime' => [
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
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
                ],
            ],
        ],
        'endtime' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:lang/locallang_general.xlf:LGL.endtime',
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
                'range' => [
                    'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y')),
                ],
            ],
        ],

        'typo_script_key' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_mailtemplate.typo_script_key',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'maxitems' => 1,
                'minitems' => 0,
                'items' => [],
                'itemsProcFunc' => 'Pluswerk\MailLogger\Wizard\MailTemplate->getTypoScriptKeys',
            ],
        ],
        'title' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_mailtemplate.title',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'subject' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_mailtemplate.subject',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'message' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_mailtemplate.message',
            'config' => [
                'type' => 'text',
                'cols' => 40,
                'rows' => 15,
                'eval' => 'trim',
            ],
        ],
        'mail_from_name' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_mailtemplate.mail_from_name',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'mail_from_address' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_mailtemplate.mail_from_address',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim',
            ],
        ],
        'mail_to_names' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_mailtemplate.mail_to_names',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim',
            ],
        ],
        'mail_to_addresses' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_mailtemplate.mail_to_addresses',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim',
            ],
        ],
        'mail_copy_addresses' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_mailtemplate.mail_copy_addresses',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim',
            ],
        ],
        'mail_blind_copy_addresses' => [
            'exclude' => 1,
            'label' => 'LLL:EXT:mail_logger/Resources/Private/Language/locallang_db.xlf:tx_maillogger_domain_model_mailtemplate.mail_blind_copy_addresses',
            'config' => [
                'type' => 'input',
                'size' => 50,
                'eval' => 'trim',
            ],
        ],

    ],
];
