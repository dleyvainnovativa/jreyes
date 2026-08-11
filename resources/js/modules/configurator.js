/* --------------------------------------------------------------------------
   configurator.js — "Arma tus lentes".
   Lee los datos del catálogo desde un <script type="application/json"> y
   calcula el precio en vivo. No hace peticiones al backend: los precios son
   estáticos y vienen serializados desde el controlador.

   Pasos: 1) tipo  2) diseño/material  3) tratamiento (opcional)
          4) extra aditivo, p. ej. fotocromático (opcional)

   Además arma un mensaje de WhatsApp con la selección y actualiza en vivo
   la tarjeta de resumen (imagen + descripciones apiladas).
   -------------------------------------------------------------------------- */

const MXN = new Intl.NumberFormat('es-MX', {
  style: 'currency',
  currency: 'MXN',
  minimumFractionDigits: 0,
  maximumFractionDigits: 0,
});

export function initConfigurator() {
  const root = document.getElementById('jr-configurator');
  if (!root) return;

  const dataEl = document.getElementById('jr-config-data');
  if (!dataEl) return;

  let data;
  try {
    data = JSON.parse(dataEl.textContent);
  } catch (e) {
    return;
  }

  const state = { armazon: null, tipo: null, diseno: null, tratamiento: null, extra: null };

  // Imagen mostrada en el resumen. "Gana" la última selección que tenga
  // imagen propia; si el elemento elegido no trae imagen, se conserva la
  // anterior (no se vuelve al logo a media configuración).
  let lastImg = null;

  const tipoWrap = root.querySelector('[data-step="tipo"]');
  const armazonWrap = root.querySelector('[data-step="armazon"]');
  const disenoWrap = root.querySelector('[data-step="diseno"]');
  const tratWrap = root.querySelector('[data-step="tratamiento"]');
  const extraWrap = root.querySelector('[data-step="extra"]');
  const priceEl = root.querySelector('[data-total]');
  const breakdownEl = root.querySelector('[data-breakdown]');
  const waBtn = root.querySelector('[data-wa-config]');

  // Tarjeta de resumen (columna derecha, fuera de #jr-configurator)
  const summary = document.getElementById('jr-config-summary');
  const summaryImg = summary?.querySelector('[data-summary-img]');
  const summaryEmpty = summary?.querySelector('[data-summary-empty]');
  const summaryDetail = summary?.querySelector('[data-summary-detail]');
  const summaryList = summary?.querySelector('[data-summary-list]');
  const defaultImg = data.logo;

  function chip(label, sublabel, onClick, group) {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'jr-chip';
    btn.setAttribute('aria-pressed', 'false');
    btn.dataset.group = group;
    btn.innerHTML = sublabel ? `${label}<small>${sublabel}</small>` : label;
    btn.addEventListener('click', () => onClick(btn));
    return btn;
  }

  function clearPressed(container) {
    container.querySelectorAll('.jr-chip').forEach((c) => c.setAttribute('aria-pressed', 'false'));
  }

  // Paso 1 — armazón (obligatorio). Cuadrícula de miniaturas.
  if (armazonWrap && Array.isArray(data.armazones)) {
    data.armazones.forEach((f) => {
      const cell = document.createElement('button');
      cell.type = 'button';
      cell.className = 'jr-frame';
      cell.setAttribute('role', 'option');
      cell.setAttribute('aria-selected', 'false');
      cell.setAttribute('aria-label', f.nombre);
      cell.innerHTML = `
        <span class="jr-frame__media"><img src="${f.imagen}" alt="${f.nombre}" loading="lazy"></span>
        <span class="jr-frame__label">${f.nombre}</span>`;
      cell.addEventListener('click', () => {
        armazonWrap.querySelectorAll('.jr-frame').forEach((c) => c.setAttribute('aria-selected', 'false'));
        cell.setAttribute('aria-selected', 'true');
        state.armazon = f;
        if (f.imagen) lastImg = f.imagen; // el armazón ancla la vista previa
        recalc();
      });
      armazonWrap.appendChild(cell);
    });
  }

  // Paso 2 — tipo de lente
  data.tipos.forEach((tipo) => {
    const btn = chip(tipo.nombre, null, () => {
      clearPressed(tipoWrap);
      btn.setAttribute('aria-pressed', 'true');
      state.tipo = tipo;
      state.diseno = null;
      // al cambiar de tipo, la vista previa vuelve al armazón elegido (o al logo)
      lastImg = state.armazon ? state.armazon.imagen : null;
      renderDesigns(tipo);
      recalc();
    }, 'tipo');
    tipoWrap.appendChild(btn);
  });

  // Paso 2 — diseño / material (depende del tipo)
  function renderDesigns(tipo) {
    disenoWrap.innerHTML = '';
    tipo.disenos.forEach((d) => {
      const sub = d.material + (d.premium ? ' · Premium' : '');
      const btn = chip(d.nombre, sub, () => {
        clearPressed(disenoWrap);
        btn.setAttribute('aria-pressed', 'true');
        state.diseno = d;
        if (d.imagen) lastImg = d.imagen;
        recalc();
      }, 'diseno');
      disenoWrap.appendChild(btn);
    });
    root.querySelector('[data-diseno-empty]')?.classList.add('d-none');
  }

  // Paso 3 — tratamiento (opcional)
  const noneTrat = chip('Sin tratamiento', 'Solo la mica', (btn) => {
    clearPressed(tratWrap);
    btn.setAttribute('aria-pressed', 'true');
    state.tratamiento = null;
    recalc();
  }, 'trat');
  tratWrap.appendChild(noneTrat);

  data.tratamientos.forEach((t) => {
    const btn = chip(t.nombre, `+ ${MXN.format(t.precio)}`, () => {
      clearPressed(tratWrap);
      btn.setAttribute('aria-pressed', 'true');
      state.tratamiento = t;
      if (t.imagen) lastImg = t.imagen;
      recalc();
    }, 'trat');
    tratWrap.appendChild(btn);
  });

  // Paso 4 — extra aditivo (opcional)
  const noneExtra = chip('Sin extra', 'Ninguno', (btn) => {
    clearPressed(extraWrap);
    btn.setAttribute('aria-pressed', 'true');
    state.extra = null;
    recalc();
  }, 'extra');
  extraWrap.appendChild(noneExtra);

  (data.extras || []).forEach((t) => {
    const btn = chip(t.nombre, `+ ${MXN.format(t.precio)}`, () => {
      clearPressed(extraWrap);
      btn.setAttribute('aria-pressed', 'true');
      state.extra = t;
      if (t.imagen) lastImg = t.imagen;
      recalc();
    }, 'extra');
    extraWrap.appendChild(btn);
  });

  // --- Resumen en vivo -----------------------------------------------------
  function summaryRow(eyebrow, nombre, descripcion, precio) {
    const price = precio != null ? `<span class="jr-text-gold fw-semibold ms-2">${MXN.format(precio)}</span>` : '';
    const desc = descripcion ? `<p class="jr-text-muted small mb-0 mt-1">${descripcion}</p>` : '';
    return `
      <div>
        <p class="jr-eyebrow mb-1">${eyebrow}</p>
        <div class="d-flex align-items-baseline justify-content-between">
          <span class="fw-semibold">${nombre}</span>${price}
        </div>
        ${desc}
      </div>`;
  }

  function renderSummary() {
    if (!summary) return;
    const anySelection = state.armazon || state.tipo || state.diseno || state.tratamiento || state.extra;

    // Imagen: gana la última selección con imagen propia; si aún no hay
    // ninguna, muestra el logo. Al reiniciar (sin selección) vuelve al logo.
    const targetImg = anySelection ? (lastImg || defaultImg) : defaultImg;
    if (summaryImg && summaryImg.getAttribute('src') !== targetImg) {
      summaryImg.style.opacity = '0';
      setTimeout(() => {
        summaryImg.src = targetImg;
        summaryImg.style.opacity = '1';
      }, 150);
    }

    if (!anySelection) {
      summaryEmpty?.classList.remove('d-none');
      summaryDetail?.classList.add('d-none');
      return;
    }

    summaryEmpty?.classList.add('d-none');
    summaryDetail?.classList.remove('d-none');

    const rows = [];
    if (state.armazon) rows.push(summaryRow('Armazón', state.armazon.nombre, 'Armazón incluido.', null));
    if (state.tipo) rows.push(summaryRow('Tipo', state.tipo.nombre, state.tipo.descripcion, null));
    if (state.diseno) rows.push(summaryRow('Diseño', `${state.diseno.nombre} · ${state.diseno.material}`, state.diseno.descripcion, state.diseno.precio));
    if (state.tratamiento) rows.push(summaryRow('Tratamiento', state.tratamiento.nombre, state.tratamiento.descripcion, state.tratamiento.precio));
    if (state.extra) rows.push(summaryRow('Extra', state.extra.nombre, state.extra.descripcion, state.extra.precio));
    if (summaryList) summaryList.innerHTML = rows.join('');
  }

  // --- Mensaje de WhatsApp -------------------------------------------------
  function buildWhatsAppUrl(total) {
    const numero = (data.whatsapp || '').replace(/\D/g, '');
    const l = [];
    l.push('¡Hola! Quiero cotizar unos lentes con esta configuración:');
    if (state.armazon) l.push(`• Armazón: ${state.armazon.nombre} (incluido)`);
    if (state.tipo) l.push(`• Tipo: ${state.tipo.nombre}`);
    if (state.diseno) l.push(`• Diseño: ${state.diseno.nombre} (${state.diseno.material}) — ${MXN.format(state.diseno.precio)}`);
    if (state.tratamiento) l.push(`• Tratamiento: ${state.tratamiento.nombre} — ${MXN.format(state.tratamiento.precio)}`);
    if (state.extra) l.push(`• Extra: ${state.extra.nombre} — ${MXN.format(state.extra.precio)}`);
    l.push(`Total estimado (micas): ${MXN.format(total)}`);
    const texto = encodeURIComponent(l.join('\n'));
    return numero ? `https://wa.me/${numero}?text=${texto}` : `https://wa.me/?text=${texto}`;
  }

  function recalc() {
    const micaPrecio = state.diseno ? state.diseno.precio : 0;
    const tratPrecio = state.tratamiento ? state.tratamiento.precio : 0;
    const extraPrecio = state.extra ? state.extra.precio : 0;
    const total = micaPrecio + tratPrecio + extraPrecio;

    priceEl.textContent = MXN.format(total);

    // Desglose corto bajo el precio
    if (!state.armazon) {
      breakdownEl.textContent = 'Elige un armazón para comenzar.';
    } else if (!state.diseno) {
      breakdownEl.textContent = 'Elige el tipo y el diseño para ver tu precio.';
    } else {
      const lineas = [`Armazón incluido`, `Mica ${MXN.format(micaPrecio)}`];
      if (state.tratamiento) lineas.push(`Tratamiento ${MXN.format(tratPrecio)}`);
      if (state.extra) lineas.push(`Extra ${MXN.format(extraPrecio)}`);
      breakdownEl.innerHTML = '<span class="jr-text-muted">' + lineas.join(' + ') + '</span>';
    }

    // Botón de WhatsApp: activo solo con armazón y diseño elegidos.
    if (waBtn) {
      if (state.armazon && state.diseno) {
        waBtn.href = buildWhatsAppUrl(total);
        waBtn.classList.remove('disabled');
        waBtn.removeAttribute('aria-disabled');
      } else {
        waBtn.href = '#';
        waBtn.classList.add('disabled');
        waBtn.setAttribute('aria-disabled', 'true');
      }
    }

    renderSummary();
  }

  recalc();
}
