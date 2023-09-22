require(
  [
    'jquery',
    'TYPO3/CMS/MailLogger/MailLogModal',
  ],
  function ($, MailLogModal) {
    $(document).ready(() => {
      MailLogModal.initLinkListener(document);
    });
  });
