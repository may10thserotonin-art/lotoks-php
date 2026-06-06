<?php
/**
 * Lotoks — Homepage (index.php)
 * Converted from pages/Home.tsx
 */
require_once __DIR__ . '/includes/auth.php';

$page_title       = 'Lotoks | Your Gateway to Global Opportunities';
$page_description = 'Lotoks connects ambitious professionals with visa sponsorships, education scholarships, job placements, and residence programs worldwide.';
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- ════════════════════════════════════════════════════════════
     HERO SECTION — from HeroSection()
     ════════════════════════════════════════════════════════════ -->
<section class="hero-section">
  <!-- Background image + overlays -->
  <div class="hero-bg">
    <img src="<?= BASE ?>/public/bg_hero.png" alt="Lotoks — Global Opportunities" loading="eager" />
  </div>
  <div class="hero-overlay-1"></div>
  <div class="hero-gradient"></div>

  <!-- Decorative blobs -->
  <div class="hero-blob-gold orb-1" style="top:25%;left:25%;width:24rem;height:24rem;"></div>
  <div class="hero-blob-gold orb-2" style="bottom:25%;right:25%;width:20rem;height:20rem;background:rgba(29,122,122,0.1);"></div>

  <!-- Floating orbs -->
  <div class="orb-float orb-1" style="top:5rem;left:2.5rem;width:1rem;height:1rem;"></div>
  <div class="orb-float orb-2" style="top:10rem;right:5rem;width:0.75rem;height:0.75rem;opacity:0.4;"></div>
  <div class="orb-float orb-3" style="bottom:8rem;left:33%;width:1.25rem;height:1.25rem;opacity:0.3;"></div>

  <!-- Hero content -->
  <div class="hero-content">
    <!-- Badge -->
    <div class="hero-badge" style="animation-delay:0s;">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="var(--color-gold)" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
      <span>Trusted by 50,000+ applicants worldwide</span>
    </div>

    <!-- H1 -->
    <h1 class="hero-h1" style="animation-delay:0.1s;">
      Your Gateway to
      <span class="gold">Global Opportunities</span>
    </h1>

    <!-- Subheading -->
    <p class="hero-sub" style="animation-delay:0.2s;">
      We connect aspirational professionals with visa sponsorships, education scholarships, job placements, and residence programs worldwide.
    </p>

    <!-- CTA buttons -->
    <div class="hero-cta" style="animation-delay:0.3s;">
      <a href="<?= BASE ?>/eligibility.php" class="btn btn-primary btn-lg btn-pill arrow-parent">
        Check Your Eligibility
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="arrow-hover"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
      </a>
      <a href="<?= BASE ?>/services.php" class="btn btn-secondary btn-lg btn-pill">
        Explore Services
      </a>
    </div>

    <!-- Floating stats card -->
    <div class="hero-stats-card" style="animation-delay:0.5s;">
      <div class="flag-avatars">
        <?php
        $flags = [
          ['code' => 'sg', 'name' => 'Singapore'],
          ['code' => 'us', 'name' => 'USA'],
          ['code' => 'ng', 'name' => 'Nigeria'],
          ['code' => 'gb', 'name' => 'United Kingdom'],
          ['code' => 'au', 'name' => 'Australia'],
        ];
        foreach ($flags as $f): ?>
          <div class="flag-avatar" title="<?= htmlspecialchars($f['name']) ?>">
            <img
              src="https://flagcdn.com/w80/<?= $f['code'] ?>.png"
              alt="<?= htmlspecialchars($f['name']) ?>"
              loading="lazy"
            />
          </div>
        <?php endforeach; ?>
      </div>
      <div style="text-align:left;">
        <div style="color:#fff;font-weight:700;">10,000+</div>
        <div style="color:rgba(255,255,255,0.6);font-size:0.875rem;">Active applicants</div>
      </div>
    </div>
  </div>

  <!-- Scroll indicator -->
  <div class="scroll-indicator">
    <div class="scroll-mouse">
      <div class="scroll-dot"></div>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     HOW IT WORKS — from HowItWorks()
     ════════════════════════════════════════════════════════════ -->
<section class="section-wrapper" style="background:linear-gradient(to bottom,var(--color-surface),#fff);">
  <div class="container">
    <div class="section-heading center dark" data-animate="fade-up">
      <h2>How It Works</h2>
      <p>Get started in four simple steps. Our streamlined process makes your journey to global opportunities effortless.</p>
    </div>

    <!-- Connecting line (desktop only) -->
    <div style="position:relative;">
      <div style="display:none;" class="how-connector"></div>

      <div style="display:grid;grid-template-columns:1fr;gap:2rem;" data-stagger data-stagger-step="120">
        <?php
        $steps = [
          ['step' => 1, 'title' => 'Create Account',    'desc' => 'Sign up in seconds and complete your profile with basic information.'],
          ['step' => 2, 'title' => 'Apply in 2 Minutes','desc' => 'Select your desired sponsorship type and fill in your details.'],
          ['step' => 3, 'title' => 'We Process',         'desc' => 'Our team reviews your application and matches you with opportunities.'],
          ['step' => 4, 'title' => 'Get Sponsored',      'desc' => 'Connect with sponsors and start your journey to a new life.'],
        ];
        foreach ($steps as $s): ?>
          <div class="process-step-card" data-animate="fade-up">
            <div class="step-number"><?= $s['step'] ?></div>
            <div style="padding-top:1rem;">
              <h3 style="font-family:var(--font-heading);font-size:1.125rem;font-weight:700;color:#fff;margin-bottom:0.75rem;">
                <?= htmlspecialchars($s['title']) ?>
              </h3>
              <p style="color:rgba(255,255,255,0.6);font-size:0.875rem;">
                <?= htmlspecialchars($s['desc']) ?>
              </p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<style>
@media (min-width:768px) {
  section .how-connector { display:block !important;position:absolute;top:1.5rem;left:0;right:0;height:2px;background:linear-gradient(to right,rgba(201,164,75,0),rgba(201,164,75,0.5),rgba(201,164,75,0)); }
  section [data-stagger] { grid-template-columns: repeat(2,1fr) !important; }
}
@media (min-width:1024px) {
  section [data-stagger] { grid-template-columns: repeat(4,1fr) !important; }
}
</style>

<!-- ════════════════════════════════════════════════════════════
     SERVICES OVERVIEW — from ServicesOverview()
     ════════════════════════════════════════════════════════════ -->
<section class="section-wrapper bg-navy">
  <div class="container">
    <div class="section-heading dark" data-animate="fade-up">
      <h2 style="color:#fff;">Our <span style="color:var(--color-gold);">Services</span></h2>
      <p style="color:rgba(255,255,255,0.6);">Comprehensive solutions for all your global mobility needs</p>
    </div>

    <div style="display:grid;grid-template-columns:1fr;gap:1.5rem;" class="services-grid">
      <?php
      $services = [
        ['id' => 'visa',      'title' => 'Visa Sponsorship',      'desc' => 'Work, study, and travel visas with verified sponsors',           'img' => BASE . '/public/images/Visa-sponsorship.jpg',       'icon' => 'globe'],
        ['id' => 'education', 'title' => 'Education Scholarships', 'desc' => 'Full and partial scholarships at top universities worldwide',     'img' => BASE . '/public/images/Educational-scholarship.jpg',  'icon' => 'graduation-cap'],
        ['id' => 'jobs',      'title' => 'Job Placements',         'desc' => 'Connect with employers offering sponsorship packages',            'img' => BASE . '/public/images/job-placement.jpg',            'icon' => 'briefcase'],
        ['id' => 'residence', 'title' => 'Permanent Residence',    'desc' => 'Pathways to citizenship through investment and work',             'img' => BASE . '/public/images/permanent-resident.jpg',       'icon' => 'home'],
      ];
      foreach ($services as $i => $srv): ?>
        <a href="<?= BASE ?>/services.php#<?= $srv['id'] ?>" class="image-card service-card" data-animate="fade-up" data-delay="<?= $i * 100 ?>" style="height:20rem;display:block;text-decoration:none;">
          <div class="image-card-bg" style="background-image:url('<?= $srv['img'] ?>');" onerror="this.style.background='#0B1D3A'"></div>
          <div class="image-card-overlay"></div>
          <div class="image-card-content">
            <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;">
              <div style="width:3rem;height:3rem;border-radius:0.75rem;background:rgba(201,164,75,0.2);display:flex;align-items:center;justify-content:center;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                  <?php if ($srv['icon'] === 'globe'): ?><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                  <?php elseif ($srv['icon'] === 'graduation-cap'): ?><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                  <?php elseif ($srv['icon'] === 'briefcase'): ?><rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                  <?php else: ?><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline>
                  <?php endif; ?>
                </svg>
              </div>
            </div>
            <h3 style="font-family:var(--font-heading);font-size:1.5rem;font-weight:700;color:#fff;margin-bottom:0.5rem;">
              <?= htmlspecialchars($srv['title']) ?>
            </h3>
            <p style="color:rgba(255,255,255,0.7);margin-bottom:1rem;"><?= htmlspecialchars($srv['desc']) ?></p>
            <div style="display:flex;align-items:center;gap:0.5rem;color:var(--color-gold);font-weight:500;" class="arrow-parent">
              Learn more
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="arrow-hover"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

    <div style="text-align:center;margin-top:3rem;" data-animate="fade-up">
      <a href="<?= BASE ?>/services.php" class="btn btn-secondary btn-lg btn-pill">View All Services</a>
    </div>
  </div>
</section>

<style>
@media (min-width:768px) { .services-grid { grid-template-columns: repeat(2,1fr) !important; } }
</style>

<!-- ════════════════════════════════════════════════════════════
     PARTNERS SECTION — from PartnersSection()
     ════════════════════════════════════════════════════════════ -->
<section style="padding-block:5rem;background:linear-gradient(to bottom,var(--color-navy),rgba(11,29,58,0.95));position:relative;overflow:hidden;">
  <!-- Blobs -->
  <div style="position:absolute;top:0;left:33%;width:24rem;height:24rem;background:rgba(201,164,75,0.05);border-radius:50%;filter:blur(60px);pointer-events:none;"></div>
  <div style="position:absolute;bottom:0;right:33%;width:20rem;height:20rem;background:rgba(29,122,122,0.05);border-radius:50%;filter:blur(60px);pointer-events:none;"></div>

  <div class="container" style="position:relative;">
    <!-- Header -->
    <div style="text-align:center;margin-bottom:3.5rem;" data-animate="fade-up">
      <div style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.5rem 1rem;border-radius:9999px;background:rgba(201,164,75,0.1);border:1px solid rgba(201,164,75,0.2);margin-bottom:1.25rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>
        <span style="font-size:0.75rem;font-weight:700;color:var(--color-gold);letter-spacing:0.1em;text-transform:uppercase;">Our Partners &amp; Accreditations</span>
      </div>
      <h2 style="font-family:var(--font-heading);font-size:clamp(1.75rem,4vw,2.25rem);font-weight:700;color:#fff;margin-bottom:1rem;">Trusted by Leading Institutions</h2>
      <p style="color:rgba(255,255,255,0.6);max-width:36rem;margin-inline:auto;">
        We collaborate with accredited universities, placement agencies, and professional bodies worldwide to deliver verified, high-quality opportunities.
      </p>
    </div>

    <!-- Partner cards -->
    <div style="display:grid;grid-template-columns:1fr;gap:1.5rem;" class="partners-grid">
      <?php
      $partners = [
        [
          'name' => 'Everest Educational Services',
          'logo' => BASE . '/public/Everest-logo/Everest.jpeg',
          'desc' => 'Your trusted partner for international education. Connecting students worldwide with top academic programs and expert guidance for study abroad.',
          'url'  => 'https://everestedu.ca/',
          'tag'  => 'Education Partner',
        ],
        [
          'name' => 'UITM – University of Information Technology and Management',
          'logo' => BASE . '/public/images/partners/uitm-logo.svg',
          'desc' => 'A leading Polish university offering world-class degree programs in IT and management. Recognised internationally for academic excellence in Rzeszów, Poland.',
          'url'  => 'https://en.uitm.edu.eu/',
          'tag'  => 'University Partner',
        ],
        [
          'name' => 'APSO – African Professional Staffing Organisations',
          'logo' => BASE . '/public/images/partners/apso-logo.png',
          'desc' => 'The Federation of African Professional Staffing Organisations, dedicated to setting ethical standards and empowering staffing professionals across Africa.',
          'url'  => 'https://apso.org.za/',
          'tag'  => 'Accreditation Body',
        ],
      ];
      foreach ($partners as $i => $p): ?>
        <div class="partner-card" data-animate="fade-up" data-delay="<?= $i * 150 ?>">
          <div class="shine"></div>
          <span class="partner-tag"><?= htmlspecialchars($p['tag']) ?></span>
          <div class="partner-logo-box">
            <img src="<?= htmlspecialchars($p['logo']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" loading="lazy" onerror="this.parentElement.innerHTML='<span style=\'color:#666;font-size:0.75rem;\'>Logo</span>'" />
          </div>
          <h3 style="font-family:var(--font-heading);font-weight:800;font-size:0.9rem;color:var(--color-navy);margin-bottom:0.75rem;line-height:1.3;">
            <?= htmlspecialchars($p['name']) ?>
          </h3>
          <p style="color:rgba(11,29,58,0.85);font-size:0.875rem;line-height:1.6;flex:1;font-weight:500;">
            <?= htmlspecialchars($p['desc']) ?>
          </p>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Divider -->
    <div style="margin-top:3.5rem;height:1px;background:linear-gradient(to right,transparent,rgba(255,255,255,0.1),transparent);"></div>
  </div>
</section>

<style>
@media (min-width:768px) { .partners-grid { grid-template-columns: repeat(3,1fr) !important; } }
</style>

<!-- ════════════════════════════════════════════════════════════
     TESTIMONIALS PREVIEW — from TestimonialsPreview()
     ════════════════════════════════════════════════════════════ -->
<section class="section-wrapper" style="background:linear-gradient(to bottom,#fff,var(--color-surface));">
  <div class="container">
    <div class="section-heading center dark" data-animate="fade-up">
      <h2>What Our Applicants <span style="color:var(--color-primary);">Say</span></h2>
      <p>Real stories from real people who achieved their global dreams</p>
    </div>

    <div style="display:grid;grid-template-columns:1fr;gap:1.5rem;" class="testimonials-grid">
      <?php
      $testimonials = [
        ['name' => 'Sarah Chen',       'country' => 'Singapore', 'code' => 'sg', 'type' => 'Visa Sponsorship',     'quote' => 'Lotoks made my dream of working in Europe a reality. The process was smooth and transparent.',         'rating' => 5],
        ['name' => 'Marcus Johnson',   'country' => 'USA',       'code' => 'us', 'type' => 'Education Scholarship', 'quote' => 'Got a full scholarship to study in Canada. The team guided me through every step.',                 'rating' => 5],
        ['name' => 'Amara Okonkwo',    'country' => 'Nigeria',   'code' => 'ng', 'type' => 'Job Placement',         'quote' => 'Found a tech job in Germany with full sponsorship. Best decision I ever made.',                    'rating' => 5],
      ];
      foreach ($testimonials as $i => $t): ?>
        <div class="glass-card testimonial-card" data-animate="fade-up" data-delay="<?= $i * 150 ?>">
          <!-- Quote icon -->
          <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="rgba(201,164,75,0.3)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom:1rem;"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 2v4c0 1.25.757 2 2 2h2c0 0 0 3-3 4m14-1c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 2v4c0 1.25.757 2 2 2h2c0 0 0 3-3 4"/></svg>
          <!-- Stars -->
          <div style="display:flex;gap:0.25rem;margin-bottom:1rem;">
            <?php for ($s = 0; $s < $t['rating']; $s++): ?>
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="var(--color-gold)" stroke="var(--color-gold)" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
            <?php endfor; ?>
          </div>
          <!-- Quote -->
          <blockquote style="color:rgba(255,255,255,0.8);font-style:italic;margin-bottom:1.5rem;flex:1;">
            "<?= htmlspecialchars($t['quote']) ?>"
          </blockquote>
          <!-- Author -->
          <div style="display:flex;align-items:center;gap:0.75rem;padding-top:1rem;border-top:1px solid rgba(255,255,255,0.1);">
            <div class="testimonial-flag">
              <img src="https://flagcdn.com/w80/<?= $t['code'] ?>.png" alt="<?= htmlspecialchars($t['country']) ?>" loading="lazy" />
            </div>
            <div>
              <div style="font-weight:600;color:#fff;"><?= htmlspecialchars($t['name']) ?></div>
              <div style="color:rgba(255,255,255,0.5);font-size:0.875rem;"><?= htmlspecialchars($t['country']) ?> · <?= htmlspecialchars($t['type']) ?></div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <div style="text-align:center;margin-top:3rem;" data-animate="fade-up">
      <a href="<?= BASE ?>/testimonials.php" class="btn btn-primary btn-lg btn-pill">Read More Stories</a>
    </div>
  </div>
</section>

<style>
@media (min-width:768px) { .testimonials-grid { grid-template-columns: repeat(3,1fr) !important; } }
</style>

<!-- ════════════════════════════════════════════════════════════
     STATS SECTION — from StatsSection()
     ════════════════════════════════════════════════════════════ -->
<section style="padding-block:5rem;background:rgba(11,29,58,0.05);">
  <div class="container">
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem;" class="stats-grid">
      <?php
      $stats = [
        ['count' => 5000, 'suffix' => '+',       'label' => 'Applications Processed', 'duration' => 2000],
        ['count' => 98,   'suffix' => '%',        'label' => 'Success Rate',           'duration' => 1500],
        ['count' => 45,   'suffix' => '+',        'label' => 'Partner Countries',      'duration' => 1800],
        ['count' => null, 'display' => '2-4 Months', 'label' => 'Average Processing',  'duration' => null],
      ];
      foreach ($stats as $i => $st): ?>
        <div class="stat-card" data-animate="scale-in" data-delay="<?= $i * 100 ?>" style="background:#fff;">
          <div class="stat-number">
            <?php if ($st['count'] !== null): ?>
              <span
                data-count="<?= $st['count'] ?>"
                data-suffix="<?= htmlspecialchars($st['suffix']) ?>"
                data-duration="<?= $st['duration'] ?>"
              >0<?= htmlspecialchars($st['suffix']) ?></span>
            <?php else: ?>
              <?= htmlspecialchars($st['display']) ?>
            <?php endif; ?>
          </div>
          <div class="stat-label"><?= htmlspecialchars($st['label']) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<style>
@media (min-width:768px) { .stats-grid { grid-template-columns: repeat(4,1fr) !important; } }
</style>

<!-- ════════════════════════════════════════════════════════════
     SOCIAL SECTION — from SocialSection()
     ════════════════════════════════════════════════════════════ -->
<section style="padding-block:4rem;background:#fff;">
  <div class="container" style="max-width:48rem;text-align:center;">
    <h2 style="font-family:var(--font-heading);font-size:clamp(1.75rem,4vw,2.25rem);font-weight:700;color:var(--color-navy);margin-bottom:0.75rem;" data-animate="fade-up">
      Follow <span style="color:var(--color-gold);">Lotoks</span>
    </h2>
    <p style="color:rgba(11,29,58,0.6);margin-bottom:2rem;" data-animate="fade-up" data-delay="100">
      Stay connected with us on social media for the latest opportunities and updates.
    </p>
    <div style="display:flex;flex-wrap:wrap;align-items:center;justify-content:center;gap:1rem;" data-animate="fade-up" data-delay="200">
      <?php
      $socials = [
        ['href' => 'https://x.com/LotoksConsult',               'label' => 'X (Twitter)',  'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>'],
        ['href' => 'https://www.instagram.com/lotoks_projects/','label' => 'Instagram',    'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>'],
        ['href' => 'https://facebook.com/lotoks',                'label' => 'Facebook',     'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>'],
        ['href' => 'https://linkedin.com/company/lotoks',        'label' => 'LinkedIn',     'icon' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>'],
      ];
      foreach ($socials as $social): ?>
        <a href="<?= htmlspecialchars($social['href']) ?>" target="_blank" rel="noopener noreferrer"
           class="social-link"
           aria-label="<?= htmlspecialchars($social['label']) ?>">
          <span style="color:var(--color-gold);"><?= $social['icon'] ?></span>
          <span style="font-weight:500;color:rgba(11,29,58,0.7);"><?= htmlspecialchars($social['label']) ?></span>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     FINAL CTA — from FinalCTA()
     ════════════════════════════════════════════════════════════ -->
<section class="cta-section bg-navy" style="padding-block:6rem;padding-inline:1rem;">
  <div class="cta-blob"></div>
  <div style="position:relative;z-index:10;max-width:48rem;margin-inline:auto;text-align:center;" data-animate="fade-up">
    <h2 style="font-family:var(--font-heading);font-size:clamp(2rem,5vw,3rem);font-weight:700;color:#fff;margin-bottom:1.5rem;">
      Start Your <span style="color:var(--color-gold);">Journey</span> Today
    </h2>
    <p style="font-size:1.25rem;color:rgba(255,255,255,0.7);margin-bottom:2.5rem;">
      Join thousands of successful applicants who have realized their dreams of global mobility. Your future is just a click away.
    </p>
    <div style="display:flex;flex-direction:column;align-items:center;gap:1rem;justify-content:center;">
      <a href="<?= BASE ?>/eligibility.php" class="btn btn-primary btn-xl btn-pill">Check Eligibility Now</a>
      <a href="<?= BASE ?>/contact.php"     class="btn btn-secondary btn-xl btn-pill">Talk to Us</a>
    </div>
  </div>
</section>
<style>
@media (min-width:640px) {
  .cta-section > div > div:last-child { flex-direction: row !important; }
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<?php require_once __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
