/* --------------------------------------------------------------------------
   http.js — Helpers reutilizables para peticiones a la API.
   Incluyen automáticamente el token CSRF y el header JSON.
   -------------------------------------------------------------------------- */

function csrfToken() {
  const meta = document.querySelector('meta[name="csrf-token"]');
  return meta ? meta.getAttribute('content') : '';
}

function baseHeaders(extra = {}) {
  return {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': csrfToken(),
    'X-Requested-With': 'XMLHttpRequest',
    ...extra,
  };
}

async function request(url, options = {}) {
  const response = await fetch(url, options);
  const contentType = response.headers.get('content-type') || '';
  const data = contentType.includes('application/json')
    ? await response.json()
    : await response.text();

  if (!response.ok) {
    const error = new Error(data?.message || 'La petición no se pudo completar.');
    error.status = response.status;
    error.data = data;
    throw error;
  }
  return data;
}

export function get(url, headers = {}) {
  return request(url, { method: 'GET', headers: baseHeaders(headers) });
}

export function post(url, body = {}, headers = {}) {
  return request(url, { method: 'POST', headers: baseHeaders(headers), body: JSON.stringify(body) });
}

export function put(url, body = {}, headers = {}) {
  return request(url, { method: 'PUT', headers: baseHeaders(headers), body: JSON.stringify(body) });
}

export function del(url, headers = {}) {
  return request(url, { method: 'DELETE', headers: baseHeaders(headers) });
}

/** Serializa un <form> a un objeto plano. */
export function serializeForm(form) {
  const data = {};
  new FormData(form).forEach((value, key) => {
    data[key] = value;
  });
  return data;
}

/** Activa/desactiva un estado de carga en un botón. */
export function setLoading(button, isLoading, loadingText = 'Enviando…') {
  if (!button) return;
  if (isLoading) {
    button.dataset.originalText = button.innerHTML;
    button.disabled = true;
    button.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>${loadingText}`;
  } else {
    button.disabled = false;
    if (button.dataset.originalText) button.innerHTML = button.dataset.originalText;
  }
}
