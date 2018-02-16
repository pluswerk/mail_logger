[![Packagist Release](https://img.shields.io/packagist/v/pluswerk/mail-logger.svg?style=flat-square)](https://packagist.org/packages/pluswerk/mail-logger)
[![Travis](https://img.shields.io/travis/pluswerk/mail_logger.svg?style=flat-square)](https://travis-ci.org/pluswerk/mail_logger)
[![GitHub License](https://img.shields.io/github/license/pluswerk/mail_logger.svg?style=flat-square)](https://github.com/pluswerk/mail_logger/blob/master/LICENSE.txt)
[![Code Climate](https://img.shields.io/codeclimate/github/pluswerk/mail_logger.svg?style=flat-square)](https://codeclimate.com/github/pluswerk/mail_logger)
[![Build Status](https://travis-ci.org/pluswerk/mail_logger.svg?branch=master)](https://travis-ci.org/pluswerk/mail_logger)

# TYPO3 extension: e-mail log

E-mails will be basically configured as template in TypoScript (for example sender).
Afterwards a database entry will be generated, which extends this template for additional information (like fluid template).
The instance of such an e-mail can be extended or overridden afterwards via php (for example: dynamic receiver).

### TypoScript example

You always have to create a TypoScript template for a mail. The "label" is the only required field.

```typo3_typoscript
# E-mail template
module.tx_maillogger.settings.mailTemplates {
    exampleMailTemplateKey {
        label = This Label will be shown in the Backend for BE-Users, REPLACE this with a good title! :-)
        mailFromName = Default Mail-From-Name
        mailFromAddress = info@domain.com
        mailToNames = Markus Hoelzle,ABC
        mailToAddresses = m.hoelzle@pluswerk.ag,a@b.de
        mailBlindCopyAddresses = tech@pluswerk.ag
    }
}
```

### E-mail templates in database

E-mail templates will be stored in the database. There the TypoScript template will be selected.
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

E-mail instances "\\Pluswerk\\MailLogger\\Domain\\Model\\Mail\\TemplateBasedMailMessage" inherit SwiftMailer Class 
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

#### example - passing E-mail parameters and sending attachment (FPDF)

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

You should always catch exceptions. Experience has shown that editors often don't add a template (or translation) etc.
Corresponding errors should somehow be handled!

### example - configuration of e-mail template

If a mail template can be selected dynamically by the editor, you can integrate a Flexform in the plugin, 
adding the following configuration:

```xml
<settings.userMailTemplate>
    <TCEforms>
        <label>E-mail template</label>
        <config>
            <type>select</type>
            <foreign_table>tx_maillogger_domain_model_mail_mailtemplate</foreign_table>
            <foreign_table_where> ORDER BY tx_maillogger_domain_model_mail_mailtemplate.title</foreign_table_where>
            <size>1</size>
            <minitems>1</minitems>
            <maxitems>1</maxitems>
        </config>
    </TCEforms>
</settings.userMailTemplate>
```

### E-mail debug

All emails can be viewed in the backend module.
Alternatively, all e-mails can be redirected to a specific e-mail address via this TypoScript setting:

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
