
function showToast(text, state) {
  const MAX_TOASTS = 3;
  const DURATION = 3500;

  const ICONS = {
    success: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
    error: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>',
    warning: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
    info: '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
  };

  // Inject styles once
  if (!document.getElementById("toast-styles")) {
    const style = document.createElement("style");
    style.id = "toast-styles";
    style.textContent = `
        .toast-container {
          position: fixed;
          bottom: 1.5rem;
          right: 1.5rem;
          z-index: 999;
          display: flex;
          flex-direction: column;
          gap: 0.7rem;
          pointer-events: none;
          max-width: 22rem;
          width: 100%;
        }
        .toast {
          position: relative;
          overflow: hidden;
          pointer-events: auto;
          display: flex;
          align-items: flex-start;
          gap: 0.7rem;
          background-color: #1e1e1e;
          border: 1px solid #2a2a2a;
          border-left: 2px solid #5b5eeb;
          border-radius: 0.6rem;
          padding: 0.9rem 1rem;
          box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
          font-family: "Geist", system-ui, sans-serif;
          color: #dddddd;
          opacity: 0;
          transform: translateY(30%);
          transition: transform 0.3s cubic-bezier(0.16, 1, 0.3, 1), opacity 0.3s ease;
        }
        .toast.toast-visible { opacity: 1; transform: translateY(0); }
        .toast.toast-leaving { opacity: 0; transform: translateY(30%); }
        .toast-icon { flex-shrink: 0; width: 1.3rem; height: 1.3rem; margin-top: 0.1rem; }
        .toast-message { flex: 1; min-width: 0; font-size: 0.9em; line-height: 1.35; }
        .toast-close {
          flex-shrink: 0; background: transparent; border: none;
          color: #a0a0a0; cursor: pointer; padding: 0.1rem; line-height: 0;
          transition: color 0.2s ease;
        }
        .toast-close:hover { color: #dddddd; }
        .toast-progress {
          position: absolute; bottom: 0; left: 0; height: 2px;
          background-color: #5b5eeb;
          animation: toast-progress-shrink ${DURATION}ms linear forwards;
        }
        .toast-container.toast-container-active {
          background-color: rgba(30, 30, 30, .7);
        }
        @keyframes toast-progress-shrink { from { width: 100%; } to { width: 0%; } }
        
        .toast.toast-success { border-left-color: #22c55e; }
        .toast.toast-success .toast-progress { background-color: #22c55e; }
        .toast.toast-success .toast-icon { color: #22c55e; }
        .toast.toast-error { border-left-color: rgb(240, 68, 56); }
        .toast.toast-error .toast-progress { background-color: #f04438; }
        .toast.toast-error .toast-icon { color: #f04438; }
        .toast.toast-warning { border-left-color: rgb(245, 158, 11); }
        .toast.toast-warning .toast-progress { background-color: #f59e0b; }
        .toast.toast-warning .toast-icon { color: #f59e0b; }
        .toast.toast-info .toast-icon { color: #5b5eeb; }
        @media (max-width: 500px) {
          .toast-container { bottom: 0.7rem; right: 0.7rem; left: 0.7rem; max-width: none; }
        }
       `;
    document.head.appendChild(style);
  }


  // Get or create the container (anchored to bottom, so new toasts push older ones up)
  let container = document.querySelector(".toast-container");
  if (!container) {
    container = document.createElement("div");
    container.className = "toast-container";
    document.body.appendChild(container);
  }

  const stillActive = container.querySelectorAll(".toast:not(.toast-leaving)");
  if (stillActive.length === 0) {
    container.classList.remove("toast-container-active");
  }


  // Enforce max of 3 visible toasts: remove the oldest immediately
  const existing = container.querySelectorAll(".toast");
  if (existing.length >= MAX_TOASTS) {
    existing[0].remove();
  }

  const div = document.createElement("div");
  div.className = `toast toast-${state}`;
  div.setAttribute("role", "status");
  div.setAttribute("aria-live", "polite");

  const div2 = document.createElement("div");
  div2.innerHTML = `
    <span class="toast-icon">${ICONS[state] || ICONS.info}</span>
    <div class="toast-message"></div>
    <button class="toast-close" aria-label="Dismiss notification">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
      </svg>
    </button>
    <div class="toast-progress"></div>
  `;
  while (div2.firstChild) div.appendChild(div2.firstChild);
  div.querySelector(".toast-message").textContent = text; // textContent avoids HTML injection

  container.appendChild(div);

  requestAnimationFrame(() => div.classList.add("toast-visible"));

  let timer;
  function dismiss() {
    clearTimeout(timer);
    div.classList.remove("toast-visible");
    div.classList.add("toast-leaving");
    div.addEventListener("transitionend", () => div.remove(), { once: true });
  }

  div.querySelector(".toast-close").addEventListener("click", dismiss);
  timer = setTimeout(dismiss, DURATION);
}
