<?php
/**
 * Lotoks — Get in Touch (contact.php)
 * Converted from pages/Contact.tsx
 */
require_once __DIR__ . '/includes/auth.php';

$page_title       = 'Contact Us | Lotoks';
$page_description = 'Have questions? We would love to hear from you. Send us a message and our team will get back to you within 24 hours.';
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- ════════════════════════════════════════════════════════════
     HERO SECTION
     ════════════════════════════════════════════════════════════ -->
<section class="page-hero">
  <div class="hero-overlay-img">
    <img src="<?= BASE ?>/public/images/Contact-us.png" alt="Contact Us Background" loading="eager" />
  </div>
  <div class="hero-overlay-dark"></div>

  <div class="container" style="position:relative; z-index:10; text-align:center;">
    <h1 style="font-size:clamp(2.5rem, 6vw, 4rem); color:#fff; margin-bottom:1rem; font-family:var(--font-heading); font-weight:700;" data-animate="fade-up">
      Get in Touch
    </h1>
    <p style="font-size:clamp(1.1rem, 2vw, 1.4rem); color:rgba(255,255,255,0.8); max-width:42rem; margin-inline:auto;" data-animate="fade-up" data-delay="100">
      Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
    </p>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     CONTACT INFO CARDS
     ════════════════════════════════════════════════════════════ -->
<section class="section-wrapper">
  <div class="container">
    <div style="display:grid; grid-template-columns:1fr; gap:2rem;" class="contact-info-grid">
      
      <!-- Email Card -->
      <div class="elevated-card info-card text-center" data-animate="fade-up">
        <div class="info-icon-box">
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
        </div>
        <h3 style="font-family:var(--font-heading); font-size:1.2rem; font-weight:700; color:var(--color-navy); margin-bottom:0.5rem;">Email Us</h3>
        <a href="mailto:info@lotoks.co.za" style="color:var(--color-gold); font-weight:600; font-size:1.05rem;" class="info-link">info@lotoks.co.za</a>
        <p style="color:rgba(11,29,58,0.5); font-size:0.85rem; margin-top:0.5rem;">Alternative: ruth@lotoks.co.za</p>
      </div>

      <!-- WhatsApp Card -->
      <div class="elevated-card info-card text-center" data-animate="fade-up" data-delay="100">
        <div class="info-icon-box">
          <!-- WhatsApp Icon -->
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
        </div>
        <h3 style="font-family:var(--font-heading); font-size:1.2rem; font-weight:700; color:var(--color-navy); margin-bottom:0.5rem;">WhatsApp Us</h3>
        <a href="https://wa.me/48790733839" target="_blank" rel="noopener noreferrer" style="color:var(--color-gold); font-weight:600; font-size:1.05rem;" class="info-link">+48 790 733 839</a>
        <p style="color:rgba(11,29,58,0.5); font-size:0.85rem; margin-top:0.5rem;">Business WhatsApp (24/7)</p>
      </div>

      <!-- Office Line Card -->
      <div class="elevated-card info-card text-center" data-animate="fade-up" data-delay="200">
        <div class="info-icon-box">
          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        </div>
        <h3 style="font-family:var(--font-heading); font-size:1.2rem; font-weight:700; color:var(--color-navy); margin-bottom:0.5rem;">Office Line</h3>
        <a href="tel:+27110518583" style="color:var(--color-gold); font-weight:600; font-size:1.05rem;" class="info-link">+27 11 051 8583</a>
        <p style="color:rgba(11,29,58,0.5); font-size:0.85rem; margin-top:0.5rem;">Cell: +27 81 506 9081</p>
      </div>

    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     CONTACT FORM & MAP SPLIT SECTION
     ════════════════════════════════════════════════════════════ -->
<section class="section-wrapper" style="background:linear-gradient(to bottom, var(--color-surface), #fff);">
  <div class="container">
    <div style="display:grid; grid-template-columns:1fr; gap:3rem;" class="contact-split-grid">
      
      <!-- Left side: Form -->
      <div data-animate="fade-up">
        <div class="glass-card contact-form-card" style="padding:2.5rem 2rem;">
          
          <!-- Success Screen Container -->
          <div id="form-success-container" style="display:none; text-align:center; padding-block:2rem;">
            <div style="width:5rem; height:5rem; border-radius:50%; background:rgba(29,122,122,0.1); display:flex; align-items:center; justify-content:center; margin-inline:auto; margin-bottom:1.5rem; color:var(--color-teal);">
              <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <h3 style="font-family:var(--font-heading); font-size:1.75rem; font-weight:700; color:#fff; margin-bottom:0.75rem;">Message Sent Successfully!</h3>
            <p style="color:rgba(255,255,255,0.7); max-width:24rem; margin-inline:auto; font-size:0.95rem; line-height:1.6; margin-bottom:2rem;">
              Thank you for reaching out. Our team will review your message and get back to you within 24 hours.
            </p>
            <button id="form-reset-btn" class="btn btn-secondary btn-pill">Send Another Message</button>
          </div>

          <!-- Main Form Container -->
          <div id="form-main-container">
            <h3 style="font-family:var(--font-heading); font-size:1.5rem; font-weight:700; color:#fff; margin-bottom:1.5rem;">Send Us a Message</h3>
            
            <form id="contact-form" novalidate style="display:flex; flex-direction:column; gap:1.25rem;">
              <div style="display:grid; grid-template-columns:1fr; gap:1.25rem;" class="form-row">
                <div class="form-group" style="margin-bottom:0;">
                  <label for="fullName" class="form-label">Full Name</label>
                  <input type="text" id="fullName" class="form-input" placeholder="John Doe" required />
                  <span id="error-fullName" class="form-error" style="display:none;"></span>
                </div>
                
                <div class="form-group" style="margin-bottom:0;">
                  <label for="email" class="form-label">Email Address</label>
                  <input type="email" id="email" class="form-input" placeholder="john@example.com" required />
                  <span id="error-email" class="form-error" style="display:none;"></span>
                </div>
              </div>

              <div style="display:grid; grid-template-columns:1fr; gap:1.25rem;" class="form-row">
                <div class="form-group" style="margin-bottom:0;">
                  <label for="phone" class="form-label">Phone Number (Optional)</label>
                  <input type="tel" id="phone" class="form-input" placeholder="+27 81 506 9081" />
                </div>
                
                <div class="form-group" style="margin-bottom:0;">
                  <label for="interest" class="form-label">Interest</label>
                  <select id="interest" class="form-input form-select" required>
                    <option value="" disabled selected>Select your interest</option>
                    <option value="visa">Visa Sponsorship</option>
                    <option value="education">Education Scholarship</option>
                    <option value="jobs">Job Placement</option>
                    <option value="residence">Permanent Residence</option>
                    <option value="other">Other Inquiry</option>
                  </select>
                  <span id="error-interest" class="form-error" style="display:none;"></span>
                </div>
              </div>

              <div class="form-group" style="margin-bottom:0;">
                <label for="message" class="form-label">Your Message</label>
                <textarea id="message" class="form-input form-textarea" rows="5" placeholder="Tell us about your goals and how we can help..." required></textarea>
                <span id="error-message" class="form-error" style="display:none;"></span>
              </div>

              <button type="submit" id="submit-btn" class="btn btn-primary btn-full btn-lg btn-pill" style="margin-top:0.75rem;">
                Send Message
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="margin-left:0.25rem;"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
              </button>
            </form>
          </div>

        </div>
      </div>

      <!-- Right side: Additional info + Map -->
      <div data-animate="fade-up" data-delay="150" style="display:flex; flex-direction:column; gap:2rem;">
        
        <!-- Abstract CSS Map Section -->
        <div>
          <h3 style="font-family:var(--font-heading); font-size:1.25rem; font-weight:700; color:var(--color-navy); margin-bottom:1rem;">Our Location</h3>
          
          <div class="abstract-map-box">
            <!-- Background mesh -->
            <div class="map-mesh"></div>

            <!-- Glowing coordinates -->
            <?php
            $coords = [
              ["top" => "20%", "left" => "30%", "label" => "New York"],
              ["top" => "40%", "left" => "60%", "label" => "London"],
              ["top" => "50%", "left" => "75%", "label" => "Dubai"],
              ["top" => "60%", "left" => "45%", "label" => "Singapore"],
              ["top" => "70%", "left" => "80%", "label" => "Sydney"],
            ];
            foreach ($coords as $pt): ?>
              <div class="map-coordinate" style="top: <?= $pt['top'] ?>; left: <?= $pt['left'] ?>;">
                <div class="pulse-dot"></div>
                <span class="coord-label"><?= htmlspecialchars($pt['label']) ?></span>
              </div>
            <?php endforeach; ?>

            <!-- Global presence card overlay -->
            <div class="map-overlay-card">
              <div style="width:2.5rem; height:2.5rem; border-radius:50%; background:rgba(201,164,75,0.15); color:var(--color-gold); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
              </div>
              <div style="text-align:left;">
                <div style="font-weight:700; color:var(--color-navy); font-size:0.95rem;">Global Presence</div>
                <div style="font-size:0.75rem; color:var(--color-on-surface-variant); font-weight:500;">Serving applicants in 150+ countries</div>
              </div>
            </div>
          </div>
        </div>

        <!-- FAQ Quick links -->
        <div>
          <h3 style="font-family:var(--font-heading); font-size:1.25rem; font-weight:700; color:var(--color-navy); margin-bottom:1rem;">Quick Questions?</h3>
          <div style="display:grid; grid-template-columns:1fr; gap:1rem;" class="quick-faq-grid">
            <?php
            $quickFaq = [
              ["q" => "How do I apply?", "a" => "Start with our eligibility check", "url" => "/eligibility.php"],
              ["q" => "What documents do I need?", "a" => "Access checklist guidelines", "url" => "/services.php#visa"],
              ["q" => "How long does it take?", "a" => "Learn about processing times", "url" => "/services.php#visa"],
              ["q" => "What are the costs?", "a" => "Request pricing information", "url" => "/services.php#visa"]
            ];
            foreach ($quickFaq as $idx => $lnk): ?>
              <a href="<?= $lnk['url'] ?>" class="quick-faq-card" style="background:#fff; border:1px solid rgba(11,29,58,0.06); border-radius:1rem; padding:1.25rem; display:flex; align-items:center; text-decoration:none; transition:all 0.3s ease;">
                <div style="width:2.25rem; height:2.25rem; border-radius:50%; background:rgba(201,164,75,0.1); color:var(--color-gold); display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-right:1rem;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                </div>
                <div style="text-align:left; flex:1; min-width:0;">
                  <div style="font-weight:700; color:var(--color-navy); font-size:0.85rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?= htmlspecialchars($lnk['q']) ?></div>
                  <div style="font-size:0.75rem; color:rgba(11,29,58,0.5); font-weight:500;"><?= htmlspecialchars($lnk['a']) ?></div>
                </div>
                <div class="arrow" style="color:var(--color-gold); opacity:0; transition:all 0.25s ease;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </div>
              </a>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Follow Us & Office Hours -->
        <div style="display:grid; grid-template-columns:1fr; gap:1.5rem;" class="social-hours-grid">
          
          <!-- Socials -->
          <div>
            <h3 style="font-family:var(--font-heading); font-size:1.25rem; font-weight:700; color:var(--color-navy); margin-bottom:1rem;">Follow Us</h3>
            <div style="display:flex; flex-wrap:wrap; gap:0.5rem;">
              <?php
              $socials = [
                ["name" => "X", "url" => "https://x.com/LotoksConsult", "icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>'],
                ["name" => "Instagram", "url" => "https://www.instagram.com/lotoks_projects/", "icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>'],
                ["name" => "Facebook", "url" => "https://facebook.com/lotoks", "icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>'],
                ["name" => "LinkedIn", "url" => "https://linkedin.com/company/lotoks", "icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 24 24"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>']
              ];
              foreach ($socials as $soc): ?>
                <a href="<?= $soc['url'] ?>" target="_blank" rel="noopener noreferrer" class="social-badge" style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1rem; border-radius:0.75rem; background:rgba(11,29,58,0.04); border:1px solid rgba(11,29,58,0.06); color:rgba(11,29,58,0.7); text-decoration:none; font-size:0.8rem; font-weight:600; transition:all 0.3s ease;">
                  <span style="color:var(--color-gold); display:flex; align-items:center;"><?= $soc['icon'] ?></span>
                  <span><?= $soc['name'] ?></span>
                </a>
              <?php endforeach; ?>
            </div>
          </div>

          <!-- Office Hours -->
          <div style="background:rgba(11,29,58,0.03); border:1px solid rgba(11,29,58,0.06); border-radius:1rem; padding:1.25rem;">
            <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.75rem;">
              <span style="color:var(--color-gold); display:flex;">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
              </span>
              <h4 style="font-family:var(--font-heading); font-size:0.95rem; font-weight:700; color:var(--color-navy); margin-bottom:0;">Office Hours</h4>
            </div>
            <div style="color:rgba(11,29,58,0.65); font-size:0.85rem; line-height:1.5; font-weight:500;">
              <p>Monday - Friday: 9:00 AM - 5:00 PM (EST)</p>
              <p>Saturday - Sunday: Closed</p>
              <p style="font-size:0.75rem; color:rgba(11,29,58,0.4); margin-top:0.5rem; font-style:italic;">*Our online support is available 24/7 for urgent inquiries</p>
            </div>
          </div>

        </div>

      </div>

    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     CTA SECTION
     ════════════════════════════════════════════════════════════ -->
<section class="section-wrapper bg-navy" style="position:relative; overflow:hidden;">
  <div style="position:absolute; top:0; left:0; width:100%; height:100%; background:radial-gradient(circle at center, rgba(201,164,75,0.06) 0%, transparent 70%); pointer-events:none;"></div>
  
  <div class="container" style="position:relative; z-index:10; text-align:center; max-width:42rem;">
    <h2 style="font-family:var(--font-heading); font-size:clamp(2rem, 5vw, 3rem); font-weight:700; color:#fff; margin-bottom:1.5rem;" data-animate="fade-up">
      Still Have Questions?
    </h2>
    <p style="font-size:1.15rem; color:rgba(255,255,255,0.7); margin-bottom:2.5rem;" data-animate="fade-up" data-delay="100">
      Check our detailed FAQ section or start your application today.
    </p>
    <div style="display:flex; flex-direction:column; justify-content:center; gap:1rem; align-items:center;" class="cta-btns" data-animate="fade-up" data-delay="200">
      <a href="<?= BASE ?>/eligibility.php" class="btn btn-primary btn-lg btn-pill" style="min-width:14rem;">
        Check Eligibility
      </a>
      <a href="<?= BASE ?>/services.php" class="btn btn-secondary btn-lg btn-pill" style="min-width:14rem; border-color:var(--color-gold); color:var(--color-gold);">
        View Services
      </a>
    </div>
  </div>
</section>

<!-- CSS Styling -->
<style>
/* Grid settings */
.contact-info-grid { grid-template-columns: 1fr; }
@media (min-width: 768px) {
  .contact-info-grid { grid-template-columns: repeat(3, 1fr) !important; }
}

.contact-split-grid { grid-template-columns: 1fr; }
@media (min-width: 992px) {
  .contact-split-grid { grid-template-columns: 1.1fr 0.9fr !important; }
}

/* Info card styles */
.info-card {
  padding: 2.25rem 1.5rem;
  border-radius: 1.5rem;
}
.info-icon-box {
  width: 4rem;
  height: 4rem;
  border-radius: 1rem;
  background: rgba(201,164,75,0.1);
  color: var(--color-gold);
  display: flex;
  align-items: center;
  justify-content: center;
  margin-inline: auto;
  margin-bottom: 1.25rem;
  transition: all 0.3s ease;
}
.info-card:hover .info-icon-box {
  background: rgba(201,164,75,0.2);
  transform: scale(1.05);
}
.info-link {
  transition: color 0.2s;
}
.info-link:hover {
  text-decoration: underline;
  color: #a37c2a !important;
}

/* Form inputs styling override rows */
.form-row { grid-template-columns: 1fr; }
@media (min-width: 576px) {
  .form-row { grid-template-columns: 1fr 1fr !important; }
}

/* Abstract map styling */
.abstract-map-box {
  position: relative;
  height: 22rem;
  border-radius: 1.5rem;
  overflow: hidden;
  background: var(--color-navy);
  border: 1px solid rgba(11,29,58,0.08);
  box-shadow: var(--shadow-card);
}
.map-mesh {
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at center, rgba(201,164,75,0.1) 0%, rgba(11,29,58,0.1) 80%), 
              linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
              linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
  background-size: 100% 100%, 20px 20px, 20px 20px;
}
.map-coordinate {
  position: absolute;
  transform: translate(-50%, -50%);
}
.pulse-dot {
  width: 0.75rem;
  height: 0.75rem;
  border-radius: 50%;
  background: var(--color-gold);
  box-shadow: 0 0 12px var(--color-gold);
  animation: pulse 2.2s infinite;
}
.coord-label {
  position: absolute;
  top: 1.25rem;
  left: 50%;
  transform: translateX(-50%);
  font-size: 0.65rem;
  font-weight: 600;
  color: rgba(255,255,255,0.7);
  background: rgba(11,29,58,0.85);
  backdrop-filter: blur(4px);
  border: 1px solid rgba(255,255,255,0.08);
  padding: 0.2rem 0.5rem;
  border-radius: 0.5rem;
  white-space: nowrap;
  pointer-events: none;
}
.map-overlay-card {
  position: absolute;
  bottom: 1rem;
  left: 1rem;
  right: 1rem;
  background: rgba(255,255,255,0.92);
  backdrop-filter: blur(8px);
  border-radius: 1rem;
  padding: 0.75rem 1rem;
  border: 1px solid rgba(255,255,255,0.2);
  display: flex;
  align-items: center;
  gap: 0.75rem;
  box-shadow: 0 10px 25px rgba(11,29,58,0.15);
}

/* Quick FAQs grid */
.quick-faq-grid { grid-template-columns: 1fr; }
@media (min-width: 576px) {
  .quick-faq-grid { grid-template-columns: 1fr 1fr !important; }
}

.quick-faq-card:hover {
  border-color: rgba(201,164,75,0.3) !important;
  box-shadow: 0 10px 25px rgba(11,29,58,0.08);
  transform: translateY(-2px);
}
.quick-faq-card:hover .arrow {
  opacity: 1 !important;
  transform: translateX(3px);
}

/* Social links hover */
.social-badge:hover {
  background: rgba(11,29,58,0.08) !important;
  border-color: rgba(201,164,75,0.3) !important;
  color: var(--color-navy) !important;
  transform: translateY(-2px);
}

.social-hours-grid { grid-template-columns: 1fr; }
@media (min-width: 576px) {
  .social-hours-grid { grid-template-columns: 1fr 1fr !important; }
}

/* CTA buttons layout */
.cta-btns { flex-direction: column; }
@media (min-width: 576px) {
  .cta-btns { flex-direction: row !important; }
}
</style>

<!-- Form submission & validation script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('contact-form');
  const mainContainer = document.getElementById('form-main-container');
  const successContainer = document.getElementById('form-success-container');
  const resetBtn = document.getElementById('form-reset-btn');
  const submitBtn = document.getElementById('submit-btn');

  if (form) {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();

      // Clear previous errors
      const errorSpans = form.querySelectorAll('.form-error');
      const inputs = form.querySelectorAll('.form-input');
      errorSpans.forEach(span => { span.style.display = 'none'; span.textContent = ''; });
      inputs.forEach(input => input.classList.remove('error'));

      // Validate inputs
      const fullName = document.getElementById('fullName').value.trim();
      const email = document.getElementById('email').value.trim();
      const interest = document.getElementById('interest').value;
      const message = document.getElementById('message').value.trim();

      let hasError = false;

      if (!fullName) {
        showError('fullName', 'Full name is required');
        hasError = true;
      }

      if (!email) {
        showError('email', 'Email is required');
        hasError = true;
      } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showError('email', 'Please enter a valid email address');
        hasError = true;
      }

      if (!interest) {
        showError('interest', 'Please select an interest');
        hasError = true;
      }

      if (!message) {
        showError('message', 'Message is required');
        hasError = true;
      } else if (message.length < 10) {
        showError('message', 'Message must be at least 10 characters');
        hasError = true;
      }

      if (hasError) return;

      // Simulate sending state
      const originalText = submitBtn.innerHTML;
      window.setButtonLoading(submitBtn, true);

      // Simulate API delay (1.5s)
      await new Promise(resolve => setTimeout(resolve, 1500));

      window.setButtonLoading(submitBtn, false, originalText);

      // Transition to success screen
      mainContainer.style.display = 'none';
      successContainer.style.display = 'block';
      successContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
  }

  if (resetBtn) {
    resetBtn.addEventListener('click', () => {
      form.reset();
      successContainer.style.display = 'none';
      mainContainer.style.display = 'block';
    });
  }

  function showError(fieldId, errorMsg) {
    const errorSpan = document.getElementById('error-' + fieldId);
    const input = document.getElementById(fieldId);
    if (errorSpan && input) {
      errorSpan.textContent = errorMsg;
      errorSpan.style.display = 'block';
      input.classList.add('error');
    }
  }
});
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
require_once __DIR__ . '/includes/scripts.php';
?>
</body>
</html>
