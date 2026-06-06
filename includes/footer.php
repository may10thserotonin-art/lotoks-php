<?php
/**
 * Lotoks — Marketing Footer
 * Pixel-perfect conversion from Footer.tsx
 */
require_once __DIR__ . '/config.php';
$year = date('Y');
?>
<footer class="site-footer">
  <!-- Main footer body -->
  <div style="max-width:80rem;margin-inline:auto;padding:4rem 1rem;">
    <div style="display:grid;grid-template-columns:1fr;gap:2.5rem;">

      <!-- Brand column -->
      <div>
        <a href="<?= BASE ?>/" style="display:inline-flex;align-items:center;gap:0.5rem;text-decoration:none;margin-bottom:1.5rem;">
          <div style="width:3.5rem;height:3.5rem;border-radius:0.75rem;overflow:hidden;">
            <img src="<?= BASE ?>/public/logo.png" alt="Lotoks" style="width:100%;height:100%;object-fit:contain;" />
          </div>
          <span style="font-family:var(--font-heading);font-size:1.5rem;font-weight:700;color:#fff;">
            Lotoks<span style="color:var(--color-gold);">.</span>
          </span>
        </a>

        <p style="color:rgba(255,255,255,0.6);margin-bottom:1.5rem;max-width:22rem;line-height:1.6;">
          Your trusted partner for global mobility solutions. We help individuals achieve their dreams of international opportunities through seamless sponsorship services.
        </p>

        <!-- Contact info -->
        <div style="display:flex;flex-direction:column;gap:0.75rem;font-size:0.875rem;">
          <a href="mailto:info@lotoks.co.za" style="display:flex;align-items:center;gap:0.75rem;color:rgba(255,255,255,0.7);text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.7)'">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            info@lotoks.co.za
          </a>
          <a href="tel:+27110518583" style="display:flex;align-items:center;gap:0.75rem;color:rgba(255,255,255,0.7);text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.7)'">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.72 12a19.79 19.79 0 0 1-3-8.59A2 2 0 0 1 3.72 1.5h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9a16 16 0 0 0 6.91 6.91l.87-.87a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
            Tel: +27 11 051 8583
          </a>
          <a href="https://wa.me/48790733839" target="_blank" rel="noopener noreferrer" style="display:flex;align-items:center;gap:0.75rem;color:rgba(255,255,255,0.7);text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.7)'">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
            WhatsApp: +48 790 733 839
          </a>
          <div style="display:flex;align-items:center;gap:0.75rem;color:rgba(255,255,255,0.7);">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
            Johannesburg, South Africa
          </div>
        </div>
      </div>

      <!-- Links grid -->
      <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:2rem;">
        <!-- Services -->
        <div>
          <h4 style="font-family:var(--font-heading);font-weight:600;font-size:1rem;margin-bottom:1rem;color:var(--color-gold);">Services</h4>
          <ul style="list-style:none;display:flex;flex-direction:column;gap:0.75rem;">
            <li><a href="<?= BASE ?>/services.php#visa"      style="color:rgba(255,255,255,0.6);text-decoration:none;font-size:0.875rem;transition:color 0.2s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Visa Sponsorship</a></li>
            <li><a href="<?= BASE ?>/services.php#education" style="color:rgba(255,255,255,0.6);text-decoration:none;font-size:0.875rem;transition:color 0.2s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Education Scholarships</a></li>
            <li><a href="<?= BASE ?>/services.php#jobs"      style="color:rgba(255,255,255,0.6);text-decoration:none;font-size:0.875rem;transition:color 0.2s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Job Placements</a></li>
            <li><a href="<?= BASE ?>/services.php#residence" style="color:rgba(255,255,255,0.6);text-decoration:none;font-size:0.875rem;transition:color 0.2s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Permanent Residence</a></li>
          </ul>
        </div>

        <!-- Company -->
        <div>
          <h4 style="font-family:var(--font-heading);font-weight:600;font-size:1rem;margin-bottom:1rem;color:var(--color-gold);">Company</h4>
          <ul style="list-style:none;display:flex;flex-direction:column;gap:0.75rem;">
            <li><a href="<?= BASE ?>/about.php"           style="color:rgba(255,255,255,0.6);text-decoration:none;font-size:0.875rem;transition:color 0.2s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">About Us</a></li>
            <li><a href="<?= BASE ?>/about.php#team"      style="color:rgba(255,255,255,0.6);text-decoration:none;font-size:0.875rem;transition:color 0.2s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Our Team</a></li>
            <li><a href="<?= BASE ?>/testimonials.php"    style="color:rgba(255,255,255,0.6);text-decoration:none;font-size:0.875rem;transition:color 0.2s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Testimonials</a></li>
            <li><a href="<?= BASE ?>/contact.php"         style="color:rgba(255,255,255,0.6);text-decoration:none;font-size:0.875rem;transition:color 0.2s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Contact Us</a></li>
          </ul>
        </div>

        <!-- Resources -->
        <div>
          <h4 style="font-family:var(--font-heading);font-weight:600;font-size:1rem;margin-bottom:1rem;color:var(--color-gold);">Resources</h4>
          <ul style="list-style:none;display:flex;flex-direction:column;gap:0.75rem;">
            <li><a href="<?= BASE ?>/eligibility.php" style="color:rgba(255,255,255,0.6);text-decoration:none;font-size:0.875rem;transition:color 0.2s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Eligibility Check</a></li>
            <li><a href="<?= BASE ?>/apply.php"       style="color:rgba(255,255,255,0.6);text-decoration:none;font-size:0.875rem;transition:color 0.2s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Application Guide</a></li>
            <li><a href="<?= BASE ?>/documents.php"   style="color:rgba(255,255,255,0.6);text-decoration:none;font-size:0.875rem;transition:color 0.2s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Documents Required</a></li>
            <li><a href="<?= BASE ?>/contact.php#faq" style="color:rgba(255,255,255,0.6);text-decoration:none;font-size:0.875rem;transition:color 0.2s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">FAQ</a></li>
          </ul>
        </div>

        <!-- Legal + Newsletter -->
        <div>
          <h4 style="font-family:var(--font-heading);font-weight:600;font-size:1rem;margin-bottom:1rem;color:var(--color-gold);">Legal</h4>
          <ul style="list-style:none;display:flex;flex-direction:column;gap:0.75rem;margin-bottom:2rem;">
            <li><a href="<?= BASE ?>/privacy.php"    style="color:rgba(255,255,255,0.6);text-decoration:none;font-size:0.875rem;transition:color 0.2s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Privacy Policy</a></li>
            <li><a href="<?= BASE ?>/terms.php"      style="color:rgba(255,255,255,0.6);text-decoration:none;font-size:0.875rem;transition:color 0.2s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Terms of Service</a></li>
            <li><a href="<?= BASE ?>/cookies.php"    style="color:rgba(255,255,255,0.6);text-decoration:none;font-size:0.875rem;transition:color 0.2s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Cookie Policy</a></li>
            <li><a href="<?= BASE ?>/disclaimer.php" style="color:rgba(255,255,255,0.6);text-decoration:none;font-size:0.875rem;transition:color 0.2s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.6)'">Disclaimer</a></li>
          </ul>

          <!-- Stay updated box -->
          <div style="padding:1rem;border-radius:0.75rem;background:rgba(201,164,75,0.1);border:1px solid rgba(201,164,75,0.2);">
            <h5 style="font-family:var(--font-heading);font-weight:600;margin-bottom:0.5rem;color:#fff;">Stay Updated</h5>
            <p style="font-size:0.8rem;color:rgba(255,255,255,0.6);margin-bottom:0.75rem;">Get the latest news and updates</p>
            <a href="<?= BASE ?>/contact.php" class="btn btn-secondary btn-sm btn-full btn-pill" style="justify-content:center;">Subscribe</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bottom bar -->
  <div class="footer-bottom">
    <div style="max-width:80rem;margin-inline:auto;padding:1.5rem 1rem;">
      <div style="display:flex;flex-direction:column;align-items:center;justify-content:space-between;gap:1rem;">

        <!-- Copyright -->
        <p style="color:rgba(255,255,255,0.4);font-size:0.875rem;">
          &copy; <?= $year ?> Lotoks. All rights reserved.
        </p>

        <!-- Social links -->
        <div style="display:flex;align-items:center;gap:1rem;">
          <!-- Facebook -->
          <a href="https://facebook.com/lotoks" target="_blank" rel="noopener noreferrer" class="footer-social-btn" aria-label="Facebook">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
          </a>
          <!-- X / Twitter -->
          <a href="https://x.com/LotoksConsult" target="_blank" rel="noopener noreferrer" class="footer-social-btn" aria-label="X (Twitter)">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
          </a>
          <!-- LinkedIn -->
          <a href="https://linkedin.com/company/lotoks" target="_blank" rel="noopener noreferrer" class="footer-social-btn" aria-label="LinkedIn">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
          </a>
          <!-- Instagram -->
          <a href="https://www.instagram.com/lotoks_projects/" target="_blank" rel="noopener noreferrer" class="footer-social-btn" aria-label="Instagram">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
          </a>
        </div>

        <p style="color:rgba(255,255,255,0.4);font-size:0.875rem;display:none;" class="footer-love">
          Made with <span style="color:var(--color-gold);">♥</span> for global mobility
        </p>
      </div>
    </div>
  </div>
</footer>

<style>
@media (min-width: 768px) {
  .site-footer > div:first-child > div { grid-template-columns: 2fr 4fr !important; }
  .site-footer .footer-bottom div > div { flex-direction: row !important; }
  .footer-love { display: block !important; }
}
@media (min-width: 1024px) {
  .site-footer > div:first-child > div { grid-template-columns: 2fr 1fr 1fr 1fr 1fr !important; }
}
</style>
