[![Packagist Release](https://img.shields.io/packagist/v/pluswerk/mail-logger.svg?style=flat-square)](https://packagist.org/packages/pluswerk/mail-logger)
[![Travis](https://img.shields.io/travis/pluswerk/mail_logger.svg?style=flat-square)](https://travis-ci.org/pluswerk/mail_logger)
[![GitHub License](https://img.shields.io/github/license/pluswerk/mail_logger.svg?style=flat-square)](https://github.com/pluswerk/mail_logger/blob/master/LICENSE.txt)
[![Build Status](https://travis-ci.org/pluswerk/mail_logger.svg?branch=master)](https://travis-ci.org/pluswerk/mail_logger)

# +Pluswerk TYPO3 extension: Mail Logger

This is an TYPO3 extension with some mail functions:
1. [E-mail logging](#1-e-mail-logging)
2. [E-mail templates](#2-e-mail-templates)

## Extension installation

Install via composer or just copy the files into the TYPO3 extension folder:

```Shell
composer require pluswerk/mail-logger
```

Add the Typoscript files to your sites Typoscript:
- add `@import 'EXT:mail_logger/Configuration/TypoScript/constants.typoscript'` in your constants
- and `@import 'EXT:mail_logger/Configuration/TypoScript/setup.typoscript'` in your setup.

## 1. E-mail logging

The extension automatically log all outgoing mails of the TYPO3 system, which are sent via the TYPO3 mail API. Just install the extension and it works. All outgoing mails can be found in the backend module of this TYPO3 mail logger.

By default the maximum logging time of e-mails is 30 days and can be changed as following:
[see strtotime](http://php.net/manual/en/function.strtotime.php#refsect1-function.strtotime-examples)
The mails will be anonymized after 7 days by default. It can be changed to anonymize directly, by setting anonymizeAfter to 0.
```ts
module.tx_maillogger.settings.cleanup {
  lifetime = 30 days
  anonymize = 1
  anonymizeAfter = 7 days
}
```

## 2. E-mail templates

You can configure TYPO3 e-mail templates, written in Fluid, which are editable from editors (in the database) and configured via TypoScript (in VCS).

*How does this work?*
E-mails will be basically configured in a TypoScript configuration (configuration of the sender address for example).
Afterwards a database entry will be generated from the editor, which extends this template with additional information (fluid template or receiver for example).
The instance of such an e-mail can be extended or overridden afterwards via php in your own extension (for example: dynamic receiver).

### TypoScript example

You always have to create a TypoScript template for a mail. The "label" is the only required field, the orher fields are optional.

```typo3_typoscript
# E-mail template
module.tx_maillogger.settings.mailTemplates {
    exampleMailTemplateKey {
        label = This Label will be shown in the Backend for BE-Users, replace this with a good title! :-)
        mailFromName = Default Mail-From-Name
        mailFromAddress = info@domain.com
        mailToNames = Markus Hoelzle, John Doe
        mailToAddresses = markus-hoelzle@example.com, john.doe@example.com
        mailBlindCopyAddresses = we-read-all-your-mails@example.com
    }
}
```

### E-mail templates in database

E-mail templates will be stored in the database. Just create a record "E-mail template". The TypoScript template will be selected there.
The message will be rendered by Fluid, so it is possible to print variables or use ViewHelpers.

##### Example message: 

```html
<f:format.nl2br>
  Hello,
  
  we have {amount} new purchase keys (see attachment).
  
  This mail was sent automatically by domain.com
</f:format.nl2br>
```

### sending E-mails via PHP

E-mail instances "\\Pluswerk\\MailLogger\\Domain\\Model\\Mail\\TemplateBasedMailMessage" inherit from SwiftMailer class 
"\\Swift\_Message".
Therefor an e-mail instance have got following functions:  <http://swiftmailer.org/docs/messages.html>
The easiest way is to use the functions of the "\\Pluswerk\\MailLogger\\Utility\\MailUtility" class.

##### basic sample:

```php
<?php
use \Pluswerk\MailLogger\Utility\MailUtility;
MailUtility::getMailByKey('exampleMailTemplateKey', null, ['myVariable' => 'This mail was sent at ' . time(), 'myUser' => $myExtbaseUser])->send();
```

#### E-mail template in certain language

```php
<?php
use \Pluswerk\MailLogger\Utility\MailUtility;
MailUtility::getMailByKey('exampleMailTemplateKey', 42, ['myVariable' => 'This mail was sent at ' . time(), 'myUser' => $myExtbaseUser])->send();
```

#### example - passing E-mail parameters and sending attachment (FPDF for example)

```php
<?php
use \Pluswerk\MailLogger\Utility\MailUtility;
try {
    // send mail
    $mail = MailUtility::getMailByKey('exampleMailTemplateKey', null, [
        'amount' => $amount
    ]);
    $pdfFileName = 'myFile.pdf';
    $pdfFileByteStream = $fpdf->Output($pdfFileName, 'S');
    $pdfFileAttachment = \Swift_Attachment::newInstance($pdfFileByteStream, $pdfFileName, 'application/pdf');
    $mail->attach($pdfFileAttachment);
    $mail->send();
} catch (\Exception $e) {
    // handle error
    $this->addFlashMessage('E-Mail could not be sent because of an error: ' . $e->getMessage(), '', AbstractMessage::ERROR);
}
```

You should always catch exceptions in your php code. Experience has shown that editors often don't add a template (or translation) etc.
Corresponding errors should somehow be handled!

### Custom fluid templates
Sometimes you additionally want to wrap the mail templates from database with your own markup.
Therefore we provide the option to customize the mail for your needs via fluid.
Again - via Typoscript - you can configure a rendering definition for every mail template.

```typo3_typoscript
module.tx_maillogger.settings.templateOverrides {
    mytemplatekey {
      title = My Template
      templatePath = EXT:my_ext/Resources/Private/Templates/Mail.html
      partialRootPaths = EXT:my_ext/Resources/Private/Partials/
      layoutRootPaths = EXT:my_ext/Resources/Private/Layouts/
    }
    anothertemplatekey {
      title = Another Template Key
      templatePath = EXT:another_ext/Resources/Private/Templates/Mail.html
      settings {
        myParameter = myValue
      }
    }
  }
}
```

```html
<!-- Fluid example -->
<h2>{mailTemplate.subject}</h2>
<f:format.raw>{message}</f:format.raw>
<p>This is my passed value: {settings.myValue}</p>
```

The Variables "message" and "mailTemplate" are automatically provided to your template.
You can use the actual message by simply wrapping it with a "f:format.raw"-viewhelper.
You can provide your own partial- and layout-paths for every template you add.
Alternatively it will use the default paths provided by this extension.

You can add your own parameters to the template via "settings"-option.

### example - Use a e-mail template in your own plugin

If a mail template can be selected dynamically by the editor, you can integrate a Flexform in the plugin, 
adding the following configuration:

```xml
<settings.userMailTemplate>
    <TCEforms>
        <label>E-mail template</label>
        <config>
            <type>select</type>
            <renderType>selectSingle</renderType>
            <foreign_table>tx_maillogger_domain_model_mailtemplate</foreign_table>
            <foreign_table_where> ORDER BY tx_maillogger_domain_model_mailtemplate.title</foreign_table_where>
            <size>1</size>
            <minitems>1</minitems>
            <maxitems>1</maxitems>
        </config>
    </TCEforms>
</settings.userMailTemplate>
```

### DKIM signing of mails (NOT SUPPORTED IN VERSION 2.0 until now)

You can set a DKIM-signing for every mailtemplate you use for spam protection reasons.
Therefore you have to define typoscript keys which you can select in the backend of a mail template.

Please note that you have to strip "-----BEGIN RSA PRIVATE KEY-----" and "-----END RSA PRIVATE KEY-----", as they are added from php with special chars you don't want to type via typoscript.
So only paste your private keychain as key.

For an example regarding using DKIM signing and adding the TXT-records to your DNS you can visit [this article](https://support.rackspace.com/how-to/create-a-dkim-txt-record/)

Key: Your private key without "-----BEGIN RSA PRIVATE KEY-----" and "-----END RSA PRIVATE KEY-----"
Domain: The domain from which you want to send your mail (e.g. info@example.com)
Selector will most likely remain "default".

```typo3_typoscript
module.tx_maillogger.settings.dkim {
    examplekey {
      key = MYRSAPRIVATEKEY
      domain = example.com
      selector = default
    }
}
```
