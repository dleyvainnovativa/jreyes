/* --------------------------------------------------------------------------
   theme.js — Cambio de tema claro/oscuro.

   El tema inicial ya lo fija un script en línea en el <head> (antes de pintar,
   para evitar parpadeo), leyendo localStorage o, si no hay elección guardada,
   la preferencia del sistema (prefers-color-scheme). Aquí solo se maneja el
   botón de cambio, la persistencia y el seguimiento del sistema.
   -------------------------------------------------------------------------- */

const STORAGE_KEY = 'jr-theme';

export function initTheme() {
  const root = document.documentElement;

  const current = () =>
    root.getAttribute('data-theme') === 'light' ? 'light' : 'dark';

  const read = () => {
    try { return localStorage.getItem(STORAGE_KEY); } catch (e) { return null; }
  };
  const save = (value) => {
    try { localStorage.setItem(STORAGE_KEY, value); } catch (e) { /* modo privado */ }
  };

  // Mantiene en sincronía el estado accesible de todos los botones de cambio.
  const syncButtons = () => {
    const isLight = current() === 'light';
    document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
      btn.setAttribute('aria-pressed', String(isLight));
      const label = isLight ? 'Cambiar a tema oscuro' : 'Cambiar a tema claro';
      btn.setAttribute('aria-label', label);
      btn.setAttribute('title', label);
    });
  };

  const apply = (theme) => {
    root.setAttribute('data-theme', theme);
    syncButtons();
  };

  syncButtons();

  // Clic en cualquier botón de cambio de tema.
  document.querySelectorAll('[data-theme-toggle]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const next = current() === 'light' ? 'dark' : 'light';
      apply(next);
      save(next);
    });
  });

  // Si el usuario no ha elegido manualmente, seguir los cambios del sistema.
  if (window.matchMedia) {
    const mq = window.matchMedia('(prefers-color-scheme: light)');
    const onChange = (e) => {
      if (read()) return; // respeta la elección manual guardada
      apply(e.matches ? 'light' : 'dark');
    };
    if (mq.addEventListener) mq.addEventListener('change', onChange);
    else if (mq.addListener) mq.addListener(onChange); // navegadores antiguos
  }
}
