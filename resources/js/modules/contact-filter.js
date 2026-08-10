/* --------------------------------------------------------------------------
   contact-filter.js — Filtra el catálogo de lentes de contacto por marca
   y por tipo (esférico, tórico, multifocal, color) sin recargar la página.
   Cada tarjeta funciona además como acordeón independiente: al tocarla se
   abre y muestra una imagen y el botón para pedir por WhatsApp.
   -------------------------------------------------------------------------- */

export function initContactFilter() {
  const root = document.getElementById('jr-contact-catalog');
  if (!root) return;

  const filters = root.querySelectorAll('[data-filter]');
  const rows = root.querySelectorAll('[data-cl-row]');
  const groups = root.querySelectorAll('[data-brand-group]');
  const emptyEl = root.querySelector('[data-empty]');

  const state = { brand: 'all', tipo: 'all' };

  function apply() {
    let visible = 0;

    rows.forEach((row) => {
      const matchBrand = state.brand === 'all' || row.dataset.brand === state.brand;
      const matchTipo = state.tipo === 'all' || row.dataset.tipo === state.tipo;
      const show = matchBrand && matchTipo;
      row.classList.toggle('d-none', !show);
      if (show) visible++;
    });

    // Ocultar encabezados de marca sin resultados visibles
    groups.forEach((group) => {
      const anyVisible = group.querySelectorAll('[data-cl-row]:not(.d-none)').length > 0;
      group.classList.toggle('d-none', !anyVisible);
    });

    if (emptyEl) emptyEl.classList.toggle('d-none', visible > 0);
  }

  filters.forEach((btn) => {
    btn.addEventListener('click', () => {
      const type = btn.dataset.filterType; // 'brand' | 'tipo'
      const value = btn.dataset.filter;

      root.querySelectorAll(`[data-filter-type="${type}"]`).forEach((b) => b.classList.remove('active'));
      btn.classList.add('active');

      state[type] = value;
      apply();
    });
  });

  // Acordeón: cada tarjeta abre/cierra de forma independiente (varias a la vez).
  root.querySelectorAll('[data-cl-toggle]').forEach((head) => {
    head.addEventListener('click', () => {
      const panel = document.getElementById(head.getAttribute('aria-controls'));
      if (!panel) return;
      const isOpen = head.getAttribute('aria-expanded') === 'true';
      head.setAttribute('aria-expanded', String(!isOpen));
      panel.hidden = isOpen;
      head.closest('.jr-cl-item')?.classList.toggle('is-open', !isOpen);
    });
  });

  apply();
}