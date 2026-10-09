/* --------------------------------------------------------------------------
   share.js — Dibuja el código QR de la página /share en el navegador.
   Usa la librería vendida qrcode.min.js (sin llamadas externas).
   La URL a codificar viene del atributo data-qr-url del contenedor.
   -------------------------------------------------------------------------- */
(function () {
  function render() {
    var box = document.getElementById('jr-qr');
    if (!box || typeof qrcode === 'undefined') return;

    var url = box.getAttribute('data-qr-url') || window.location.origin + '/';

    try {
      // type 0 = versión automática; 'M' = ~15% de corrección de errores,
      // buen balance para impresión y pantalla.
      var qr = qrcode(0, 'M');
      qr.addData(url);
      qr.make();

      // SVG: nítido a cualquier tamaño (ideal para imprimir o acercar).
      // cellSize 1 + margin 0: el tamaño real lo controla el CSS del <svg>.
      box.innerHTML = qr.createSvgTag({ cellSize: 1, margin: 0, scalable: true });

      var svg = box.querySelector('svg');
      if (svg) {
        svg.removeAttribute('width');
        svg.removeAttribute('height');
        svg.setAttribute('role', 'img');
        svg.setAttribute('aria-label', 'Código QR al sitio de JReyes Ópticos');
      }
      box.classList.add('is-ready');
    } catch (e) {
      // Si algo falla, deja un enlace de respaldo visible.
      box.innerHTML = '<a href="' + url + '">' + url + '</a>';
    }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', render);
  } else {
    render();
  }
})();
