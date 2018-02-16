[![Packagist Release](https://img.shields.io/packagist/v/pluswerk/mail-logger.svg?style=flat-square)](https://packagist.org/packages/pluswerk/mail-logger)
[![GitHub License](https://img.shields.io/github/license/pluswerk/mail_logger.svg?style=flat-square)](https://github.com/pluswerk/mail_logger/blob/master/LICENSE.txt)
[![Code Climate](https://img.shields.io/codeclimate/github/pluswerk/mail_logger.svg?style=flat-square)](https://codeclimate.com/github/pluswerk/mail_logger)
[![Build Status](https://travis-ci.org/pluswerk/mail_logger.svg?branch=master)](https://travis-ci.org/pluswerk/mail_logger)

# TYPO3 Extension: Mail Logger

E-Mails werden grundsätzlich als Vorlage im TypoScript konfiguriert (z.B. Absender). Dann wird ein Datenbank-Eintrag erstellt, der dieser Vorlage weitere Daten hinzufügt oder überschreibt (z.B. Fluid-Template). Die Instanz einer solchen Mail kann dann nochmals in PHP mit Daten ergänzt oder überschrieben werden (z.B. dynamischer Empfänger).

### TypoScript Vorlage

Es muss immer eine TypoScript-Vorlage für eine Mail erstellt werden. Das "label" ist das einzige Pflichtfeld.

```typescript
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

### E-Mail Templates Datenbank

E-Mail Templates werden in der Datenbank angelegt. Dort wird die TypoScript-Vorlage ausgewählt.
Die "Message" wird mit Fluid gerendet. Hier ist es also möglich, Variablen auszulesen oder ViewHelper zu verwenden. Beispiel "Message":

```html
<f:format.nl2br>
Hello,

we have {amount} new purchase keys (see attachment).

This mail was sent automatically by domain.com
</f:format.nl2br>
```

### E-Mail per PHP versenden

E-Mail Objekte "\\Pluswerk\\MailLogger\\Domain\\Model\\Mail\\TemplateBasedMailMessage" erben nun von der TYPO3 Klasse "\\TYPO3\\CMS\\Core\\Mail\\MailMessage", welche von der SwiftMailer Klasse "\\Swift\_Message" erben.
Daher hat das E-Mail Objekt folgende Funktionen: <http://swiftmailer.org/docs/messages.html>
Am einfachsten verwendet man die Funktionen der PHP-Klasse "\\Pluswerk\\MailLogger\\Utility\\MailUtility".

#### Einfaches Beispiel

```php
<?php
use \Pluswerk\MailLogger\Utility\MailUtility;
MailUtility::getMailByKey('exampleMailTemplateKey', null, ['myVariable' => 'This mail was sent at ' . time(), 'myUser' => $myExtbaseUser])->send();
```

#### E-Mail Template in bestimmter Sprache

```php
<?php
use \Pluswerk\MailLogger\Utility\MailUtility;
MailUtility::getMailByKey('exampleMailTemplateKey', 42, ['myVariable' => 'This mail was sent at ' . time(), 'myUser' => $myExtbaseUser])->send();
```

#### Beispiel PHP: E-Mail Parameter übergeben und Anhang (FPDF) senden

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

Es sollten dabei immer die Fehler gefangen werden. Die Erfahrung hat gezeigt, dass Redakteure häufig kein Template pflegen (oder nicht übersetzten) oder ähnliches. Entsprechende Fehler sollten irgendwie gehändelt werden!

### Beispiel - Konfiguration Mail-Template

Soll ein Mail-Template dynamisch vom Redakteur ausgewählt werden können, kann man im Plugin eine Flexform einbinden, mit folgender Konfiguration

```xml
<settings.userMailTemplate>
    <TCEforms>
        <label>E-Mail Vorlage</label>
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

### E-Mail Debug

Alle E-Mails können im Backend-Modul betrachtet werden.
Alternativ können alle E-Mails über eine TypoScript-Einstellung an eine bestimmte E-Mail Adresse umgeleitet werden:

```typescript
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
