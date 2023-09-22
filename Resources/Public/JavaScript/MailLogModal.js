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
        modal.hideModal();
      },
    }];
    /** @var {HTMLElement} modal*/
    const modal = Modal.advanced({
      title,
      content: html,
      severity: Severity.info,
      buttons: buttons,
    });
    setTimeout(() => {
      modal.querySelector('.t3js-modal-content').style.width = '50%';

      const iframe = modal.querySelector('iframe.iframe-content');
      const iframeDocument = iframe.contentDocument || iframe.contentWindow.document;
      iframeDocument.querySelector('html').innerHTML = modal.querySelector(iframe.dataset.content).innerHTML;
    });
  }
});

