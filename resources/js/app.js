/* ==========================================================================
   JReyes Ópticos — app.js (punto de entrada)
   Arquitectura JS modular y reutilizable en vanilla JavaScript.
   ========================================================================== */

import 'bootstrap';

import * as http from './modules/http.js';
import { toast } from './modules/notify.js';
import { initReveal } from './modules/reveal.js';
import { initNavbar } from './modules/navbar.js';
import { initTheme } from './modules/theme.js';
import { initConfigurator } from './modules/configurator.js';
import { initContactFilter } from './modules/contact-filter.js';
import { initContactGrad } from './modules/contact-grad.js';

// Exponer helpers globalmente por comodidad en vistas Blade puntuales.
window.JR = { http, toast };

document.addEventListener('DOMContentLoaded', () => {
  initTheme();
  initNavbar();
  initReveal();
  initConfigurator();
  initContactFilter();
  initContactGrad();
});
