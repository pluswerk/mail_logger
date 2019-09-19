var Pluswerk = Pluswerk || {};
Pluswerk.MailLogger = Pluswerk.MailLogger || {};

Pluswerk.MailLogger.MailLogController = {

  /**
   * init
   */
  init: function (jQuery) {
    Pluswerk.MailLogger.MailLogController.initLinkListener(jQuery);
  },


  /**
   * initLinkListener
   */
  initLinkListener: function (jQuery) {
    jQuery('.maillogger-open-modal').click(function () {
      Pluswerk.MailLogger.MailLogController.loadMailLogModal(jQuery(this).attr('href'), jQuery);
      return false;
    });
  },


  /**
   * loadMailLogModal
   *
   * @param {string} url
   */
  loadMailLogModal: function (url, jQuery) {
    // @todo: Implement new modal while ajax loading
    //var typo3Modal = Pluswerk.MailLogger.DashboardController.getTYPO3Modal();//,
    //	modal = typo3Modal.template.clone().addClass(typo3Modal.getSeverityClass(TYPO3.Severity.info));
    //modal.find('.modal-content').remove();
    //$('body').append(modal);
    //modal.modal();
    jQuery.ajax({
      url: url,
      success: function (data) {
        data = jQuery('<div />').html(data);
        var title = data.find('h1').clone().html();
        data.find('h1').remove();
        Pluswerk.MailLogger.MailLogController.showMailLogModal(data, title, {
          callback: {
            hidden: function () {
              //modal.modal('hide').remove();
            }
          }
        }, jQuery);
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
  showMailLogModal: function (html, title, options, jQuery) {
    var typo3Window = (opener != null && typeof opener.top.TYPO3 !== 'undefined' ? opener.top : top);
    var typo3Modal = Pluswerk.MailLogger.DashboardController.getTYPO3Modal();
    var severity = Pluswerk.MailLogger.DashboardController.getSeverity();
    var buttonClass = '';
    if (typeof typo3Window.TYPO3.Severity.getCssClass !== 'undefined') {
      buttonClass = typo3Window.TYPO3.Severity.getCssClass(severity.info);
    } else {
      buttonClass = typo3Modal.getSeverityClass(severity.info);
    }
    var buttons = [{
        text: typo3Window.TYPO3.lang['button.ok'] || 'OK',
        btnClass: 'btn-' + buttonClass,
        name: 'ok'
      }];
    var $modal = typo3Modal.show(title, html, severity.info, buttons);
    $modal.on('button.clicked', function (e) {
      if (e.target.name === 'ok') {
        jQuery(this).trigger('confirm.button.ok');
        typo3Modal.dismiss();
      }
    });
    $modal.on('shown.bs.modal', function () {
      Pluswerk.MailLogger.DashboardController.initPanelToggler(jQuery, typo3Modal.currentModal);
      $modal.find('.modal-dialog').css('width', '95%');
      $modal.find('iframe.iframe-content').each(function () {
        var iframe = jQuery(this);
        iframe.contents().find('html').html($modal.find(iframe.data('content')));
      });
    });
    $modal.on('hidden.bs.modal', function () {
      options.callback.hidden();
    });
  }

};
