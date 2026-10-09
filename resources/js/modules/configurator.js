/* --------------------------------------------------------------------------
   configurator.js — "Arma tus lentes".

   Recorre un ÁRBOL de catálogo (profundidad variable) servido como JSON:
     armazón (paso fijo 1) → nivel → material/diseño → ... → tratamiento

   Cada tipo de lente tiene su propia profundidad (bifocal añade un paso de
   diseño). No hay lógica especial por tipo: el configurador simplemente
   muestra los HIJOS del nodo elegido en cada paso. Cuando el nodo elegido
   no tiene hijos, es una hoja (tratamiento) y la configuración termina.

   El total suma solo los nodos con precio > 0; los de precio 0 se muestran
   como "Precio en tienda" y no bloquean.

   El resumen muestra UNA IMAGEN POR CADA SELECCIÓN que tenga imagen propia
   (galería apilada), no una sola imagen.
   -------------------------------------------------------------------------- */

const MXN = new Intl.NumberFormat('es-MX', {
  style: 'currency',
  currency: 'MXN',
  minimumFractionDigits: 0,
  maximumFractionDigits: 0,
});

// Etiqueta legible del paso según el "kind" del nodo.
const KIND_LABEL = {
  nivel: 'Calidad',
  diseno: 'Diseño',
  material: 'Material',
  linea: 'Línea',
  tratamiento: 'Tratamiento',
};

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

  const armazonWrap = root.querySelector('[data-step="armazon"]');
  const stepsWrap = root.querySelector('[data-dynamic-steps]');
  const priceEl = root.querySelector('[data-total]');
  const breakdownEl = root.querySelector('[data-breakdown]');
  const waBtn = root.querySelector('[data-wa-config]');

  // Resumen
  const summary = document.getElementById('jr-config-summary');
  const gallery = summary?.querySelector('[data-summary-gallery]');
  const placeholder = summary?.querySelector('[data-summary-placeholder]');
  const summaryEmpty = summary?.querySelector('[data-summary-empty]');
  const summaryDetail = summary?.querySelector('[data-summary-detail]');
  const summaryList = summary?.querySelector('[data-summary-list]');

  // Estado: armazón + una cadena de nodos elegidos (uno por nivel del árbol).
  const state = {
    armazon: null,
    path: [], // [{nodo, stepIndex}] en orden de profundidad
  };

  /* ---------------------------------------------------------------------- */
  /* Paso 1 — Armazón (obligatorio)                                         */
  /* ---------------------------------------------------------------------- */
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
        recalc();
      });
      armazonWrap.appendChild(cell);
    });
  }

  /* ---------------------------------------------------------------------- */
  /* Pasos dinámicos: uno por nivel de profundidad del árbol                */
  /* ---------------------------------------------------------------------- */

  // Número de paso mostrado (el armazón es el 1).
  const stepNumberBase = 2;

  // Crea (o reutiliza) el contenedor de un paso en la profundidad dada.
  function ensureStepBlock(depth, kind) {
    let block = stepsWrap.querySelector(`[data-depth="${depth}"]`);
    if (!block) {
      block = document.createElement('div');
      block.className = 'jr-config__step';
      block.dataset.depth = String(depth);
      block.innerHTML = `
        <div class="jr-config__label mb-3">
          <span class="jr-config__num">${stepNumberBase + depth}</span>
          <span data-step-title></span>
        </div>
        <div class="jr-chips" data-step-chips></div>`;
      stepsWrap.appendChild(block);
    }
    block.querySelector('[data-step-title]').textContent = KIND_LABEL[kind] || 'Opción';
    return block;
  }

  // Elimina los pasos con profundidad > depth (cuando se cambia una elección
  // más arriba, los pasos inferiores dejan de tener sentido).
  function pruneStepsBelow(depth) {
    stepsWrap.querySelectorAll('.jr-config__step').forEach((b) => {
      if (Number(b.dataset.depth) > depth) b.remove();
    });
    state.path = state.path.slice(0, depth + 1);
  }

  function chip(nodo, onClick) {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'jr-chip';
    btn.setAttribute('aria-pressed', 'false');
    const sub = precioSub(nodo);
    btn.innerHTML = sub ? `${nodo.nombre}<small>${sub}</small>` : nodo.nombre;
    btn.addEventListener('click', () => onClick(btn, nodo));
    return btn;
  }

  function tieneHijos(nodo) {
    return Array.isArray(nodo.hijos) && nodo.hijos.length > 0;
  }

  // Sublínea del chip.
  //  - Si tiene precio > 0 -> siempre lo muestra (aunque tenga hijos, como
  //    las líneas Varilux, que cuestan y además llevan tratamientos).
  //  - Si es agrupador puro (nivel/diseño, o precio 0 con hijos) -> nada.
  //  - Si es una hoja con precio 0 -> "Precio en tienda".
  function precioSub(nodo) {
    if (nodo.precio > 0) {
      return (nodo.es_extra ? '+ ' : '') + MXN.format(nodo.precio);
    }
    if (nodo.kind === 'nivel' || nodo.kind === 'diseno' || tieneHijos(nodo)) return null;
    return 'Precio en tienda';
  }

  // Renderiza los hijos de un nodo como un paso a la profundidad dada.
  function renderStep(depth, hijos) {
    if (!hijos || !hijos.length) return; // hoja: no hay más pasos
    const kind = hijos[0].kind;
    const block = ensureStepBlock(depth, kind);
    const chipsWrap = block.querySelector('[data-step-chips]');
    chipsWrap.innerHTML = '';

    hijos.forEach((nodo) => {
      const btn = chip(nodo, (b) => {
        chipsWrap.querySelectorAll('.jr-chip').forEach((c) => c.setAttribute('aria-pressed', 'false'));
        b.setAttribute('aria-pressed', 'true');

        // Al elegir en este paso, poda los inferiores y fija la elección.
        pruneStepsBelow(depth);
        state.path[depth] = nodo;

        // Si tiene hijos, abre el siguiente paso.
        if (nodo.hijos && nodo.hijos.length) {
          renderStep(depth + 1, nodo.hijos);
        }
        recalc();
      });
      chipsWrap.appendChild(btn);
    });
  }

  // Arranca los pasos dinámicos con las raíces del árbol (nivel).
  function initTreeSteps() {
    stepsWrap.innerHTML = '';
    state.path = [];
    renderStep(0, data.arbol || []);
  }

  /* ---------------------------------------------------------------------- */
  /* Resumen con galería multi-imagen                                       */
  /* ---------------------------------------------------------------------- */
  function selectedItems() {
    // Devuelve la lista ordenada de selecciones para el resumen.
    const items = [];
    if (state.armazon) {
      items.push({
        eyebrow: 'Armazón',
        nombre: state.armazon.nombre,
        descripcion: 'Armazón incluido.',
        precio: null,
        imagen: state.armazon.imagen || null,
      });
    }
    state.path.forEach((nodo) => {
      if (!nodo) return;
      // "En tienda" solo para hojas de precio (sin hijos) con precio 0.
      // Un material/nivel agrupador (con hijos) no muestra etiqueta de precio.
      const esHoja = !tieneHijos(nodo);
      const esAgrupadorPuro = nodo.kind === 'nivel' || nodo.kind === 'diseno';
      items.push({
        eyebrow: KIND_LABEL[nodo.kind] || 'Opción',
        nombre: nodo.nombre,
        descripcion: nodo.descripcion || null,
        precio: nodo.precio > 0 ? nodo.precio : null,
        enTienda: nodo.precio === 0 && esHoja && !esAgrupadorPuro,
        es_extra: nodo.es_extra,
        imagen: nodo.imagen || null,
      });
    });
    return items;
  }

  function renderGallery(items) {
    if (!gallery) return;
    const withImg = items.filter((it) => it.imagen);

    if (!withImg.length) {
      // Sin imágenes propias: muestra el placeholder (logo).
      if (placeholder) placeholder.style.display = '';
      gallery.querySelectorAll('[data-summary-thumb]').forEach((n) => n.remove());
      return;
    }

    if (placeholder) placeholder.style.display = 'none';

    // Reconciliar miniaturas por src (evita parpadeo al recalcular).
    const wanted = withImg.map((it) => it.imagen);
    gallery.querySelectorAll('[data-summary-thumb]').forEach((thumb) => {
      if (!wanted.includes(thumb.dataset.src)) thumb.remove();
    });

    withImg.forEach((it, i) => {
      let thumb = gallery.querySelector(`[data-summary-thumb][data-src="${cssEscape(it.imagen)}"]`);
      if (!thumb) {
        thumb = document.createElement('figure');
        thumb.className = 'jr-summary-thumb';
        thumb.dataset.summaryThumb = '';
        thumb.dataset.src = it.imagen;
        thumb.innerHTML = `
          <img src="${it.imagen}" alt="${it.nombre}" loading="lazy"
               onerror="this.closest('.jr-summary-thumb').remove()">
          <figcaption>${it.eyebrow}</figcaption>`;
        gallery.appendChild(thumb);
      }
      thumb.style.order = String(i);
    });
  }

  function cssEscape(s) {
    return String(s).replace(/["\\]/g, '\\$&');
  }

  function summaryRow(it) {
    let price = '';
    if (it.precio != null) {
      price = `<span class="jr-text-gold fw-semibold ms-2">${(it.es_extra ? '+ ' : '') + MXN.format(it.precio)}</span>`;
    } else if (it.enTienda) {
      price = `<span class="jr-text-muted small ms-2">Precio en tienda</span>`;
    }
    const desc = it.descripcion ? `<p class="jr-text-muted small mb-0 mt-1">${it.descripcion}</p>` : '';
    return `
      <div>
        <p class="jr-eyebrow mb-1">${it.eyebrow}</p>
        <div class="d-flex align-items-baseline justify-content-between">
          <span class="fw-semibold">${it.nombre}</span>${price}
        </div>
        ${desc}
      </div>`;
  }

  function renderSummary(items) {
    if (!summary) return;
    const any = items.length > 0;

    renderGallery(items);

    if (!any) {
      summaryEmpty?.classList.remove('d-none');
      summaryDetail?.classList.add('d-none');
      return;
    }
    summaryEmpty?.classList.add('d-none');
    summaryDetail?.classList.remove('d-none');
    if (summaryList) summaryList.innerHTML = items.map(summaryRow).join('');
  }

  /* ---------------------------------------------------------------------- */
  /* ---------------------------------------------------------------------- */
  /* Graduación (opcional) — tabla en escritorio, tarjetas en móvil.        */
  /* Ambas vistas comparten las mismas claves de campo; se mantienen en     */
  /* sincronía y se guardan en localStorage. No afecta el precio.           */
  /* ---------------------------------------------------------------------- */
  const GRAD_KEY = 'jr_graduacion';
  const GRAD_FIELDS = [
    'od-esfera', 'od-cilindro', 'od-eje', 'od-add',
    'oi-esfera', 'oi-cilindro', 'oi-eje', 'oi-add',
    'dip',
  ];

  function initGraduacion() {
    const grad = document.getElementById('jr-grad');
    if (!grad) return;

    // Todos los inputs de una clave (puede haber 2: tabla + tarjeta móvil).
    const inputsFor = (key) =>
      grad.querySelectorAll(`[data-grad="${key}"], [data-grad-m="${key}"]`);

    // Guardar en localStorage (tolerante a modo privado / bloqueos).
    function save() {
      try {
        const obj = {};
        GRAD_FIELDS.forEach((k) => {
          const el = grad.querySelector(`[data-grad="${k}"], [data-grad-m="${k}"]`);
          obj[k] = el ? el.value.trim() : '';
        });
        localStorage.setItem(GRAD_KEY, JSON.stringify(obj));
      } catch (e) { /* sin persistencia, no pasa nada */ }
    }

    // Restaurar valores guardados.
    function restore() {
      let obj = {};
      try { obj = JSON.parse(localStorage.getItem(GRAD_KEY) || '{}') || {}; }
      catch (e) { obj = {}; }
      GRAD_FIELDS.forEach((k) => {
        if (obj[k] != null && obj[k] !== '') {
          inputsFor(k).forEach((el) => { el.value = obj[k]; });
        }
      });
    }

    // Sincroniza las dos vistas y guarda al escribir.
    GRAD_FIELDS.forEach((k) => {
      inputsFor(k).forEach((el) => {
        el.addEventListener('input', () => {
          inputsFor(k).forEach((otro) => { if (otro !== el) otro.value = el.value; });
          save();
          // Actualiza el enlace de WhatsApp si ya está activo.
          recalc();
        });
      });
    });

    // Botón borrar.
    const clearBtn = grad.querySelector('[data-grad-clear]');
    if (clearBtn) {
      clearBtn.addEventListener('click', () => {
        GRAD_FIELDS.forEach((k) => inputsFor(k).forEach((el) => { el.value = ''; }));
        try { localStorage.removeItem(GRAD_KEY); } catch (e) {}
        recalc();
      });
    }

    restore();
  }

  // Lee la graduación actual del DOM como objeto { clave: valor }.
  function leerGraduacion() {
    const grad = document.getElementById('jr-grad');
    const out = {};
    if (!grad) return out;
    GRAD_FIELDS.forEach((k) => {
      const el = grad.querySelector(`[data-grad="${k}"], [data-grad-m="${k}"]`);
      out[k] = el ? el.value.trim() : '';
    });
    return out;
  }

  // Construye las líneas de graduación para el mensaje de WhatsApp.
  // Devuelve [] si no se capturó nada, para no ensuciar el mensaje.
  function graduacionLineas() {
    const g = leerGraduacion();
    const tieneAlgo = GRAD_FIELDS.some((k) => g[k] !== '');
    if (!tieneAlgo) return [];

    const ojo = (p, etiqueta) => {
      const partes = [];
      if (g[`${p}-esfera`]) partes.push(`Esf ${g[`${p}-esfera`]}`);
      if (g[`${p}-cilindro`]) partes.push(`Cil ${g[`${p}-cilindro`]}`);
      if (g[`${p}-eje`]) partes.push(`Eje ${g[`${p}-eje`]}`);
      if (g[`${p}-add`]) partes.push(`ADD ${g[`${p}-add`]}`);
      return partes.length ? `• ${etiqueta}: ${partes.join(', ')}` : null;
    };

    const l = ['', 'Graduación:'];
    const od = ojo('od', 'OD (der.)');
    const oi = ojo('oi', 'OI (izq.)');
    if (od) l.push(od);
    if (oi) l.push(oi);
    if (g.dip) l.push(`• DIP: ${g.dip} mm`);
    return l;
  }

  /* ---------------------------------------------------------------------- */
  /* WhatsApp                                                               */
  /* ---------------------------------------------------------------------- */
  function buildWhatsAppUrl(total) {
    const numero = (data.whatsapp || '').replace(/\D/g, '');
    const l = [];
    l.push(`¡Hola! Quiero cotizar unos lentes ${data.tipoNombre?.toLowerCase() || ''} con esta configuración:`);
    if (state.armazon) l.push(`• Armazón: ${state.armazon.nombre} (incluido)`);
    state.path.forEach((nodo) => {
      if (!nodo) return;
      const etiqueta = KIND_LABEL[nodo.kind] || 'Opción';
      const precio = nodo.precio > 0 ? ` — ${(nodo.es_extra ? '+ ' : '') + MXN.format(nodo.precio)}` : ' — precio en tienda';
      l.push(`• ${etiqueta}: ${nodo.nombre}${precio}`);
    });
    l.push(`Total estimado (micas): ${MXN.format(total)}`);

    // Graduación (si el cliente la capturó).
    graduacionLineas().forEach((linea) => l.push(linea));

    const texto = encodeURIComponent(l.join('\n'));
    return numero ? `https://wa.me/${numero}?text=${texto}` : `https://wa.me/?text=${texto}`;
  }

  /* ---------------------------------------------------------------------- */
  /* Recalcular                                                             */
  /* ---------------------------------------------------------------------- */
  function recalc() {
    const items = selectedItems();

    // Total: suma de nodos del path con precio > 0.
    const total = state.path.reduce((sum, n) => sum + (n && n.precio > 0 ? n.precio : 0), 0);
    priceEl.textContent = MXN.format(total);

    // Desglose corto
    if (!state.armazon) {
      breakdownEl.textContent = 'Elige un armazón para comenzar.';
    } else if (!state.path.length || !state.path[0]) {
      breakdownEl.textContent = 'Elige el nivel de la mica para ver tu precio.';
    } else {
      const partes = ['Armazón incluido'];
      state.path.forEach((n) => {
        if (!n) return;
        if (n.precio > 0) partes.push(`${n.nombre} ${MXN.format(n.precio)}`);
      });
      breakdownEl.innerHTML = '<span class="jr-text-muted">' + partes.join(' + ') + '</span>';
    }

    // El botón se activa con armazón + una hoja final (el último nodo del
    // path no tiene hijos). Esto funciona para todos los tipos y
    // profundidades: la configuración está completa solo al llegar al
    // final de la rama (tratamiento).
    const ultimo = state.path[state.path.length - 1];
    const hojaElegida = ultimo && !tieneHijos(ultimo);
    const listo = state.armazon && hojaElegida;

    if (waBtn) {
      if (listo) {
        waBtn.href = buildWhatsAppUrl(total);
        waBtn.classList.remove('disabled');
        waBtn.removeAttribute('aria-disabled');
      } else {
        waBtn.href = '#';
        waBtn.classList.add('disabled');
        waBtn.setAttribute('aria-disabled', 'true');
      }
    }

    renderSummary(items);
  }

  // Arranque
  initTreeSteps();
  initGraduacion();
  recalc();
}
