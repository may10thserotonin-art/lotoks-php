/**
 * Lotoks — Main JavaScript
 * Replaces all Framer Motion / React / TanStack behaviours with vanilla JS
 */

'use strict';

/* ════════════════════════════════════════════════════════════
   1. PAGE LOADED — body fade-in
   ════════════════════════════════════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
  document.body.classList.add('page-loaded');
  initNavbar();
  initScrollAnimations();
  initCounters();
  initDropdown();
  initMobileMenu();
  initToast();
  initModals();
  initPasswordToggles();
  initTabBar();
});

/* ════════════════════════════════════════════════════════════
   2. NAVBAR — scroll background + active links
   ════════════════════════════════════════════════════════════ */
function initNavbar() {
  const navbar = document.querySelector('.site-navbar');
  if (!navbar) return;

  function updateNavbar() {
    if (window.scrollY > 20) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  }

  updateNavbar();
  window.addEventListener('scroll', updateNavbar, { passive: true });

  // Highlight active nav link
  const currentPath = window.location.pathname.replace(/\/$/, '');
  document.querySelectorAll('.nav-link').forEach(link => {
    const href = link.getAttribute('href') || '';
    const linkPath = href.replace(/\/$/, '');
    if (linkPath && (currentPath === linkPath || currentPath.startsWith(linkPath + '/'))) {
      link.classList.add('active');
    } else if (linkPath === '' && (currentPath === '' || currentPath === '/index.php')) {
      link.classList.add('active');
    }
  });
}

/* ════════════════════════════════════════════════════════════
   3. SERVICES DROPDOWN
   ════════════════════════════════════════════════════════════ */
function initDropdown() {
  const trigger   = document.getElementById('services-trigger');
  const dropdown  = document.getElementById('services-dropdown');
  if (!trigger || !dropdown) return;

  let closeTimer;

  function open() {
    clearTimeout(closeTimer);
    dropdown.classList.remove('hidden-anim');
    dropdown.classList.add('visible-anim');
    dropdown.hidden = false;
    trigger.setAttribute('aria-expanded', 'true');
  }

  function close() {
    closeTimer = setTimeout(() => {
      dropdown.classList.remove('visible-anim');
      dropdown.classList.add('hidden-anim');
      trigger.setAttribute('aria-expanded', 'false');
    }, 120);
  }

  trigger.addEventListener('mouseenter', open);
  trigger.addEventListener('mouseleave', close);
  dropdown.addEventListener('mouseenter', () => clearTimeout(closeTimer));
  dropdown.addEventListener('mouseleave', close);

  // Touch/click support
  trigger.addEventListener('click', (e) => {
    e.preventDefault();
    if (dropdown.classList.contains('visible-anim')) { close(); } else { open(); }
  });

  // Close on outside click
  document.addEventListener('click', (e) => {
    if (!trigger.contains(e.target) && !dropdown.contains(e.target)) { close(); }
  });
}

/* ════════════════════════════════════════════════════════════
   4. SCROLL ANIMATIONS (IntersectionObserver)
   Replaces Framer Motion fadeUpVariant / staggerContainer
   ════════════════════════════════════════════════════════════ */
function initScrollAnimations() {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const el = entry.target;
      const delay = el.dataset.delay || '0';
      el.style.transitionDelay = `${delay}ms`;
      el.classList.add('animated');
      observer.unobserve(el);
    });
  }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

  document.querySelectorAll('[data-animate]').forEach(el => observer.observe(el));

  // Stagger children
  document.querySelectorAll('[data-stagger]').forEach(parent => {
    const children = parent.children;
    const base = parseInt(parent.dataset.staggerDelay || '0', 10);
    const step = parseInt(parent.dataset.staggerStep  || '80', 10);

    const staggerObserver = new IntersectionObserver((entries) => {
      if (!entries[0].isIntersecting) return;
      Array.from(children).forEach((child, i) => {
        setTimeout(() => {
          child.classList.add('animated');
        }, base + i * step);
      });
      staggerObserver.unobserve(parent);
    }, { threshold: 0.1 });

    staggerObserver.observe(parent);
  });
}

/* ════════════════════════════════════════════════════════════
   5. COUNTER ANIMATION (requestAnimationFrame — no flicker)
   Replaces the buggy setInterval-based counter in React source
   ════════════════════════════════════════════════════════════ */
function initCounters() {
  const counters = document.querySelectorAll('[data-count]');
  if (!counters.length) return;

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (!entry.isIntersecting) return;
      const el    = entry.target;
      const end   = parseFloat(el.dataset.count);
      const dur   = parseInt(el.dataset.duration || '2000', 10);
      const isInt = !el.dataset.count.includes('.');
      const suffix = el.dataset.suffix || '';
      const prefix = el.dataset.prefix || '';

      let startTime = null;

      function tick(timestamp) {
        if (!startTime) startTime = timestamp;
        const elapsed  = timestamp - startTime;
        const progress = Math.min(elapsed / dur, 1);
        // easeOutCubic
        const eased = 1 - Math.pow(1 - progress, 3);
        const current = eased * end;

        el.textContent = prefix + (isInt ? Math.floor(current) : current.toFixed(1)) + suffix;

        if (progress < 1) {
          requestAnimationFrame(tick);
        } else {
          el.textContent = prefix + (isInt ? Math.floor(end) : end.toFixed(1)) + suffix;
        }
      }

      requestAnimationFrame(tick);
      observer.unobserve(el);
    });
  }, { threshold: 0.5 });

  counters.forEach(el => observer.observe(el));
}

/* ════════════════════════════════════════════════════════════
   6. MOBILE MENU (user sidebar)
   ════════════════════════════════════════════════════════════ */
function initMobileMenu() {
  const openBtn  = document.getElementById('mobile-menu-btn');
  const closeBtn = document.getElementById('mobile-menu-close');
  const panel    = document.getElementById('mobile-sidebar-panel');
  const overlay  = document.getElementById('mobile-menu-overlay');

  if (!panel) return;

  function openMenu() {
    panel.classList.add('open');
    if (overlay) { overlay.style.display = 'block'; }
    document.body.style.overflow = 'hidden';
  }

  function closeMenu() {
    panel.classList.remove('open');
    if (overlay) { overlay.style.display = 'none'; }
    document.body.style.overflow = '';
  }

  if (openBtn)  openBtn.addEventListener('click', openMenu);
  if (closeBtn) closeBtn.addEventListener('click', closeMenu);
  if (overlay)  overlay.addEventListener('click', closeMenu);
}

/* ════════════════════════════════════════════════════════════
   7. ADMIN MOBILE NAV toggle
   ════════════════════════════════════════════════════════════ */
function initAdminMobileNav() {
  const toggleBtn  = document.getElementById('admin-nav-toggle');
  const sidebar    = document.getElementById('admin-mobile-sidebar');
  const overlay    = document.getElementById('admin-nav-overlay');

  if (!toggleBtn || !sidebar) return;

  function open() {
    sidebar.classList.add('open');
    if (overlay) overlay.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }
  function close() {
    sidebar.classList.remove('open');
    if (overlay) overlay.style.display = 'none';
    document.body.style.overflow = '';
  }

  toggleBtn.addEventListener('click', open);
  if (overlay) overlay.addEventListener('click', close);
  document.querySelectorAll('.admin-nav-link').forEach(l => l.addEventListener('click', close));
}

/* ════════════════════════════════════════════════════════════
   8. TOAST SYSTEM
   Replaces react-toastify
   ════════════════════════════════════════════════════════════ */
function initToast() {
  if (document.getElementById('toast-container')) return;
  const container = document.createElement('div');
  container.id = 'toast-container';
  document.body.appendChild(container);
}

window.toast = {
  _show(message, type = 'info', duration = 3500) {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const el = document.createElement('div');
    el.className = `toast toast-${type}`;
    el.textContent = message;
    container.appendChild(el);

    setTimeout(() => {
      el.classList.add('fade-out');
      el.addEventListener('animationend', () => el.remove(), { once: true });
    }, duration);
  },
  success(msg, dur) { this._show(msg, 'success', dur); },
  error(msg, dur)   { this._show(msg, 'error', dur); },
  info(msg, dur)    { this._show(msg, 'info', dur); },
};

/* ════════════════════════════════════════════════════════════
   9. MODALS (generic open/close)
   ════════════════════════════════════════════════════════════ */
function initModals() {
  // Open
  document.querySelectorAll('[data-modal-open]').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.modalOpen;
      const modal = document.getElementById(id);
      if (modal) modal.style.display = 'flex';
    });
  });

  // Close
  document.querySelectorAll('[data-modal-close]').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.dataset.modalClose;
      const modal = id ? document.getElementById(id) : btn.closest('.modal-overlay');
      if (modal) modal.style.display = 'none';
    });
  });

  // Click outside
  document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', (e) => {
      if (e.target === overlay) overlay.style.display = 'none';
    });
  });

  // Esc key
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      document.querySelectorAll('.modal-overlay').forEach(o => o.style.display = 'none');
    }
  });
}

/* ════════════════════════════════════════════════════════════
   10. PASSWORD SHOW/HIDE TOGGLES
   ════════════════════════════════════════════════════════════ */
function initPasswordToggles() {
  document.querySelectorAll('[data-pw-toggle]').forEach(btn => {
    const targetId = btn.dataset.pwToggle;
    const input    = document.getElementById(targetId);
    if (!input) return;

    btn.addEventListener('click', () => {
      const isPassword = input.type === 'password';
      input.type = isPassword ? 'text' : 'password';

      // Swap icon
      const eyeOn  = btn.querySelector('.eye-on');
      const eyeOff = btn.querySelector('.eye-off');
      if (eyeOn)  eyeOn.style.display  = isPassword ? 'none' : 'block';
      if (eyeOff) eyeOff.style.display = isPassword ? 'block' : 'none';
    });
  });
}

/* ════════════════════════════════════════════════════════════
   11. MOBILE TAB BAR — active state
   ════════════════════════════════════════════════════════════ */
function initTabBar() {
  const currentPath = window.location.pathname;
  document.querySelectorAll('.tab-bar-item').forEach(item => {
    const href = item.getAttribute('href') || item.querySelector('a')?.getAttribute('href') || '';
    if (href && currentPath.includes(href.split('/').pop())) {
      item.classList.add('active');
    }
  });
}

/* ════════════════════════════════════════════════════════════
   12. SLIDE PANEL (admin detail)
   ════════════════════════════════════════════════════════════ */
window.SlidePanel = {
  open(panelId) {
    const panel   = document.getElementById(panelId);
    const overlay = document.getElementById(panelId + '-overlay');
    if (panel)   panel.classList.add('open');
    if (overlay) overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
  },
  close(panelId) {
    const panel   = document.getElementById(panelId);
    const overlay = document.getElementById(panelId + '-overlay');
    if (panel)   panel.classList.remove('open');
    if (overlay) overlay.classList.remove('open');
    document.body.style.overflow = '';
  }
};

/* ════════════════════════════════════════════════════════════
   13. AJAX HELPER
   ════════════════════════════════════════════════════════════ */
window.api = {
  async post(url, data) {
    const res = await fetch(url, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      body: JSON.stringify(data),
      credentials: 'same-origin',
    });
    return res.json();
  },

  async get(url) {
    const res = await fetch(url, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      credentials: 'same-origin',
    });
    return res.json();
  },

  async postForm(url, formData) {
    const res = await fetch(url, {
      method: 'POST',
      body: formData,
      credentials: 'same-origin',
    });
    return res.json();
  },

  async del(url) {
    const res = await fetch(url, {
      method: 'DELETE',
      headers: { 'X-Requested-With': 'XMLHttpRequest' },
      credentials: 'same-origin',
    });
    return res.json();
  },

  async put(url, data) {
    const res = await fetch(url, {
      method: 'PUT',
      headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      body: JSON.stringify(data),
      credentials: 'same-origin',
    });
    return res.json();
  }
};

/* ════════════════════════════════════════════════════════════
   14. FORM SUBMIT HELPER — show spinner in button
   ════════════════════════════════════════════════════════════ */
window.setButtonLoading = function(btn, loading, originalText) {
  if (loading) {
    btn.disabled = true;
    btn.dataset.originalText = btn.innerHTML;
    btn.innerHTML = `<svg class="spinner spinner-sm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
    </svg>`;
  } else {
    btn.disabled = false;
    btn.innerHTML = originalText || btn.dataset.originalText || btn.innerHTML;
  }
};

/* ════════════════════════════════════════════════════════════
   15. FILTER TABS (generic pill filter, used in Opportunities)
   ════════════════════════════════════════════════════════════ */
window.initFilterTabs = function(containerSelector, targetSelector, filterAttr) {
  const container = document.querySelector(containerSelector);
  if (!container) return;

  container.querySelectorAll('[data-filter]').forEach(btn => {
    btn.addEventListener('click', () => {
      // Update active state
      container.querySelectorAll('[data-filter]').forEach(b => b.classList.remove('active'));
      btn.classList.add('active');

      const filter = btn.dataset.filter;

      document.querySelectorAll(targetSelector).forEach(item => {
        if (filter === 'all' || item.dataset[filterAttr] === filter) {
          item.style.display = '';
        } else {
          item.style.display = 'none';
        }
      });
    });
  });
};

/* ════════════════════════════════════════════════════════════
   16. SEARCH FILTER (real-time)
   ════════════════════════════════════════════════════════════ */
window.initSearch = function(inputId, targetSelector, searchFields) {
  const input = document.getElementById(inputId);
  if (!input) return;

  input.addEventListener('input', () => {
    const q = input.value.toLowerCase().trim();

    document.querySelectorAll(targetSelector).forEach(item => {
      const text = searchFields.map(f => item.dataset[f] || '').join(' ').toLowerCase();
      item.style.display = (!q || text.includes(q)) ? '' : 'none';
    });
  });
};

/* ════════════════════════════════════════════════════════════
   17. CONFIRMATION DIALOGS
   ════════════════════════════════════════════════════════════ */
window.confirm2 = function(message) {
  return new Promise(resolve => {
    // Use native for simplicity — can upgrade to custom modal
    resolve(window.confirm(message));
  });
};

/* ════════════════════════════════════════════════════════════
   18. FORMAT HELPERS
   ════════════════════════════════════════════════════════════ */
window.fmt = {
  date(str) {
    if (!str) return '—';
    return new Date(str).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
  },
  datetime(str) {
    if (!str) return '—';
    return new Date(str).toLocaleString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' });
  },
  bytes(n) {
    if (!n) return '0 B';
    const k = 1024;
    const sizes = ['B', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(n) / Math.log(k));
    return parseFloat((n / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i];
  },
  currency(amount, currency = 'USD') {
    return new Intl.NumberFormat('en-US', { style: 'currency', currency }).format(amount);
  }
};
