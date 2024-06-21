define(['jquery', 'TYPO3/CMS/Backend/Modal', 'TYPO3/CMS/Backend/Severity'], function ($, Modal, Severity) {
  return {
    initLinkListener() {
      document.querySelectorAll('a.maillogger-open-modal').forEach(
        el => el.addEventListener('click', (event) => {
          event.preventDefault();
          loadMailLogModal(el.href);
        }),
      );
    },
  };

  /**
   * loadMailLogModal
   *
   * @param {string} url
   */
  async function loadMailLogModal(url) {
    const response = await fetch(url);
    let data = await response.text();
    data = $('<div />').html(data);
    const title = data.find('h1').clone().html();
    data.find('h1').remove();
    showMailLogModal(data, title);
  }

  /**
   * showMailLogModal
   *
   * @param {string} html
   * @param {string} title
   */
  function showMailLogModal(html, title) {
    const typo3Window = (opener != null && typeof opener.top.TYPO3 !== 'undefined' ? opener.top : top);
    const buttonClass = Severity.getCssClass(Severity.info);

    const buttons = [{
      text: typo3Window.TYPO3.lang['button.close'] || 'Close',
      btnClass: 'btn-' + buttonClass,
      name: 'ok',
      trigger(event, modal) {
        if (modal) {
          // TYPO3 12:
          modal.hideModal();
        } else {
          // TYPO3 11:
          Modal.dismiss();
        }
      },
    }];
    /** @var {HTMLElement} modal*/
    let modal = Modal.advanced({
      title,
      content: html,
      severity: Severity.info,
      buttons: buttons,
    });
    if (typeof modal.querySelector !== 'function') {
      // TYPO3 11: modal is jQueryObject
      modal = modal[0];
    }

    const afterModalInitialized = () => {
      modal.querySelector('.t3js-modal-content').style.width = '50%';

      const iframe = modal.querySelector('iframe.iframe-content');
      const iframeDocument = iframe.contentDocument || iframe.contentWindow?.document;
      const modalContent = modal.querySelector(iframe.dataset.content).innerHTML;
      if (iframeDocument && modalContent) {
        iframeDocument.write(modalContent);
      } else {
        setTimeout(afterModalInitialized, 10);
      }
    };
    setTimeout(afterModalInitialized);
  }
});

