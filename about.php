<?php
/**
 * Lotoks — About Us (about.php)
 * Converted from pages/About.tsx
 */
require_once __DIR__ . '/includes/auth.php';
redirect_if_logged_in();

$page_title       = 'About Us | Lotoks';
$page_description = 'Empowering global mobility through innovation and trust. Learn about the mission, values, and team behind Lotoks.';
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<!-- ════════════════════════════════════════════════════════════
     HERO SECTION
     ════════════════════════════════════════════════════════════ -->
<section class="page-hero">
  <div class="hero-overlay-img">
    <img src="<?= BASE ?>/public/images/Aboutus-background.png" alt="About Us Background" loading="eager" />
  </div>
  <div class="hero-overlay-dark"></div>

  <div class="container" style="position:relative; z-index:10; text-align:center;">
    <h1 style="font-size:clamp(2.5rem, 6vw, 4rem); color:#fff; margin-bottom:1rem; font-family:var(--font-heading); font-weight:700;" data-animate="fade-up">
      About Us
    </h1>
    <p style="font-size:clamp(1.1rem, 2vw, 1.4rem); color:rgba(255,255,255,0.8); max-width:42rem; margin-inline:auto;" data-animate="fade-up" data-delay="100">
      Empowering global mobility through innovation and trust
    </p>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     MISSION & VISION SECTION
     ════════════════════════════════════════════════════════════ -->
<section class="section-wrapper" style="background:linear-gradient(to bottom, var(--color-surface), #fff);">
  <div class="container">
    <div style="display:grid; gap:3rem; align-items:center;" class="mission-grid">
      
      <!-- Text content -->
      <div data-animate="fade-up">
        <div style="position:relative;">
          <div style="position:absolute; top:-1rem; left:-1rem; width:6rem; height:6rem; background:rgba(201,164,75,0.1); border-radius:50%; filter:blur(24px); pointer-events:none;"></div>
          
          <h2 style="font-family:var(--font-heading); font-size:clamp(2rem, 4vw, 2.5rem); font-weight:700; color:var(--color-navy); margin-bottom:1.5rem; position:relative;">
            Our Vision & Mission
          </h2>

          <div style="margin-bottom:1.5rem;">
            <h3 style="font-family:var(--font-heading); font-size:1.25rem; font-weight:700; color:var(--color-gold); margin-bottom:0.5rem;">Our Vision</h3>
            <p style="color:var(--color-on-surface-variant); leading-relaxed:1.6;">
              To become a globally recognized and trusted consulting and recruitment agency that provides professional, efficient, and ethical recruitment solutions while empowering individuals through international employment and educational opportunities.
            </p>
          </div>

          <div style="margin-bottom:2rem;">
            <h3 style="font-family:var(--font-heading); font-size:1.25rem; font-weight:700; color:var(--color-teal); margin-bottom:0.5rem;">Our Mission</h3>
            <p style="color:var(--color-on-surface-variant); leading-relaxed:1.6;">
              To provide reliable, transparent, and professional consulting and recruitment services in accordance with international standards, labour regulations, and ethical business practices.
            </p>
          </div>

          <!-- Bullet checklist -->
          <div style="display:flex; flex-direction:column; gap:0.75rem;">
            <?php
            $bullets = [
              "Federation of African Professional Staffing Organisations (APSO) Accredited",
              "Verified sponsorship opportunities abroad",
              "Transparent, ethical recruitment processes",
              "Dedicated documentation & mobility guidance support",
              "Registration with the Department of Labour South Africa for local employment opportunities"
            ];
            foreach ($bullets as $b): ?>
              <div style="display:flex; align-items:center; gap:0.75rem;">
                <span style="color:var(--color-teal); display:inline-flex; align-items:center;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                </span>
                <span style="color:rgba(11,29,58,0.85); font-weight:500; font-size:0.95rem;"><?= htmlspecialchars($b) ?></span>
              </div>
            <?php endforeach; ?>
          </div>

        </div>
      </div>

      <!-- Image -->
      <div data-animate="fade-up" data-delay="150" style="position:relative;">
        <div style="aspect-ratio:1/1; border-radius:1.5rem; overflow:hidden; box-shadow:var(--shadow-navy);">
          <img src="<?= BASE ?>/public/images/unsplash/1522071820081-009f0129c71c-800x800.jpg" alt="Our team collaborating" style="width:100%; height:100%; object-fit:cover;" />
        </div>
        <div style="position:absolute; bottom:-1.5rem; right:-1.5rem; width:12rem; height:12rem; background:rgba(201,164,75,0.1); border-radius:50%; filter:blur(40px); pointer-events:none; z-index:-1;"></div>
      </div>

    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     VALUES SECTION
     ════════════════════════════════════════════════════════════ -->
<section class="section-wrapper bg-navy">
  <div class="container">
    <div class="section-heading center light" data-animate="fade-up">
      <h2>Our Values</h2>
      <p>The principles that guide everything we do</p>
    </div>

    <div style="display:grid; gap:2rem;" class="values-grid">
      <?php
      $values = [
        [
          'title' => 'Transparency',
          'desc'  => 'We believe in complete honesty and clarity throughout your journey.',
          'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 9.7a1 1 0 0 1-.68 0C7.5 20.5 4 18 4 13V6a1 1 0 0 1 .76-.97l8-2a1 1 0 0 1 .48 0l8 2A1 1 0 0 1 20 6z"/></svg>',
        ],
        [
          'title' => 'Speed',
          'desc'  => 'We optimize every step to get you results as quickly as possible.',
          'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
        ],
        [
          'title' => 'Global Access',
          'desc'  => 'We open doors to opportunities across 150+ countries worldwide.',
          'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>',
        ],
      ];
      foreach ($values as $i => $v): ?>
        <div class="glass-card" data-animate="fade-up" data-delay="<?= $i * 150 ?>" style="text-align:center; display:flex; flex-direction:column; justify-content:center;">
          <div style="width:4rem; height:4rem; border-radius:1rem; background:rgba(201,164,75,0.1); display:flex; align-items:center; justify-content:center; margin-inline:auto; margin-bottom:1.5rem; color:var(--color-gold);">
            <?= $v['icon'] ?>
          </div>
          <h3 style="font-family:var(--font-heading); font-size:1.25rem; font-weight:700; color:#fff; margin-bottom:0.75rem;">
            <?= htmlspecialchars($v['title']) ?>
          </h3>
          <p style="color:rgba(255,255,255,0.7); font-size:0.95rem; line-height:1.6;">
            <?= htmlspecialchars($v['desc']) ?>
          </p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     OUR JOURNEY (TIMELINE) SECTION
     ════════════════════════════════════════════════════════════ -->
<section class="section-wrapper" style="background:#fff;">
  <div class="container">
    <div class="section-heading center dark" data-animate="fade-up">
      <h2>Our Journey</h2>
      <p>From a small startup to a global platform</p>
    </div>

    <!-- Timeline Wrapper -->
    <div style="position:relative; max-width:56rem; margin-inline:auto; padding-block:1rem;">
      <!-- Vertical line (desktop only) -->
      <div class="timeline-line"></div>

      <div style="display:flex; flex-direction:column; gap:3.5rem;">
        <?php
        $timeline = [
          ["year" => "2019", "title" => "Foundation", "description" => "Lotoks was founded with a vision to democratize global mobility."],
          ["year" => "2020", "title" => "First Partnership", "description" => "Established partnerships with 50+ universities and employers worldwide."],
          ["year" => "2021", "title" => "Tech Platform Launch", "description" => "Launched our proprietary AI-powered matching platform."],
          ["year" => "2022", "title" => "Global Expansion", "description" => "Expanded operations to 100+ countries with 500+ partner organizations."],
          ["year" => "2023", "title" => "50K Milestone", "description" => "Helped 50,000+ applicants achieve their international dreams."],
          ["year" => "2024", "title" => "Industry Leader", "description" => "Recognized as the leading global sponsorship platform worldwide."]
        ];
        foreach ($timeline as $idx => $t):
          $isLeft = $idx % 2 === 0;
        ?>
          <div class="timeline-item <?= $isLeft ? 'left-aligned' : 'right-aligned' ?>" data-animate="fade-up">
            <!-- Timeline Dot -->
            <div class="timeline-dot"></div>

            <!-- Content Card -->
            <div class="timeline-card-content">
              <div style="color:var(--color-gold); font-weight:700; font-size:1.15rem; margin-bottom:0.25rem; font-family:var(--font-heading);"><?= $t['year'] ?></div>
              <h3 style="font-family:var(--font-heading); font-size:1.25rem; font-weight:700; color:var(--color-navy); margin-bottom:0.5rem;"><?= htmlspecialchars($t['title']) ?></h3>
              <p style="color:var(--color-on-surface-variant); font-size:0.95rem; line-height:1.6;"><?= htmlspecialchars($t['description']) ?></p>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     MEET OUR TEAM SECTION
     ════════════════════════════════════════════════════════════ -->
<section class="section-wrapper" style="background:linear-gradient(to bottom, #fff, var(--color-surface));">
  <div class="container">
    <div class="section-heading center dark" data-animate="fade-up">
      <h2>Meet Our Team</h2>
      <p>The passionate people behind Lotoks</p>
    </div>

    <div style="display:grid; gap:2rem;" class="team-grid">
      <?php
      $team = [
        [
          "name"  => "TR Ngwenya",
          "role"  => "Founder & Global Recruitment Director",
          "image" => "./public/Team-members/Thobekile-Ruth-Ngwenya-ceo-lotoks.png",
          "bio"   => "She leads Lotoks with a visionary approach, integrating high-end technological innovations with robust operational strategies to shape the future of global enterprise solutions."
        ],
        [
          "name"  => "R Veremu",
          "role"  => "Corporate Relations & Business Dev Director",
          "image" => "./public/Team-members/Ronald-Veremu.png",
          "bio"   => "Specializing in next-gen interactive systems, cloud computing, and high-performance databases."
        ],
        [
          "name"  => "SL Ndlovu",
          "role"  => "Head of International Placements",
          "image" => "./public/Team-members/Lynette-Ndlovu.jpeg",
          "bio"   => "Streamlining enterprise processes and driving operational performance across international teams."
        ],
        [
          "name"  => "K Ngcobo",
          "role"  => "Head of Candidate Local Sourcing",
          "image" => "./public/Team-members/Karabo-Ngcobo.png",
          "bio"   => "Expert in scalable systems architecture, leading our engineering department with modern agile methodologies."
        ],
        [
          "name"  => "RR Nyathi",
          "role"  => "Candidate Sourcing & Data Assistant",
          "image" => "./public/Team-members/Rethabile-Ruth-Nyathi.jpeg",
          "bio"   => "Crafting beautiful, modern, and human-centric user experiences that wow clients at first glance."
        ]
      ];
      foreach ($team as $idx => $m): ?>
        <div class="team-member" data-animate="fade-up" data-delay="<?= $idx * 80 ?>">
          <div class="team-card-inner" style="position:relative; overflow:hidden; border-radius:1rem; aspect-ratio:1/1; box-shadow:var(--shadow-card); background:var(--color-navy); margin-bottom:1rem;">
            <img src="<?= $m['image'] ?>" alt="<?= htmlspecialchars($m['name']) ?>" style="width:100%; height:100%; object-fit:cover; transition:transform 0.5s;" />
            <div class="team-bio-overlay" style="position:absolute; inset:0; background:rgba(11,29,58,0.92); padding:1.5rem; display:flex; align-items:flex-end; opacity:0; transition:all 0.3s ease;">
              <p style="color:#fff; font-size:0.875rem; line-height:1.5; font-weight:500; transform:translateY(10px); transition:transform 0.3s ease;" class="bio-text">
                <?= htmlspecialchars($m['bio']) ?>
              </p>
            </div>
          </div>
          <h3 style="font-family:var(--font-heading); font-size:1.15rem; font-weight:700; color:var(--color-navy); margin-bottom:0.25rem;">
            <?= htmlspecialchars($m['name']) ?>
          </h3>
          <p style="color:var(--color-gold); font-size:0.9rem; font-weight:600;">
            <?= htmlspecialchars($m['role']) ?>
          </p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     PARTNERS SECTION
     ════════════════════════════════════════════════════════════ -->
<section style="padding-block:6rem; background:linear-gradient(to bottom, var(--color-navy), rgba(11,29,58,0.95)); position:relative; overflow:hidden;">
  <div style="position:absolute; top:0; left:33%; width:min(24rem,60vw); height:min(24rem,60vw); background:rgba(201,164,75,0.05); border-radius:50%; filter:blur(80px); pointer-events:none;"></div>
  <div style="position:absolute; bottom:0; right:33%; width:min(20rem,50vw); height:min(20rem,50vw); background:rgba(29,122,122,0.05); border-radius:50%; filter:blur(80px); pointer-events:none;"></div>

  <div class="container" style="position:relative; z-index:10;">
    <div style="text-align:center; margin-bottom:4rem;" data-animate="fade-up">
      <div style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.5rem 1rem; border-radius:9999px; background:rgba(201,164,75,0.1); border:1px solid rgba(201,164,75,0.2); margin-bottom:1.25rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 18a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2"></path><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><circle cx="12" cy="10" r="3"></circle></svg>
        <span style="font-size:0.75rem; font-weight:700; color:var(--color-gold); letter-spacing:0.1em; text-transform:uppercase;">Partners &amp; Accreditations</span>
      </div>
      <h2 style="font-family:var(--font-heading); font-size:clamp(2rem, 4vw, 2.5rem); font-weight:700; color:#fff; margin-bottom:1rem;">Institutions We Work With</h2>
      <p style="color:rgba(255,255,255,0.6); max-width:36rem; margin-inline:auto;">
        Lotoks is proudly accredited and partnered with leading universities, placement agencies, and professional bodies across the globe.
      </p>
    </div>

    <div style="display:grid; gap:2rem;" class="partners-grid-about">
      <?php
      $partners = [
        [
          "name"   => "Everest Educational Services",
          "logo"   => "./public/Everest-logo/Everest.jpeg",
          "desc"   => "Your trusted partner for international education. Connecting students worldwide with top academic programs and expert guidance for study abroad.",
          "website"=> "https://everestedu.ca/",
          "tag"    => "Education Partner"
        ],
        [
          "name"   => "UITM – University of Information Technology and Management",
          "logo"   => "./public/images/partners/uitm-logo.svg",
          "desc"   => "A leading Polish university offering world-class degree programs in IT and management. Recognised internationally for academic excellence in Rzeszów, Poland.",
          "website"=> "https://en.uitm.edu.eu/",
          "tag"    => "University Partner"
        ],
        [
          "name"   => "APSO – African Professional Staffing Organisations",
          "logo"   => "./public/images/partners/apso-logo.png",
          "desc"   => "The Federation of African Professional Staffing Organisations, dedicated to setting ethical standards and empowering staffing professionals across Africa.",
          "website"=> "https://apso.org.za/",
          "tag"    => "Accreditation Body"
        ]
      ];
      foreach ($partners as $idx => $p): ?>
        <a href="<?= htmlspecialchars($p['website']) ?>" target="_blank" rel="noopener noreferrer" 
           class="partner-card" 
           data-animate="fade-up" 
           data-delay="<?= $idx * 150 ?>"
           style="background:#b7974a; border:1px solid rgba(183,151,74,0.4); border-radius:1rem; padding:2rem; display:flex; flex-direction:column; position:relative; overflow:hidden; text-decoration:none; transition:all 0.3s ease;">
          
          <!-- Diagonal Sweep Shine -->
          <div class="partner-card-shine"></div>

          <span class="partner-tag" style="align-self:flex-start; font-size:0.75rem; font-weight:700; color:var(--color-navy); bg:rgba(11,29,58,0.1); border:1px solid rgba(11,29,58,0.2); padding:0.25rem 0.75rem; border-radius:9999px; margin-bottom:1.5rem;">
            <?= htmlspecialchars($p['tag']) ?>
          </span>

          <div style="height:5rem; background:rgba(255,255,255,0.95); border-radius:0.75rem; padding:0.75rem; display:flex; align-items:center; justify-content:center; margin-bottom:1.5rem; transition:transform 0.3s ease;" class="logo-box">
            <img src="<?= htmlspecialchars($p['logo']) ?>" alt="<?= htmlspecialchars($p['name']) ?>" style="max-height:100%; max-width:100%; object-fit:contain;" />
          </div>

          <h3 style="font-family:var(--font-heading); font-weight:800; font-size:1rem; color:var(--color-navy); margin-bottom:0.75rem; line-height:1.4;">
            <?= htmlspecialchars($p['name']) ?>
          </h3>

          <p style="color:rgba(11,29,58,0.9); font-size:0.875rem; line-height:1.6; font-weight:500; flex:1;">
            <?= htmlspecialchars($p['desc']) ?>
          </p>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     STATS SECTION
     ════════════════════════════════════════════════════════════ -->
<section class="section-wrapper bg-surface" style="background:rgba(11,29,58,0.03);">
  <div class="container">
    <div style="display:grid; gap:1.5rem;" class="stats-grid-about">
      <?php
      $stats = [
        ["number" => "150", "suffix" => "+", "label" => "Countries Covered"],
        ["number" => "98",  "suffix" => "%", "label" => "Success Rate"],
        ["number" => "24",  "suffix" => "h", "label" => "Average Response"]
      ];
      foreach ($stats as $idx => $st): ?>
        <div class="stat-card" data-animate="scale-in" data-delay="<?= $idx * 100 ?>" style="background:#fff; border-radius:1rem; padding:2rem; box-shadow:var(--shadow-card); border:1px solid rgba(11,29,58,0.05); text-align:center;">
          <div class="stat-number" style="font-size:3rem; font-weight:700; color:var(--color-gold); display:flex; align-items:center; justify-content:center; margin-bottom:0.5rem;">
            <span
              data-count="<?= htmlspecialchars($st['number']) ?>"
              data-suffix="<?= htmlspecialchars($st['suffix']) ?>"
              data-duration="2000"
            >0<?= htmlspecialchars($st['suffix']) ?></span>
          </div>
          <div class="stat-label" style="color:rgba(11,29,58,0.6); font-weight:600; font-size:0.95rem;"><?= htmlspecialchars($st['label']) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     CTA SECTION
     ════════════════════════════════════════════════════════════ -->
<section class="section-wrapper bg-navy" style="position:relative; overflow:hidden;">
  <div style="position:absolute; top:0; left:0; width:100%; height:100%; background:radial-gradient(circle at center, rgba(201,164,75,0.08) 0%, transparent 70%); pointer-events:none;"></div>
  
  <div class="container" style="position:relative; z-index:10; text-align:center; max-width:42rem;">
    <h2 style="font-family:var(--font-heading); font-size:clamp(2rem, 5vw, 3rem); font-weight:700; color:#fff; margin-bottom:1.5rem;" data-animate="fade-up">
      Ready to Start Your Journey?
    </h2>
    <p style="font-size:1.15rem; color:rgba(255,255,255,0.7); margin-bottom:2.5rem;" data-animate="fade-up" data-delay="100">
      Let us help you achieve your global mobility goals. Get in touch today.
    </p>
    <div style="display:flex; flex-direction:column; justify-content:center; gap:1rem; align-items:center;" class="cta-btns" data-animate="fade-up" data-delay="200">
      <a href="<?= BASE ?>/eligibility.php" class="btn btn-primary btn-lg btn-pill" style="min-width:14rem;">
        Check Eligibility
      </a>
      <a href="<?= BASE ?>/contact.php" class="btn btn-secondary btn-lg btn-pill" style="min-width:14rem;">
        Contact Us
      </a>
    </div>
  </div>
</section>

<!-- Styles for responsiveness -->
<style>
/* Mission layout */
@media (min-width: 768px) {
  .mission-grid { grid-template-columns: 1fr 1fr; }
}
@media (min-width: 1024px) {
  .mission-grid { grid-template-columns: 1.2fr 0.8fr; }
}

/* Values layout */
.values-grid { grid-template-columns: 1fr; }
@media (min-width: 768px) {
  .values-grid { grid-template-columns: repeat(3, 1fr); }
}

/* Timeline Layout */
.timeline-line {
  position: absolute;
  top: 0;
  bottom: 0;
  left: 1rem;
  width: 2px;
  background: linear-gradient(to bottom, rgba(201,164,75,0), rgba(201,164,75,0.5), rgba(201,164,75,0));
}
.timeline-item {
  position: relative;
  display: flex;
  flex-direction: column;
  padding-left: 2.5rem;
}
.timeline-dot {
  position: absolute;
  left: 0.5rem;
  top: 0.5rem;
  transform: none;
  width: 1rem;
  height: 1rem;
  border-radius: 50%;
  background: var(--color-gold);
  box-shadow: 0 0 10px var(--color-gold);
  z-index: 10;
}
.timeline-card-content {
  background: var(--color-surface-container-lowest);
  border: 1px solid rgba(11,29,58,0.08);
  border-radius: 1rem;
  padding: 1.5rem;
  box-shadow: var(--shadow-card);
}

@media (min-width: 768px) {
  .timeline-line {
    left: 50%;
    transform: translateX(-50%);
  }
  .timeline-item {
    flex-direction: row;
    padding-left: 0;
    width: 100%;
  }
  .timeline-dot {
    left: 50%;
    transform: translateX(-50%);
  }
  .timeline-item.left-aligned {
    justify-content: flex-end;
  }
  .timeline-item.right-aligned {
    justify-content: flex-start;
  }
  .timeline-item.left-aligned .timeline-card-content {
    width: calc(50% - 2rem);
    text-align: right;
  }
  .timeline-item.right-aligned .timeline-card-content {
    width: calc(50% - 2rem);
    margin-left: auto;
    margin-right: 0;
    text-align: left;
  }
}

/* Team Grid */
.team-grid { grid-template-columns: 1fr; }
@media (min-width: 576px) {
  .team-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (min-width: 576px) and (max-width: 991px) {
  .team-grid > .team-member:last-child:nth-child(odd) {
    grid-column: 1 / -1;
    max-width: 280px;
    margin-inline: auto;
  }
}
@media (min-width: 992px) {
  .team-grid { grid-template-columns: repeat(5, 1fr); }
}

.team-member:hover img {
  transform: scale(1.06);
}
.team-member:hover .team-bio-overlay {
  opacity: 1 !important;
}
.team-member:hover .bio-text {
  transform: translateY(0) !important;
}

/* Partner Grid */
.partners-grid-about { grid-template-columns: 1fr; }
@media (min-width: 768px) {
  .partners-grid-about { grid-template-columns: repeat(3, 1fr) !important; }
}

.partner-card-shine {
  position: absolute;
  top: 0;
  left: -150%;
  width: 200%;
  height: 100%;
  background: linear-gradient(to right, transparent, rgba(255,255,255,0.2), transparent);
  transform: skewX(-12deg);
  pointer-events: none;
  transition: left 0.85s ease-out;
}
.partner-card:hover .partner-card-shine {
  left: 100%;
}
.partner-card:hover {
  border-color: rgba(255,255,255,0.4) !important;
  box-shadow: 0 20px 45px rgba(183,151,74,0.35);
  transform: translateY(-5px);
}
.partner-card:hover .logo-box {
  transform: scale(1.05);
}

/* Stats layout */
.stats-grid-about { grid-template-columns: 1fr; }
@media (min-width: 576px) {
  .stats-grid-about { grid-template-columns: repeat(3, 1fr) !important; }
}

/* CTA buttons layout */
.cta-btns { flex-direction: column; }
@media (min-width: 576px) {
  .cta-btns { flex-direction: row !important; }
}
</style>

<?php
require_once __DIR__ . '/includes/footer.php';
require_once __DIR__ . '/includes/scripts.php';
?>
</body>
</html>
