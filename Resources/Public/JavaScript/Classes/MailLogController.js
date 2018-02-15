var Pluswerk = Pluswerk || {};
Pluswerk.MailLogger = Pluswerk.MailLogger || {};

Pluswerk.MailLogger.MailLogController = {

  /**
   * init
   */
  init: function () {
    Pluswerk.MailLogger.MailLogController.initLinkListener();
  },


  /**
   * initLinkListener
   */
  initLinkListener: function () {
    $('.maillogger-open-modal').click(function () {
      Pluswerk.MailLogger.MailLogController.loadMailLogModal($(this).attr('href'));
      return false;
    });
  },


  /**
   * loadMailLogModal
   *
   * @param {string} url
   */
  loadMailLogModal: function (url) {
    // @todo: Implement new modal while ajax loading
    //var typo3Modal = Pluswerk.MailLogger.DashboardController.getTYPO3Modal();//,
    //	modal = typo3Modal.template.clone().addClass(typo3Modal.getSeverityClass(TYPO3.Severity.info));
    //modal.find('.modal-content').remove();
    //$('body').append(modal);
    //modal.modal();
    $.ajax({
      url: url,
      success: function (data) {
        data = $('<div />').html(data);
        var title = data.find('h1').clone().html();
        data.find('h1').remove();
        Pluswerk.MailLogger.MailLogController.showMailLogModal(data, title, {
          callback: {
            hidden: function () {
              //modal.modal('hide').remove();
            }
          }
        });
      },
      dataType: 'html'
    });
  },


  /**
   * showMailLogModal
   *
   * @param {string} html
   * @param {string} title
   * @param {object=} options
   */
  showMailLogModal: function (html, title, options) {
    var typo3Modal = Pluswerk.MailLogger.DashboardController.getTYPO3Modal(),
      severity = Pluswerk.MailLogger.DashboardController.getSeverity(),
      buttons = [{
        text: TYPO3.lang['button.ok'] || 'OK',
        btnClass: 'btn-' + typo3Modal.getSeverityClass(severity.info),
        name: 'ok'
      }];
    var $modal = typo3Modal.show(title, html, severity.info, buttons);
    $modal.on('button.clicked', function (e) {
      if (e.target.name === 'ok') {
        $(this).trigger('confirm.button.ok');
        typo3Modal.dismiss();
      }
    });
    $modal.on('shown.bs.modal', function () {
      Pluswerk.MailLogger.DashboardController.initPanelToggler(typo3Modal.currentModal);
      $modal.find('.modal-dialog').css('width', '95%');
      $modal.find('iframe.iframe-content').each(function () {
        var iframe = $(this);
        iframe.contents().find('html').html($modal.find(iframe.data('content')));
      });
    });
    $modal.on('hidden.bs.modal', function () {
      options.callback.hidden();
    });
  }

};
