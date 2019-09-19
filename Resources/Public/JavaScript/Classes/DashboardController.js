var Pluswerk = Pluswerk || {};
Pluswerk.MailLogger = Pluswerk.MailLogger || {};

Pluswerk.MailLogger.DashboardController = {

  /**
   * init
   */
  init: function (jQuery) {
    Pluswerk.MailLogger.DashboardController.initPanelToggler(jQuery);
  },


  /**
   * initPanelToggler
   *
   * @param {jQuery=} $element
   */
  initPanelToggler: function (jQuery, $element) {
    if (typeof $element == 'undefined') {
      $element = jQuery(document);
    }
    $element.find(".panel-heading").unbind('click').on('click', function () {
      var $panelHeading = jQuery(this);
      $panelHeading.parent().find("> .maillogger-panel-body").toggle();
    });
  },

  /**
   * @returns {TYPO3.Modal}
   */
  getTYPO3Modal: function () {
    var typo3Modal = TYPO3.Modal;
    if (!typo3Modal && parent && parent.window.TYPO3 && parent.window.TYPO3.Modal) {
      typo3Modal = parent.window.TYPO3.Modal;
    }
    return typo3Modal;
  },


  /**
   * @returns {{notice: number, information: number, info: number, ok: number, warning: number, error: number}}
   */
  getSeverity: function () {
    var severity = TYPO3.Severity;
    if (typeof severity === "undefined") {
      severity = {
        notice: -2,
        information: -1,
        info: -1,
        ok: 0,
        warning: 1,
        error: 2
      };
    }
    return severity;
  }

};
