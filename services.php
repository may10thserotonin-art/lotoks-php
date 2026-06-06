<?php
/**
 * Lotoks — Our Services (services.php)
 * Converted from pages/Services.tsx
 */
require_once __DIR__ . '/includes/auth.php';

$page_title       = 'Our Services | Lotoks';
$page_description = 'Comprehensive solutions for all your global mobility needs. Visa sponsorships, scholarships, work placement contracts, and permanent residency programs.';
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/navbar.php';

// Check default tab based on URL query/hash if desired
$active_tab = 'visa';
?>

<!-- ════════════════════════════════════════════════════════════
     HERO SECTION
     ════════════════════════════════════════════════════════════ -->
<section class="page-hero">
  <div class="hero-overlay-img">
    <img src="<?= BASE ?>/public/images/Ourservices-.png" alt="Our Services Background" loading="eager" />
  </div>
  <div class="hero-overlay-dark"></div>

  <div class="container" style="position:relative; z-index:10; text-align:center;">
    <h1 style="font-size:clamp(2.5rem, 6vw, 4rem); color:#fff; margin-bottom:1rem; font-family:var(--font-heading); font-weight:700;" data-animate="fade-up">
      Our Services
    </h1>
    <p style="font-size:clamp(1.1rem, 2vw, 1.4rem); color:rgba(255,255,255,0.8); max-width:42rem; margin-inline:auto;" data-animate="fade-up" data-delay="100">
      Comprehensive solutions for all your global mobility needs
    </p>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     SERVICES NAVIGATION & TABS
     ════════════════════════════════════════════════════════════ -->
<section class="section-wrapper">
  <div class="container">
    
    <!-- Tab buttons -->
    <div class="services-tab-buttons" data-animate="fade-up">
      <button class="tab-btn active" data-tab="visa">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
        Visa Sponsorship
      </button>
      <button class="tab-btn" data-tab="education">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
        Education
      </button>
      <button class="tab-btn" data-tab="jobs">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
        Job Placements
      </button>
      <button class="tab-btn" data-tab="residence">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
        Residence
      </button>
    </div>

    <!-- Tab Contents wrapper -->
    <div style="margin-top:3rem;">
      <?php
      $servicesData = [
        "visa" => [
          "title"       => "Visa Sponsorship",
          "subtitle"    => "Work, study, and travel visas with verified sponsors",
          "description" => "Our visa sponsorship program connects you with verified employers and organizations willing to sponsor your visa application. We streamline the entire process, making it seamless and stress-free.",
          "image"       => "./public/images/Visa-sponsorship.jpg",
          "benefits"    => [
            "Verified sponsor network across 50+ countries",
            "Dedicated case manager for personalized support",
            "Fast-track processing with 98% approval rate",
            "Post-visa relocation assistance included",
            "Legal support throughout the application",
            "Real-time application tracking"
          ],
          "process"     => [
            ["step" => 1, "title" => "Profile Assessment", "desc" => "We evaluate your qualifications"],
            ["step" => 2, "title" => "Match with Sponsor", "desc" => "Find the right sponsor for you"],
            ["step" => 3, "title" => "Document Preparation", "desc" => "We help prepare all required docs"],
            ["step" => 4, "title" => "Application Submission", "desc" => "Submit to immigration authorities"],
            ["step" => 5, "title" => "Visa Approval", "desc" => "Get your visa and start your journey"]
          ]
        ],
        "education" => [
          "title"       => "Education Scholarships",
          "subtitle"    => "Full and partial scholarships at top universities worldwide",
          "description" => "Access world-class education with our comprehensive scholarship matching service. We partner with universities and organizations to bring you funded opportunities that match your profile.",
          "image"       => "./public/images/Educational-scholarship.jpg",
          "benefits"    => [
            "Access to 1000+ scholarship programs",
            "Full and partial funding options",
            "Scholarship guaranteed admission support",
            "Application essay assistance",
            "Interview preparation coaching",
            "Post-admission visa sponsorship"
          ],
          "process"     => [
            ["step" => 1, "title" => "Academic Profile Review", "desc" => "Assess your academic background"],
            ["step" => 2, "title" => "Scholarship Search", "desc" => "Find matching opportunities"],
            ["step" => 3, "title" => "Application Support", "desc" => "Complete applications with guidance"],
            ["step" => 4, "title" => "Interview Preparation", "desc" => "Ace your scholarship interviews"],
            ["step" => 5, "title" => "Acceptance & Funding", "desc" => "Secure your scholarship offer"]
          ]
        ],
        "jobs" => [
          "title"       => "Job Placements",
          "subtitle"    => "Connect with employers offering sponsorship packages",
          "description" => "Find your dream job with companies willing to sponsor your work visa. Our job matching algorithm pairs you with opportunities that align with your skills and career goals.",
          "image"       => "./public/images/job-placement.jpg",
          "benefits"    => [
            "Exclusive job listings with sponsorship",
            "Direct hiring from top employers",
            "Resume optimization services",
            "Interview preparation and coaching",
            "Salary negotiation support",
            "Relocation assistance"
          ],
          "process"     => [
            ["step" => 1, "title" => "Career Assessment", "desc" => "Understand your career goals"],
            ["step" => 2, "title" => "Job Matching", "desc" => "Get matched with suitable positions"],
            ["step" => 3, "title" => "Interview Prep", "desc" => "Prepare for employer interviews"],
            ["step" => 4, "title" => "Offer Negotiation", "desc" => "Negotiate terms and packages"],
            ["step" => 5, "title" => "Visa Sponsorship", "desc" => "Secure work visa sponsorship"]
          ]
        ],
        "residence" => [
          "title"       => "Permanent Residence",
          "subtitle"    => "Pathways to citizenship through investment and work",
          "description" => "Our residence and citizenship by investment programs help you obtain permanent residency or citizenship in your desired country through legitimate pathways.",
          "image"       => "./public/images/permanent-resident.jpg",
          "benefits"    => [
            "Multiple pathway options available",
            "Investment-based citizenship programs",
            "Work-based residence permits",
            "Family inclusion options",
            "Tax planning assistance",
            "Post-grant integration support"
          ],
          "process"     => [
            ["step" => 1, "title" => "Eligibility Assessment", "desc" => "Determine best pathway"],
            ["step" => 2, "title" => "Program Selection", "desc" => "Choose your target country"],
            ["step" => 3, "title" => "Investment Processing", "desc" => "Complete required investments"],
            ["step" => 4, "title" => "Application Submission", "desc" => "Submit residence application"],
            ["step" => 5, "title" => "Approval & Settlement", "desc" => "Obtain residence permit"]
          ]
        ]
      ];

      foreach ($servicesData as $key => $srv):
        $visible = ($key === $active_tab);
      ?>
        <div class="tab-content" id="tab-content-<?= $key ?>" style="display: <?= $visible ? 'block' : 'none' ?>;" data-animate="fade-up">
          
          <!-- Tab Inner Header -->
          <div class="tab-hero-card" style="background-image: linear-gradient(to right, rgba(11,29,58,0.85), rgba(11,29,58,0.4)), url('<?= $srv['image'] ?>');">
            <div class="tab-hero-text">
              <h2><?= htmlspecialchars($srv['title']) ?></h2>
              <p><?= htmlspecialchars($srv['subtitle']) ?></p>
            </div>
          </div>

          <!-- Description -->
          <div style="margin-block: 3.5rem;">
            <p style="font-size: 1.15rem; color: var(--color-on-surface-variant); max-width: 48rem; line-height: 1.6;">
              <?= htmlspecialchars($srv['description']) ?>
            </p>
          </div>

          <!-- Two Column layout: Benefits vs How It Works -->
          <div style="display:grid; grid-template-columns:1fr; gap:3rem;" class="service-details-grid">
            
            <!-- Left Column: Benefits -->
            <div>
              <h3 style="font-family:var(--font-heading); font-size:1.5rem; font-weight:700; color:var(--color-navy); margin-bottom:1.5rem;">Key Benefits</h3>
              <div style="display:flex; flex-direction:column; gap:1rem;">
                <?php foreach ($srv['benefits'] as $b): ?>
                  <div style="display:flex; align-items:flex-start; gap:0.75rem;">
                    <span style="color:var(--color-teal); margin-top:0.2rem; display:inline-flex;">
                      <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    </span>
                    <span style="color:var(--color-on-surface-variant); font-size:0.95rem; font-weight:500;"><?= htmlspecialchars($b) ?></span>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

            <!-- Right Column: Process Timeline -->
            <div>
              <h3 style="font-family:var(--font-heading); font-size:1.5rem; font-weight:700; color:var(--color-navy); margin-bottom:1.5rem;">How It Works</h3>
              <div style="display:flex; flex-direction:column; gap:1.25rem;">
                <?php foreach ($srv['process'] as $proc): ?>
                  <div style="display:flex; align-items:center; gap:1rem;">
                    <div style="width:2.5rem; height:2.5rem; border-radius:50%; background:var(--color-gold); color:var(--color-navy); display:flex; align-items:center; justify-content:center; font-family:var(--font-heading); font-weight:700; font-size:1rem; flex-shrink:0;">
                      <?= $proc['step'] ?>
                    </div>
                    <div>
                      <h4 style="font-family:var(--font-heading); font-size:0.95rem; font-weight:700; color:var(--color-navy); margin-bottom:0.15rem;"><?= htmlspecialchars($proc['title']) ?></h4>
                      <p style="color:rgba(11,29,58,0.55); font-size:0.85rem;"><?= htmlspecialchars($proc['desc']) ?></p>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>

          </div>

          <!-- Tab CTA button -->
          <div style="text-align:center; margin-top:4rem;">
            <a href="<?= BASE ?>/eligibility.php" class="btn btn-primary btn-lg btn-pill arrow-parent">
              Get Started Now
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="arrow-hover"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
          </div>

          <!-- IF Job Placements, render Job Listings Grid -->
          <?php if ($key === 'jobs'): ?>
            <div style="margin-top:6rem; padding-top:4rem; border-top:1px solid rgba(11,29,58,0.1);" data-animate="fade-up">
              <h3 style="font-family:var(--font-heading); font-size:2rem; font-weight:700; color:var(--color-navy); text-align:center; margin-bottom:3.5rem;">
                Available Job Categories
              </h3>
              
              <div style="display:grid; grid-template-columns:1fr; gap:1.5rem;" class="jobs-categories-grid">
                <?php
                $jobListings = [
                  ["title" => "Nurse",                    "icon" => "stethoscope", "image" => "./public/images/jobs/nurse.jpg",           "desc" => "ICU, ward & community nursing roles with full sponsorship", "tag" => "Healthcare"],
                  ["title" => "Bike Riders",              "icon" => "bike",        "image" => "./public/images/jobs/bike-rider.jpg",      "desc" => "Delivery and courier positions across major cities",          "tag" => "Logistics"],
                  ["title" => "Truck Drivers",            "icon" => "truck",       "image" => "./public/images/jobs/truck-driver.jpg",     "desc" => "Long-haul and local freight transport opportunities",         "tag" => "Transport"],
                  ["title" => "IT / Tech Professionals",  "icon" => "laptop",      "image" => "./public/images/jobs/it-tech.jpg",          "desc" => "Software, DevOps, cybersecurity and data roles",              "tag" => "Technology"],
                  ["title" => "Construction Workers",     "icon" => "hard-hat",    "image" => "./public/images/jobs/construction.jpg",     "desc" => "Site, civil and structural construction positions",            "tag" => "Construction"],
                  ["title" => "Healthcare Workers",       "icon" => "heart-pulse", "image" => "./public/images/jobs/healthcare.jpg",       "desc" => "Doctors, paramedics and allied health professionals",         "tag" => "Healthcare"],
                  ["title" => "Engineers",                "icon" => "settings",    "image" => "https://images.unsplash.com/photo-1581092921461-eab62e97a780?w=600&q=80", "desc" => "Civil, mechanical, electrical and project engineering", "tag" => "Engineering"],
                  ["title" => "Factory Workers",          "icon" => "factory",     "image" => "https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?w=600&q=80", "desc" => "Production line and manufacturing plant roles",       "tag" => "Manufacturing"],
                  ["title" => "Warehouse Workers",        "icon" => "package",     "image" => "https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?w=600&q=80", "desc" => "Pick, pack, dispatch and inventory management",        "tag" => "Logistics"],
                  ["title" => "Agriculture",              "icon" => "sprout",      "image" => "https://images.unsplash.com/photo-1464226184884-fa280b87c399?w=600&q=80", "desc" => "Farming, crop management and agri-tech roles",        "tag" => "Agriculture"],
                  ["title" => "Mining & Resources",       "icon" => "hammer",      "image" => "https://images.unsplash.com/photo-1553361371-9b22f78e8b1d?w=600&q=80", "desc" => "Underground, open-cut and resources extraction jobs", "tag" => "Mining"],
                  ["title" => "Transport & Logistics",    "icon" => "ship",        "image" => "https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?w=600&q=80", "desc" => "Freight, shipping and supply chain coordination",       "tag" => "Transport"],
                  ["title" => "Finance & Accounting",     "icon" => "coins",       "image" => "https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=600&q=80", "desc" => "Accounting, audit, banking and financial analyst roles", "tag" => "Finance"]
                ];
                foreach ($jobListings as $jobIdx => $job): ?>
                  <div class="job-category-card" onclick="window.location.href='/eligibility.php'">
                    <!-- Job Card Background image -->
                    <div class="job-card-image" style="background-image:url('<?= $job['image'] ?>');" onerror="this.style.background='#0B1D3A'"></div>
                    <!-- Overlay -->
                    <div class="job-card-gradient"></div>

                    <!-- Badges -->
                    <div class="job-card-top">
                      <span class="job-tag-badge"><?= htmlspecialchars($job['tag']) ?></span>
                      <div class="job-icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                          <?php if ($job['icon'] === 'stethoscope'): ?><path d="M4.8 2.3A.3.3 0 1 0 5 2H4a2 2 0 0 0-2 2v5a6 6 0 0 0 6 6v4a3 3 0 0 0 6 0v-4a6 6 0 0 0 6-6V4a2 2 0 0 0-2-2h-1a.3.3 0 1 0 .2.3M8 15h8"/></svg>
                          <?php elseif ($job['icon'] === 'bike'): ?><circle cx="5.5" cy="17.5" r="2.5"/><circle cx="18.5" cy="17.5" r="2.5"/><path d="M15 6h5v2h-4.3l-2.7 4.1-3.6-2.5L12 6.5M9.8 17.5h8.7M6.5 11.5L9.5 8h4.5"/></svg>
                          <?php elseif ($job['icon'] === 'truck'): ?><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                          <?php elseif ($job['icon'] === 'laptop'): ?><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="2" y1="20" x2="22" y2="20"/><line x1="12" y1="17" x2="12" y2="20"/></svg>
                          <?php elseif ($job['icon'] === 'hard-hat'): ?><path d="M2 18a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1v-2a8 8 0 0 0-16 0zM12 2v8M12 2a8 8 0 0 1 8 8M12 2a8 8 0 0 0-8 8"/></svg>
                          <?php elseif ($job['icon'] === 'heart-pulse'): ?><path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/><path d="M3.22 12H9.5l1.5-5 2 10 1.5-5h4.28"/></svg>
                          <?php elseif ($job['icon'] === 'settings'): ?><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                          <?php elseif ($job['icon'] === 'factory'): ?><path d="M2 20V8l6 4V8l6 4V8l8 5v7Z"/><rect x="4" y="15" width="3" height="5"/><rect x="10" y="15" width="3" height="5"/></svg>
                          <?php elseif ($job['icon'] === 'package'): ?><line x1="16.5" y1="9.4" x2="7.5" y2="4.21"/><polygon points="12 22.08 12 12 3 6.92 3 17.08 12 22.08"/><polygon points="12 12 21 6.92 21 17.08 12 22.08"/><polygon points="12 2 21 6.92 12 12 3 6.92 12 2"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
                          <?php elseif ($job['icon'] === 'sprout'): ?><path d="M7 20h10M12 20V8M12 8a6 6 0 0 1 6-6h2v2a6 6 0 0 1-6 6M12 10a6 6 0 0 0-6-6H4v2a6 6 0 0 0 6 6"/></svg>
                          <?php elseif ($job['icon'] === 'hammer'): ?><path d="m15 5 4 4M21.5 2.5a2.12 2.12 0 0 1 0 3L12 15l-4-4 9.5-9.5a2.12 2.12 0 0 1 3 0Z"/><path d="M7 16H3a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-4Z"/></svg>
                          <?php elseif ($job['icon'] === 'ship'): ?><path d="M2 21h20M19.3 14.8C21.1 13.5 22 11.7 22 10V3h-5v3H7V3H2v7c0 1.7.9 3.5 2.7 4.8L12 19l7.3-4.2Z"/></svg>
                          <?php else: ?><circle cx="8" cy="8" r="6"/><circle cx="18" cy="18" r="4"/><path d="M12 18a6 6 0 0 0-6-6M18 14a4 4 0 0 0-4-4"/></svg>
                          <?php endif; ?>
                        </svg>
                      </div>
                    </div>

                    <!-- Details content -->
                    <div class="job-card-details">
                      <h4><?= htmlspecialchars($job['title']) ?></h4>
                      <p><?= htmlspecialchars($job['desc']) ?></p>
                      <div class="job-apply-cta">
                        Apply Now
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                      </div>
                    </div>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>

        </div>
      <?php endforeach; ?>
    </div>

  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     ENTERPRISE TALENT SOLUTIONS SECTION
     ════════════════════════════════════════════════════════════ -->
<section class="section-wrapper bg-navy" style="position:relative; overflow:hidden;">
  <div style="position:absolute; top:0; left:0; width:100%; height:100%; pointer-events:none; opacity:0.15;">
    <div style="position:absolute; top:4rem; left:4rem; width:24rem; height:24rem; background:var(--color-gold); border-radius:50%; filter:blur(70px);"></div>
    <div style="position:absolute; bottom:4rem; right:4rem; width:24rem; height:24rem; background:var(--color-teal); border-radius:50%; filter:blur(70px);"></div>
  </div>

  <div class="container" style="position:relative; z-index:10;">
    <div class="section-heading center light" data-animate="fade-up">
      <h2>Enterprise Talent Solutions</h2>
      <p>Workforce strategies designed to scale with your growth, drive efficiency, and elevate performance.</p>
    </div>

    <div style="display:grid; grid-template-columns:1fr; gap:2rem; margin-top:4rem;" class="enterprise-grid">
      <?php
      $enterprise = [
        [
          "title"      => "Recruitment",
          "tagline"    => "Hire the best people",
          "desc"       => "Finding the right people shouldn't slow your growth plans. We help you secure permanent, contract, and executive talent who fit your business goals.",
          "icon"       => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>',
          "accent"     => "var(--color-gold)",
          "bg_accent"  => "rgba(201,164,75,0.1)",
          "bullets"    => [
            "Industry-specific consultants who understand your market",
            "Thorough search and assessment to deliver strong shortlists",
            "Employer branding support to attract the best candidates",
            "A transparent process that saves time and delivers results"
          ]
        ],
        [
          "title"      => "Outsourcing",
          "tagline"    => "Scale your talent operations",
          "desc"       => "When hiring demands spike, your team needs flexibility. Our outsourcing solutions take the pressure off and keep things moving.",
          "icon"       => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>',
          "accent"     => "var(--color-teal)",
          "bg_accent"  => "rgba(29,122,122,0.1)",
          "bullets"    => [
            "RPO models for full, project, or modular recruitment support",
            "MSP programs to manage vendors, compliance, and costs",
            "Direct sourcing combined with trusted supplier networks",
            "Real-time dashboards and analytics for smarter decisions"
          ]
        ],
        [
          "title"      => "Talent Advisory",
          "tagline"    => "Make smarter workforce decisions",
          "desc"       => "Talent strategies work best when they're backed by data. We turn insights into practical steps that improve attraction, retention, and performance.",
          "icon"       => '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>',
          "accent"     => "var(--color-red)",
          "bg_accent"  => "rgba(209,75,75,0.1)",
          "bullets"    => [
            "Market intelligence to benchmark compensation packages",
            "Diagnostics for diversity and candidate experience",
            "Leadership coaching and development programs",
            "Clear, actionable recommendations for long-term success"
          ]
        ]
      ];

      foreach ($enterprise as $idx => $ent): ?>
        <div class="enterprise-card" data-animate="fade-up" data-delay="<?= $idx * 150 ?>">
          <div>
            <!-- Header section -->
            <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem;">
              <div style="width:3.5rem; height:3.5rem; border-radius:1rem; background:<?= $ent['bg_accent'] ?>; color:<?= $ent['accent'] ?>; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <?= $ent['icon'] ?>
              </div>
              <div>
                <h3 style="font-family:var(--font-heading); font-size:1.35rem; font-weight:700; color:#fff; margin-bottom:0.15rem;">
                  <?= htmlspecialchars($ent['title']) ?>
                </h3>
                <p style="font-size:0.75rem; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; color:<?= $ent['accent'] ?>;">
                  <?= htmlspecialchars($ent['tagline']) ?>
                </p>
              </div>
            </div>

            <p style="color:rgba(255,255,255,0.7); font-size:0.9rem; line-height:1.6; margin-bottom:1.5rem;">
              <?= htmlspecialchars($ent['desc']) ?>
            </p>

            <div style="display:flex; flex-direction:column; gap:0.75rem; margin-bottom:2rem;">
              <?php foreach ($ent['bullets'] as $bullet): ?>
                <div style="display:flex; align-items:flex-start; gap:0.5rem;">
                  <span style="color:<?= $ent['accent'] ?>; margin-top:0.15rem; display:inline-flex; flex-shrink:0;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                  </span>
                  <span style="color:rgba(255,255,255,0.8); font-size:0.8rem; line-height:1.4;"><?= htmlspecialchars($bullet) ?></span>
                </div>
              <?php endforeach; ?>
            </div>
          </div>

          <div style="padding-top:1.5rem; border-top:1px solid rgba(255,255,255,0.06); margin-top:auto;">
            <a href="<?= BASE ?>/contact.php" class="btn btn-secondary btn-full btn-pill group-hover-white" style="border-color:rgba(255,255,255,0.15); color:#fff; font-size:0.875rem;">
              Partner With Us
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left:0.25rem;"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
            </a>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     STATS / NUMBERS SECTION
     ════════════════════════════════════════════════════════════ -->
<section style="padding-block:5rem; background:rgba(11,29,58,0.02); border-bottom:1px solid rgba(11,29,58,0.05);">
  <div class="container">
    <div style="display:grid; grid-template-columns:repeat(2, 1fr); gap:2rem;" class="stats-grid-services">
      <?php
      $stats = [
        ["count" => 50000, "suffix" => "+", "label" => "Applications Processed", "icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>'],
        ["count" => 98,    "suffix" => "%", "label" => "Success Rate", "icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>'],
        ["count" => 150,   "suffix" => "+", "label" => "Partner Countries", "icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>'],
        ["count" => 24,    "suffix" => "h", "label" => "Average Response", "icon" => '<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>']
      ];
      foreach ($stats as $idx => $st): ?>
        <div class="stat-card" data-animate="scale-in" data-delay="<?= $idx * 100 ?>" style="background:#fff; border-radius:1rem; padding:2rem; box-shadow:var(--shadow-card); border:1px solid rgba(11,29,58,0.05); text-align:center;">
          <div style="color:var(--color-gold); margin-bottom:1rem; display:flex; justify-content:center;">
            <?= $st['icon'] ?>
          </div>
          <div class="stat-number" style="font-size:2.5rem; font-weight:700; color:var(--color-navy); display:flex; align-items:center; justify-content:center; margin-bottom:0.25rem;">
            <span
              data-count="<?= htmlspecialchars($st['count']) ?>"
              data-suffix="<?= htmlspecialchars($st['suffix']) ?>"
              data-duration="2000"
            >0<?= htmlspecialchars($st['suffix']) ?></span>
          </div>
          <div class="stat-label" style="color:rgba(11,29,58,0.55); font-weight:600; font-size:0.9rem;"><?= htmlspecialchars($st['label']) ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     FAQ SECTION
     ════════════════════════════════════════════════════════════ -->
<section class="section-wrapper bg-navy">
  <div class="container">
    <div class="section-heading center light" data-animate="fade-up">
      <h2>Frequently Asked Questions</h2>
      <p>Find answers to common questions about our services</p>
    </div>

    <div style="max-width:48rem; margin-inline:auto;" data-animate="fade-up">
      <?php
      $faqs = [
        [
          "q" => "How long does the visa sponsorship process take?",
          "a" => "The process typically takes 2-6 months depending on the visa type and destination country. Our team works to expedite this timeline wherever possible."
        ],
        [
          "q" => "What documents do I need for the application?",
          "a" => "Required documents vary by service but typically include passport, educational certificates, work experience letters, financial documents, and medical records. We'll guide you through the specific requirements."
        ],
        [
          "q" => "Are there any upfront costs?",
          "a" => "Our service fees are competitive and transparent. We offer flexible payment plans and only charge for services rendered. No hidden fees or surprise costs."
        ],
        [
          "q" => "What is your success rate?",
          "a" => "We maintain a 98% success rate across all our services, supported by our thorough screening process and dedicated support team."
        ],
        [
          "q" => "Can I apply for multiple services at once?",
          "a" => "Yes, you can apply for multiple services. Our team will help you prioritize and create a comprehensive strategy for your global mobility goals."
        ]
      ];
      foreach ($faqs as $faq): ?>
        <div class="faq-item" style="border-bottom:1px solid rgba(255,255,255,0.08); overflow:hidden;">
          <button class="faq-trigger" style="width:100%; padding:1.5rem 0; display:flex; align-items:center; justify-content:between; text-align:left; color:#fff; cursor:pointer;">
            <span style="font-size:1.1rem; font-weight:600; flex:1; padding-right:1rem;"><?= htmlspecialchars($faq['q']) ?></span>
            <span class="faq-chevron" style="width:2rem; height:2rem; border-radius:50%; background:rgba(201,164,75,0.1); color:var(--color-gold); display:flex; align-items:center; justify-content:center; flex-shrink:0; transition:transform 0.3s ease;">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </span>
          </button>
          <div class="faq-answer" style="max-height:0; overflow:hidden; transition:max-height 0.35s ease-in-out; color:rgba(255,255,255,0.7); font-size:0.95rem; line-height:1.6;">
            <div style="padding-bottom:1.5rem;">
              <?= htmlspecialchars($faq['a']) ?>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     FINAL CTA SECTION
     ════════════════════════════════════════════════════════════ -->
<section class="section-wrapper bg-navy" style="position:relative; overflow:hidden; border-top:1px solid rgba(255,255,255,0.05);">
  <div style="position:absolute; top:0; left:0; width:100%; height:100%; background:radial-gradient(circle at center, rgba(201,164,75,0.06) 0%, transparent 70%); pointer-events:none;"></div>
  
  <div class="container" style="position:relative; z-index:10; text-align:center; max-width:42rem;">
    <h2 style="font-family:var(--font-heading); font-size:clamp(2rem, 5vw, 3rem); font-weight:700; color:#fff; margin-bottom:1.5rem;" data-animate="fade-up">
      Ready to Get Started?
    </h2>
    <p style="font-size:1.15rem; color:rgba(255,255,255,0.7); margin-bottom:2.5rem;" data-animate="fade-up" data-delay="100">
      Check your eligibility or contact us to discuss your options.
    </p>
    <div style="display:flex; flex-direction:column; justify-content:center; gap:1rem; align-items:center;" class="cta-btns" data-animate="fade-up" data-delay="200">
      <a href="<?= BASE ?>/eligibility.php" class="btn btn-primary btn-lg btn-pill" style="min-width:14rem;">
        Check Eligibility
      </a>
      <a href="<?= BASE ?>/contact.php" class="btn btn-secondary btn-lg btn-pill" style="min-width:14rem; border-color:var(--color-gold); color:var(--color-gold);">
        Contact Us
      </a>
    </div>
  </div>
</section>

<!-- Styling for dynamic components -->
<style>
/* Services Tab buttons */
.services-tab-buttons {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
  justify-content: center;
}

.services-tab-buttons .tab-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.75rem 1.5rem;
  border-radius: 0.75rem;
  font-family: var(--font-heading);
  font-weight: 600;
  font-size: 0.95rem;
  cursor: pointer;
  background: #fff;
  color: rgba(11,29,58,0.7);
  border: 2px solid rgba(11,29,58,0.08);
  transition: all 0.25s ease;
}

.services-tab-buttons .tab-btn:hover {
  border-color: rgba(201,164,75,0.3);
  color: var(--color-navy);
  transform: translateY(-2px);
}

.services-tab-buttons .tab-btn.active {
  background: var(--color-gold);
  color: var(--color-navy);
  border-color: var(--color-gold);
  box-shadow: 0 8px 20px rgba(201,164,75,0.25);
  transform: translateY(-2px);
}

/* Service Tab Hero Card */
.tab-hero-card {
  position: relative;
  background-size: cover;
  background-position: center;
  border-radius: 1.5rem;
  overflow: hidden;
  min-height: 20rem;
  display: flex;
  align-items: center;
  padding: 2.5rem;
  box-shadow: var(--shadow-navy);
}

.tab-hero-text h2 {
  font-family: var(--font-heading);
  font-size: clamp(1.8rem, 4vw, 2.5rem);
  font-weight: 700;
  color: #fff;
  margin-bottom: 0.5rem;
}

.tab-hero-text p {
  font-size: clamp(1rem, 2vw, 1.25rem);
  color: rgba(255,255,255,0.9);
  max-width: 32rem;
}

/* Split layout responsive */
.service-details-grid { grid-template-columns: 1fr; }
@media (min-width: 992px) {
  .service-details-grid { grid-template-columns: 1fr 1fr !important; }
}

/* Available Jobs Listings Grid */
.jobs-categories-grid { grid-template-columns: 1fr; }
@media (min-width: 576px)  { .jobs-categories-grid { grid-template-columns: repeat(2, 1fr) !important; } }
@media (min-width: 992px)  { .jobs-categories-grid { grid-template-columns: repeat(3, 1fr) !important; } }
@media (min-width: 1200px) { .jobs-categories-grid { grid-template-columns: repeat(4, 1fr) !important; } }

/* Job Listing Card */
.job-category-card {
  position: relative;
  border-radius: 1rem;
  overflow: hidden;
  height: 18rem;
  box-shadow: var(--shadow-card);
  cursor: pointer;
  transition: all 0.4s ease;
}

.job-category-card .job-card-image {
  position: absolute;
  inset: 0;
  background-size: cover;
  background-position: center;
  transition: transform 0.6s ease;
}

.job-category-card:hover .job-card-image {
  transform: scale(1.08);
}

.job-card-gradient {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, var(--color-navy) 0%, rgba(11,29,58,0.5) 60%, transparent 100%);
}

.job-card-top {
  position: absolute;
  top: 0.75rem;
  left: 0.75rem;
  right: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: space-between;
  z-index: 10;
}

.job-tag-badge {
  font-size: 0.7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  background: var(--color-gold);
  color: var(--color-navy);
  padding: 0.25rem 0.6rem;
  border-radius: 9999px;
  box-shadow: 0 4px 10px rgba(201,164,75,0.2);
}

.job-icon-box {
  width: 2.25rem;
  height: 2.25rem;
  border-radius: 0.5rem;
  background: rgba(255,255,255,0.15);
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255,255,255,0.25);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
}

.job-card-details {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 1.25rem;
  z-index: 10;
  text-align: left;
}

.job-card-details h4 {
  font-family: var(--font-heading);
  font-size: 1.1rem;
  font-weight: 700;
  color: #fff;
  margin-bottom: 0.35rem;
  line-height: 1.2;
}

.job-card-details p {
  font-size: 0.8rem;
  color: rgba(255,255,255,0.7);
  line-height: 1.4;
  margin-bottom: 0.75rem;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.job-apply-cta {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  font-size: 0.75rem;
  font-weight: 600;
  color: var(--color-gold);
  transition: color 0.3s;
}

.job-category-card:hover .job-apply-cta {
  color: #fff;
}

.job-category-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 30px rgba(11,29,58,0.25);
}

/* Enterprise talent solutions grid */
.enterprise-grid { grid-template-columns: 1fr; }
@media (min-width: 992px) {
  .enterprise-grid { grid-template-columns: repeat(3, 1fr) !important; }
}

/* Enterprise card component */
.enterprise-card {
  background: rgba(255,255,255,0.03);
  backdrop-filter: blur(10px);
  -webkit-backdrop-filter: blur(10px);
  border: 1px solid rgba(255,255,255,0.08);
  padding: 2.25rem;
  border-radius: 1.5rem;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  transition: all 0.3s ease;
  box-shadow: 0 15px 35px rgba(0,0,0,0.15);
}

.enterprise-card:hover {
  transform: translateY(-8px);
}

/* Custom shadow / border for hovers on specific services */
.enterprise-card:hover:nth-child(1) { border-color: rgba(201,164,75,0.4); box-shadow: 0 20px 40px rgba(201,164,75,0.12); }
.enterprise-card:hover:nth-child(2) { border-color: rgba(29,122,122,0.4);  box-shadow: 0 20px 40px rgba(29,122,122,0.12);  }
.enterprise-card:hover:nth-child(3) { border-color: rgba(209,75,75,0.4);   box-shadow: 0 20px 40px rgba(209,75,75,0.12);   }

/* Partner with us hover */
.btn.group-hover-white:hover {
  background: #fff !important;
  color: var(--color-navy) !important;
  border-color: #fff !important;
}

/* Stats responsive grid */
.stats-grid-services { grid-template-columns: repeat(2, 1fr); }
@media (min-width: 768px) {
  .stats-grid-services { grid-template-columns: repeat(4, 1fr) !important; }
}

/* FAQ item states */
.faq-item.open .faq-chevron {
  transform: rotate(180deg);
  background: var(--color-gold) !important;
  color: var(--color-navy) !important;
}

/* CTA buttons layout */
.cta-btns { flex-direction: column; }
@media (min-width: 576px) {
  .cta-btns { flex-direction: row !important; }
}
</style>

<!-- Tab switcher script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  const tabBtns = document.querySelectorAll('.services-tab-buttons .tab-btn');
  const tabContents = document.querySelectorAll('.tab-content');

  function switchTab(tabId) {
    tabBtns.forEach(btn => {
      if (btn.dataset.tab === tabId) {
        btn.classList.add('active');
      } else {
        btn.classList.remove('active');
      }
    });
    tabContents.forEach(content => {
      if (content.id === `tab-content-${tabId}`) {
        content.style.display = 'block';
      } else {
        content.style.display = 'none';
      }
    });
  }

  tabBtns.forEach(btn => {
    btn.addEventListener('click', () => {
      const tabId = btn.dataset.tab;
      switchTab(tabId);
      window.history.pushState(null, null, '#' + tabId);
    });
  });

  // Handle load hash
  const hash = window.location.hash.substring(1);
  if (hash && ['visa', 'education', 'jobs', 'residence'].includes(hash)) {
    switchTab(hash);
  }

  // FAQ Accordion Interactivity
  const faqItems = document.querySelectorAll('.faq-item');
  faqItems.forEach(item => {
    const trigger = item.querySelector('.faq-trigger');
    const answer  = item.querySelector('.faq-answer');
    
    if (trigger && answer) {
      trigger.addEventListener('click', () => {
        const isOpen = item.classList.contains('open');
        
        // Close others
        faqItems.forEach(other => {
          other.classList.remove('open');
          other.querySelector('.faq-answer').style.maxHeight = '0px';
        });

        if (!isOpen) {
          item.classList.add('open');
          answer.style.maxHeight = answer.scrollHeight + 'px';
        }
      });
    }
  });
});
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
require_once __DIR__ . '/includes/scripts.php';
?>
</body>
</html>
