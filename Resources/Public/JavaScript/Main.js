{
  var $ = TYPO3.jQuery;
  $(document).ready(function () {
    require.config({
      paths: {
        mailLogger: '../typo3conf/ext/mail_logger/Resources/Public/JavaScript/',
      }
    });

    require(["mailLogger/Classes/MailLogController"], function () {
      Pluswerk.MailLogger.MailLogController.init();
    });

    require(["mailLogger/Classes/DashboardController"], function () {
      Pluswerk.MailLogger.DashboardController.init();
    });

  });
}
