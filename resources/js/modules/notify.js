/* --------------------------------------------------------------------------
   notify.js — Notificaciones tipo toast reutilizables.
   -------------------------------------------------------------------------- */

function ensureContainer() {
  let el = document.getElementById('jr-toast-container');
  if (!el) {
    el = document.createElement('div');
    el.id = 'jr-toast-container';
    el.style.cssText = 'position:fixed;top:1rem;right:1rem;z-index:1080;display:flex;flex-direction:column;gap:.5rem;max-width:340px;';
    document.body.appendChild(el);
  }
  return el;
}

/**
 * Muestra una notificación.
 * @param {string} message
 * @param {'success'|'error'|'info'} type
 * @param {number} duration ms
 */
export function toast(message, type = 'info', duration = 4000) {
  const container = ensureContainer();
  const colors = {
    success: { bg: 'rgba(251,203,102,.14)', border: '#e0ac3f', text: '#fddc95', icon: 'fa-circle-check' },
    error:   { bg: 'rgba(245,34,40,.14)',  border: '#f52228', text: '#ff8a8f', icon: 'fa-circle-exclamation' },
    info:    { bg: '#221d19',              border: '#3a322b', text: '#f4efe9', icon: 'fa-circle-info' },
  };
  const c = colors[type] || colors.info;

  const el = document.createElement('div');
  el.setAttribute('role', 'status');
  el.style.cssText = `background:${c.bg};border:1px solid ${c.border};color:${c.text};padding:.85rem 1rem;border-radius:8px;box-shadow:0 8px 24px rgba(0,0,0,.4);display:flex;gap:.6rem;align-items:flex-start;font-weight:600;opacity:0;transform:translateX(12px);transition:opacity .25s ease,transform .25s ease;`;
  el.innerHTML = `<i class="fa-solid ${c.icon}" style="margin-top:.15rem"></i><span>${message}</span>`;
  container.appendChild(el);

  requestAnimationFrame(() => { el.style.opacity = '1'; el.style.transform = 'none'; });

  setTimeout(() => {
    el.style.opacity = '0';
    el.style.transform = 'translateX(12px)';
    setTimeout(() => el.remove(), 300);
  }, duration);
}
