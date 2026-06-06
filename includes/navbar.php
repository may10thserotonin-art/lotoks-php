<?php
/**
 * Lotoks — Marketing Navbar
 * Pixel-perfect conversion from Navbar.tsx
 * Include after <body> tag on every public page.
 *
 * Optional var:
 *   $current_page = 'home'|'about'|'services'|'testimonials'|'contact'
 */
require_once __DIR__ . '/config.php';

$current_page = $current_page ?? '';

// Determine active link from URL path
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (BASE !== '' && str_starts_with($uri, BASE)) {
    $uri = substr($uri, strlen(BASE));
}
$uri = rtrim($uri, '/') ?: '/';

function nav_is_active(string $uri, string $href): bool {
    $href = rtrim($href, '/') ?: '/';
    if ($href === '/') return $uri === '/' || $uri === '/index.php';
    return $uri === $href || str_starts_with($uri, $href . '/') || str_starts_with($uri, $href . '.');
}
?>
<header
  class="site-navbar"
  id="site-navbar"
  style="position:fixed;top:0;left:0;right:0;z-index:50;transition:background 0.3s,padding 0.3s,box-shadow 0.3s;padding:1.25rem 0;background:transparent;"
  aria-label="Main navigation"
>
  <div style="max-width:80rem;margin-inline:auto;padding-inline:1rem;">
    <div style="display:flex;align-items:center;justify-content:space-between;">

      <!-- Logo -->
      <a href="<?= BASE ?>/" style="display:flex;align-items:center;gap:0.5rem;text-decoration:none;" class="navbar-logo">
        <div style="width:3rem;height:3rem;border-radius:0.75rem;overflow:hidden;transition:transform 0.2s;">
          <img src="<?= BASE ?>/public/logo.png" alt="Lotoks" style="width:100%;height:100%;object-fit:contain;" />
        </div>
        <span style="font-family:var(--font-heading);font-size:1.5rem;font-weight:700;color:#fff;">
          Lotoks<span style="color:var(--color-gold);">.</span>
        </span>
      </a>

      <!-- Desktop Nav -->
      <nav class="desktop-nav" style="display:none;align-items:center;gap:0.25rem;" aria-label="Primary">

        <!-- Home -->
        <a href="<?= BASE ?>/"
           class="nav-link<?= nav_is_active($uri, '/') ? ' active' : '' ?>"
           style="padding:0.5rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;color:<?= nav_is_active($uri, '/') ? 'var(--color-gold)' : 'rgba(255,255,255,0.8)' ?>;text-decoration:none;transition:color 0.2s,background 0.2s;"
           onmouseover="if(!this.classList.contains('active')){this.style.color='#fff';this.style.background='rgba(255,255,255,0.05)'}"
           onmouseout="if(!this.classList.contains('active')){this.style.color='rgba(255,255,255,0.8)';this.style.background='transparent'}"
        >Home</a>

        <!-- About -->
        <a href="<?= BASE ?>/about.php"
           class="nav-link<?= nav_is_active($uri, '/about') ? ' active' : '' ?>"
           style="padding:0.5rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;color:<?= nav_is_active($uri, '/about') ? 'var(--color-gold)' : 'rgba(255,255,255,0.8)' ?>;text-decoration:none;transition:color 0.2s,background 0.2s;"
           onmouseover="if(!this.classList.contains('active')){this.style.color='#fff';this.style.background='rgba(255,255,255,0.05)'}"
           onmouseout="if(!this.classList.contains('active')){this.style.color='rgba(255,255,255,0.8)';this.style.background='transparent'}"
        >About Us</a>

        <!-- Services (dropdown) -->
        <div style="position:relative;" id="services-nav-wrapper">
          <button
            id="services-trigger"
            aria-haspopup="true"
            aria-expanded="false"
            style="display:flex;align-items:center;gap:0.25rem;padding:0.5rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;color:<?= nav_is_active($uri, '/services') ? 'var(--color-gold)' : 'rgba(255,255,255,0.8)' ?>;background:none;border:none;cursor:pointer;transition:color 0.2s,background 0.2s;"
            onmouseover="this.style.color='#fff';this.style.background='rgba(255,255,255,0.05)'"
            onmouseout="this.style.color='rgba(255,255,255,0.8)';this.style.background='transparent'"
          >
            Our Services
            <svg id="services-chevron" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transition:transform 0.2s;"><polyline points="6 9 12 15 18 9"></polyline></svg>
          </button>

          <!-- Dropdown -->
          <div
            id="services-dropdown"
            class="services-dropdown hidden-anim"
            hidden
            style="position:absolute;top:100%;left:0;margin-top:0.5rem;width:14rem;padding:0.5rem;background:rgba(11,29,58,0.97);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border-radius:0.75rem;border:1px solid rgba(201,164,75,0.2);box-shadow:0 20px 40px rgba(0,0,0,0.4);"
          >
            <a href="<?= BASE ?>/services.php#visa"       class="dropdown-item" style="display:block;padding:0.625rem 1rem;border-radius:0.5rem;font-size:0.875rem;color:rgba(255,255,255,0.8);text-decoration:none;transition:color 0.15s,background 0.15s;" onmouseover="this.style.color='var(--color-gold)';this.style.background='rgba(201,164,75,0.05)'" onmouseout="this.style.color='rgba(255,255,255,0.8)';this.style.background='transparent'">Visa Sponsorship</a>
            <a href="<?= BASE ?>/services.php#education"  class="dropdown-item" style="display:block;padding:0.625rem 1rem;border-radius:0.5rem;font-size:0.875rem;color:rgba(255,255,255,0.8);text-decoration:none;transition:color 0.15s,background 0.15s;" onmouseover="this.style.color='var(--color-gold)';this.style.background='rgba(201,164,75,0.05)'" onmouseout="this.style.color='rgba(255,255,255,0.8)';this.style.background='transparent'">Education Scholarships</a>
            <a href="<?= BASE ?>/services.php#jobs"       class="dropdown-item" style="display:block;padding:0.625rem 1rem;border-radius:0.5rem;font-size:0.875rem;color:rgba(255,255,255,0.8);text-decoration:none;transition:color 0.15s,background 0.15s;" onmouseover="this.style.color='var(--color-gold)';this.style.background='rgba(201,164,75,0.05)'" onmouseout="this.style.color='rgba(255,255,255,0.8)';this.style.background='transparent'">Job Placements</a>
            <a href="<?= BASE ?>/services.php#residence"  class="dropdown-item" style="display:block;padding:0.625rem 1rem;border-radius:0.5rem;font-size:0.875rem;color:rgba(255,255,255,0.8);text-decoration:none;transition:color 0.15s,background 0.15s;" onmouseover="this.style.color='var(--color-gold)';this.style.background='rgba(201,164,75,0.05)'" onmouseout="this.style.color='rgba(255,255,255,0.8)';this.style.background='transparent'">Permanent Residence</a>
            <div style="height:1px;background:rgba(255,255,255,0.08);margin:0.25rem 0.5rem;"></div>
            <a href="<?= BASE ?>/requirements.php"        class="dropdown-item" style="display:block;padding:0.625rem 1rem;border-radius:0.5rem;font-size:0.875rem;color:rgba(255,255,255,0.8);text-decoration:none;transition:color 0.15s,background 0.15s;" onmouseover="this.style.color='var(--color-gold)';this.style.background='rgba(201,164,75,0.05)'" onmouseout="this.style.color='rgba(255,255,255,0.8)';this.style.background='transparent'">Application Requirements</a>
          </div>
        </div>

        <!-- Testimonials -->
        <a href="<?= BASE ?>/testimonials.php"
           class="nav-link<?= nav_is_active($uri, '/testimonials') ? ' active' : '' ?>"
           style="padding:0.5rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;color:<?= nav_is_active($uri, '/testimonials') ? 'var(--color-gold)' : 'rgba(255,255,255,0.8)' ?>;text-decoration:none;transition:color 0.2s,background 0.2s;"
           onmouseover="if(!this.classList.contains('active')){this.style.color='#fff';this.style.background='rgba(255,255,255,0.05)'}"
           onmouseout="if(!this.classList.contains('active')){this.style.color='rgba(255,255,255,0.8)';this.style.background='transparent'}"
        >Testimonials</a>

        <!-- Contact -->
        <a href="<?= BASE ?>/contact.php"
           class="nav-link<?= nav_is_active($uri, '/contact') ? ' active' : '' ?>"
           style="padding:0.5rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;color:<?= nav_is_active($uri, '/contact') ? 'var(--color-gold)' : 'rgba(255,255,255,0.8)' ?>;text-decoration:none;transition:color 0.2s,background 0.2s;"
           onmouseover="if(!this.classList.contains('active')){this.style.color='#fff';this.style.background='rgba(255,255,255,0.05)'}"
           onmouseout="if(!this.classList.contains('active')){this.style.color='rgba(255,255,255,0.8)';this.style.background='transparent'}"
        >Contact Us</a>
      </nav>

      <!-- Desktop CTA -->
      <div class="desktop-cta" style="display:none;align-items:center;gap:0.75rem;">
        <a href="<?= BASE ?>/login.php" class="btn btn-ghost btn-sm" style="color:#fff;">Sign In</a>
        <a href="<?= BASE ?>/eligibility.php" class="btn btn-primary btn-sm btn-pill" style="background:var(--color-gold);color:var(--color-navy);">
          Check Eligibility
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
        </a>
      </div>

      <!-- Mobile hamburger -->
      <button
        id="mobile-nav-btn"
        aria-label="Toggle menu"
        aria-controls="mobile-nav-panel"
        aria-expanded="false"
        style="display:flex;align-items:center;justify-content:center;width:2.5rem;height:2.5rem;border-radius:0.5rem;border:none;background:transparent;color:#fff;cursor:pointer;"
        class="mobile-nav-toggle"
      >
        <svg id="nav-icon-open" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
        <svg id="nav-icon-close" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
      </button>
    </div>
  </div>

  <!-- Mobile Menu Panel -->
  <div
    id="mobile-nav-panel"
    style="display:none;background:rgba(11,29,58,0.98);backdrop-filter:blur(16px);-webkit-backdrop-filter:blur(16px);border-top:1px solid rgba(201,164,75,0.1);overflow:hidden;"
    aria-label="Mobile navigation"
  >
    <div style="max-width:80rem;margin-inline:auto;padding:1rem;">
      <nav style="display:flex;flex-direction:column;gap:0.25rem;">
        <a href="<?= BASE ?>/"                class="mobile-nav-link<?= nav_is_active($uri, '/') ? ' mactive' : '' ?>"        style="padding:0.75rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;color:<?= nav_is_active($uri, '/') ? 'var(--color-gold)' : 'rgba(255,255,255,0.8)' ?>;text-decoration:none;<?= nav_is_active($uri, '/') ? 'background:rgba(201,164,75,0.1);' : '' ?>">Home</a>
        <a href="<?= BASE ?>/about.php"       class="mobile-nav-link<?= nav_is_active($uri, '/about') ? ' mactive' : '' ?>"    style="padding:0.75rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;color:<?= nav_is_active($uri, '/about') ? 'var(--color-gold)' : 'rgba(255,255,255,0.8)' ?>;text-decoration:none;<?= nav_is_active($uri, '/about') ? 'background:rgba(201,164,75,0.1);' : '' ?>">About Us</a>
        <a href="<?= BASE ?>/services.php"    class="mobile-nav-link<?= nav_is_active($uri, '/services') ? ' mactive' : '' ?>" style="padding:0.75rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;color:<?= nav_is_active($uri, '/services') ? 'var(--color-gold)' : 'rgba(255,255,255,0.8)' ?>;text-decoration:none;<?= nav_is_active($uri, '/services') ? 'background:rgba(201,164,75,0.1);' : '' ?>">Our Services</a>

        <!-- Mobile services sub-links -->
        <div style="padding-left:1rem;display:flex;flex-direction:column;gap:0.25rem;">
          <a href="<?= BASE ?>/services.php#visa"      style="display:block;padding:0.5rem 1rem;font-size:0.8rem;color:rgba(255,255,255,0.5);text-decoration:none;">↳ Visa Sponsorship</a>
          <a href="<?= BASE ?>/services.php#education" style="display:block;padding:0.5rem 1rem;font-size:0.8rem;color:rgba(255,255,255,0.5);text-decoration:none;">↳ Education Scholarships</a>
          <a href="<?= BASE ?>/services.php#jobs"      style="display:block;padding:0.5rem 1rem;font-size:0.8rem;color:rgba(255,255,255,0.5);text-decoration:none;">↳ Job Placements</a>
          <a href="<?= BASE ?>/services.php#residence" style="display:block;padding:0.5rem 1rem;font-size:0.8rem;color:rgba(255,255,255,0.5);text-decoration:none;">↳ Permanent Residence</a>
        </div>

        <a href="<?= BASE ?>/testimonials.php" class="mobile-nav-link<?= nav_is_active($uri, '/testimonials') ? ' mactive' : '' ?>" style="padding:0.75rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;color:<?= nav_is_active($uri, '/testimonials') ? 'var(--color-gold)' : 'rgba(255,255,255,0.8)' ?>;text-decoration:none;">Testimonials</a>
        <a href="<?= BASE ?>/contact.php"      class="mobile-nav-link<?= nav_is_active($uri, '/contact') ? ' mactive' : '' ?>"      style="padding:0.75rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;color:<?= nav_is_active($uri, '/contact') ? 'var(--color-gold)' : 'rgba(255,255,255,0.8)' ?>;text-decoration:none;">Contact Us</a>

        <div style="height:1px;background:rgba(255,255,255,0.1);margin:0.5rem 0;"></div>

        <a href="<?= BASE ?>/login.php" style="padding:0.75rem 1rem;border-radius:0.5rem;font-size:0.875rem;font-weight:500;color:rgba(255,255,255,0.8);text-decoration:none;">Sign In</a>
        <a href="<?= BASE ?>/eligibility.php" class="btn btn-primary btn-pill" style="margin-top:0.5rem;justify-content:center;background:var(--color-gold);color:var(--color-navy);">Check Eligibility</a>
      </nav>
    </div>
  </div>
</header>

<style>
@media (min-width: 1024px) {
  .desktop-nav  { display: flex !important; }
  .desktop-cta  { display: flex !important; }
  .mobile-nav-toggle { display: none !important; }
}
.mobile-nav-link { transition: color 0.2s, background 0.2s; }
.mobile-nav-link:hover:not(.mactive) { color: #fff !important; background: rgba(255,255,255,0.05); }
.navbar-logo div:hover { transform: scale(1.05); }
</style>

<script>
(function () {
  // Mobile toggle
  const btn   = document.getElementById('mobile-nav-btn');
  const panel = document.getElementById('mobile-nav-panel');
  const iconO = document.getElementById('nav-icon-open');
  const iconC = document.getElementById('nav-icon-close');

  if (btn && panel) {
    btn.addEventListener('click', () => {
      const open = panel.style.display !== 'none' && panel.style.display !== '';
      panel.style.display = open ? 'none' : 'block';
      iconO.style.display = open ? 'block' : 'none';
      iconC.style.display = open ? 'none'  : 'block';
      btn.setAttribute('aria-expanded', String(!open));
    });
  }

  // Services dropdown chevron rotation
  const trigger  = document.getElementById('services-trigger');
  const chevron  = document.getElementById('services-chevron');
  const dropdown = document.getElementById('services-dropdown');
  if (trigger && dropdown) {
    trigger.addEventListener('mouseenter', () => { chevron.style.transform = 'rotate(180deg)'; });
    trigger.addEventListener('mouseleave', () => { chevron.style.transform = 'rotate(0deg)'; });
    const wrapper = document.getElementById('services-nav-wrapper');
    if (wrapper) {
      wrapper.addEventListener('mouseleave', () => { chevron.style.transform = 'rotate(0deg)'; });
    }
  }
})();
</script>
