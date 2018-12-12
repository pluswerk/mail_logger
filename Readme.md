[![Packagist Release](https://img.shields.io/packagist/v/pluswerk/mail-logger.svg?style=flat-square)](https://packagist.org/packages/pluswerk/mail-logger)
[![Travis](https://img.shields.io/travis/pluswerk/mail_logger.svg?style=flat-square)](https://travis-ci.org/pluswerk/mail_logger)
[![GitHub License](https://img.shields.io/github/license/pluswerk/mail_logger.svg?style=flat-square)](https://github.com/pluswerk/mail_logger/blob/master/LICENSE.txt)
[![Code Climate](https://img.shields.io/codeclimate/github/pluswerk/mail_logger.svg?style=flat-square)](https://codeclimate.com/github/pluswerk/mail_logger)
[![Build Status](https://travis-ci.org/pluswerk/mail_logger.svg?branch=master)](https://travis-ci.org/pluswerk/mail_logger)

# +Pluswerk TYPO3 extension: Mail Logger

This is an TYPO3 extension with three mail functions:
1. [E-mail logging](#1-e-mail-logging)
2. [E-mail templates](#2-e-mail-templates)
3. [E-mail debugging](#3-e-mail-debugging)

## Extension installation

Just copy the files into the TYPO3 extension folder, for example by using composer:

```Shell
composer require pluswerk/mail-logger
```

Go to the TYPO3 backend, activate the extension and add the TypoScript to the page template.
Now everything is set-up and ready for you to create your own mailing settings in TypoScript.

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

## 3. E-mail debugging

All emails can be viewed in the backend module.
Alternatively, all e-mails can be redirected to a specific e-mail address via this TypoScript setting.
This can be used to debug outgoing mails in TYPO3:

```typo3_typoscript
module.tx_maillogger.settings.debug {
    # Redirect all mails from mail_logger to specific mail addresses
    mail {
        # Set enable to '1' to enable this mail debug feature
        enable = 0

        # Specify your ip (comma separated) or set to all '*'
        ip = 127.0.0.1, 123.123.123.123
        #ip = *

        # Set the mail addresses (comma separated) to which the mails should be redirected
        mailRedirect = test@domain.com, a@b.de
    }
}
```
