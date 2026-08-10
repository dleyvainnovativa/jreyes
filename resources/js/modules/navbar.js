/* --------------------------------------------------------------------------
   navbar.js — Efecto de la barra de navegación al hacer scroll.
   -------------------------------------------------------------------------- */

export function initNavbar() {
  const nav = document.querySelector('.jr-navbar');
  if (!nav) return;

  const onScroll = () => {
    nav.style.boxShadow = window.scrollY > 20 ? '0 6px 20px rgba(0,0,0,.35)' : 'none';
  };
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });
}
