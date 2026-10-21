/* --------------------------------------------------------------------------
   contact-grad.js — Formulario de contacto principal.
   · Muestra/oculta el bloque de graduación según «¿Conoces tu graduación?».
   · Botón ± para poner negativos en móvil (teclado decimal sin tecla menos).
   -------------------------------------------------------------------------- */

export function initContactGrad() {
  const form = document.querySelector('[data-contact-form]');
  if (!form) return;

  // Mostrar/ocultar el bloque de graduación.
  const bloque = form.querySelector('[data-grad-contact]');
  const radios = form.querySelectorAll('input[name="conoce_graduacion"]');

  const sincronizar = () => {
    const sel = form.querySelector('input[name="conoce_graduacion"]:checked');
    const si = sel && sel.value === '1';
    if (bloque) bloque.hidden = ! si;
  };
  radios.forEach((r) => r.addEventListener('change', sincronizar));
  sincronizar();

  // Botón ± : alterna el signo del campo.
  form.querySelectorAll('[data-grad-sign]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const input = btn.parentElement.querySelector('input');
      if (! input) return;
      const v = input.value.trim();
      input.value = v.startsWith('-') ? v.slice(1) : '-' + v;
      input.focus();
    });
  });
}
