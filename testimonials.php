<?php
/**
 * Lotoks — Candidate Success Stories (testimonials.php)
 * Converted from pages/Testimonials.tsx
 */
require_once __DIR__ . '/includes/auth.php';
redirect_if_logged_in();

$page_title       = 'Success Stories | Lotoks';
$page_description = 'Real success stories from candidates who have relocated to Europe, the UK, Poland, and Lithuania with Lotoks sponsorship.';
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/navbar.php';

// Testimonials data
$testimonials = [
  [
    "id" => 0,
    "name" => "Clyde",
    "country" => "Poland (via South Africa)",
    "flag" => "🇿🇦",
    "type" => "education",
    "typeLabel" => "Education & Visa",
    "quote" => "My name is Clyde, and this photo was taken at Basel International Airport, Switzerland/France, on my way to Poland to begin my studies. The journey to this point was not an easy one. After completing my A-Level studies in 2023, I applied to study in Poland and initially thought the process would be straightforward. However, I soon encountered a major challenge: securing a visa appointment at the Polish Embassy in Pretoria. Despite my efforts, I struggled for a long time without success. Everything changed in late July 2025 when a close friend referred me to Lotoks Consulting Agency. From that moment, the process became much smoother. Within just two weeks, the Lotoks team helped me secure a visa appointment and assisted me with all the necessary supporting documents. Their professionalism, efficiency, and guidance made what seemed impossible become a reality. If you are struggling with your study abroad process and feel like you've run out of options, don't give up. There is a way forward, and I highly recommend Lotoks Consulting Agency for their outstanding support and reliable services.",
    "rating" => 5,
    "hasVideo" => false,
    "image" => "./public/Clyde-Testimonials/Clyde.png",
  ],
  [
    "id" => 1,
    "name" => "Brian",
    "country" => "Poland (via Zimbabwe)",
    "flag" => "🇿🇼",
    "type" => "education",
    "typeLabel" => "Computer Science Major (Mobile Software Engineering)",
    "quote" => "My name is Brian, and I have been in Poland studying computer science with a major in mobile software engineering. I had a great experience working with Lotoks Consulting. From the beginning, they guided me through the entire study abroad process in a professional and supportive way. They helped me with the application for the course at Uniwersytet WSB Merito in Poznań, assisted me with visa applications, and also helped with travelling arrangements and itinerary planning. Their communication was clear, and they were always available whenever I had questions or needed assistance. Thanks to their guidance, the process became much easier and less stressful. I highly recommend Lotoks Consulting to anyone looking for assistance with studying abroad, as it went very well for me.",
    "rating" => 5,
    "hasVideo" => false,
    "image" => "./public/ugc-testimonials/Brian-Testimonials/Brian.jpeg",
  ],
  [
    "id" => 2,
    "name" => "Rethabile Nyathi",
    "country" => "Poland",
    "flag" => "🇿🇦",
    "type" => "education",
    "typeLabel" => "Wsei University (Poland)",
    "quote" => "Thanks to the dedicated support of Lotoks Consulting, I am now pursuing my education in Poland—a dream I once thought was out of reach. Their team guided me through each stage with professionalism and genuine care. Their partnership with trusted institutions like UITM – University of Information Technology and Management in Rzeszów, Poland, gave me confidence that I was applying to a legitimate and high-quality program. I am deeply grateful to the Lotoks team for making my study abroad journey possible.",
    "rating" => 5,
    "hasVideo" => true,
    "videoUrl" => "./public/ugc-testimonials/Graduating-videos/Rethbile-Nythi.mp4",
    "poster" => "./public/ugc-testimonials/Graduation-photos/WhatsApp Image 2026-05-29 at 00.16.02.jpeg",
  ],
  [
    "id" => 3,
    "name" => "Tanaka",
    "country" => "Wsei University, Poland",
    "flag" => "🇵🇱",
    "type" => "education",
    "typeLabel" => "Education",
    "quote" => "I am officially a graduate! Lotoks helped me secure my university admission and guided me through a flawless visa application. Highly recommend their services.",
    "rating" => 5,
    "hasVideo" => true,
    // (Wait, no videoUrl specified in Tanaka in source - let's default to a general placeholder or blank)
    "videoUrl" => "",
  ],
  [
    "id" => 4,
    "name" => "Clyde",
    "country" => "Poland",
    "flag" => "🇿🇼",
    "type" => "education",
    "typeLabel" => "Student Visa Success",
    "quote" => "I'm Clyde from Bulawayo, Zimbabwe. I want to thank Lotoks Consulting for the incredible support they gave me throughout my study abroad journey. From helping with my application to securing my visa and even arranging my travel, every step was handled with care and professionalism. They made my dream of studying in Poland a reality. If you're looking for a trustworthy partner to guide you abroad, Lotoks is the real deal. I highly recommend them!",
    "rating" => 5,
    "hasVideo" => true,
    "videoUrl" => "./public/ugc-testimonials/ugc-testimonias-video/clyde.mp4",
  ],
  [
    "id" => 5,
    "name" => "Adolf Hlungwani",
    "country" => "Lithuania",
    "flag" => "🇿🇦",
    "type" => "visa",
    "typeLabel" => "Truck Driver (Lithuania)",
    "quote" => "I'm Adolf Hlungwani, a truck driver based in Lithuania originally from South Africa. I want to thank Lotoks Consulting for helping me secure this incredible opportunity abroad. They connected me with a reputable transport company in Lithuania and handled all the paperwork, visa processing, and logistical arrangements professionally. Their team made the entire relocation process smooth and stress-free. Thanks to their support, I'm now driving international routes across Europe and building a better future for myself and my family. If you're a skilled driver looking for overseas opportunities, I highly recommend reaching out to Lotoks — they deliver results!",
    "rating" => 5,
    "hasVideo" => true,
    "videoUrl" => "",
  ],
  [
    "id" => 6,
    "name" => "Confirm Mpofu",
    "country" => "Lithuania",
    "flag" => "🇱🇹",
    "type" => "residence",
    "typeLabel" => "Lithuania TRP",
    "quote" => "SUCCESS ALERT! I am thrilled to announce that I have obtained my 2-Year Lithuania Temporary Residence Permit (TRP). Another dream achieved with the support of LOTOKS Consulting Agency. I am honored to have been guided through this journey and I wish myself all the best as I begin this exciting new chapter in Lithuania. Your journey starts with a single step. Let LOTOKS Consulting Agency guide the way.",
    "rating" => 5,
    "hasVideo" => true,
    "poster" => "./public/ugc-testimonials/Truck-drivers/2-Year Lithuania Temporary Residence Permit (TRP).png",
    "videoUrl" => "",
  ],
  [
    "id" => 7,
    "name" => "Khawuleza Ngwenya",
    "country" => "Lithuania",
    "flag" => "🇿🇼",
    "type" => "visa",
    "typeLabel" => "Truck Driver (Lithuania)",
    "quote" => "I'm Khawuleza Ngwenya from Zimbabwe, currently working as a truck driver in Lithuania. Thanks to LOTOKS Consulting Agency, I was able to secure this life-changing opportunity abroad. They handled everything from the paperwork to the placement, making the entire process smooth and stress-free. I'm now driving across Europe and building a better future. If you're looking for genuine assistance to work overseas, I highly recommend LOTOKS!",
    "rating" => 5,
    "hasVideo" => true,
    "videoUrl" => "./public/Khawuleza Ngwenya-video/Khawuleza Ngwenya video.mp4",
    "poster" => "./public/ugc-testimonials/Truck-drivers/litua.png",
  ],
  [
    "id" => 8,
    "name" => "Ruvarashe Vanessa Mashange",
    "country" => "Poland",
    "flag" => "🇵🇱",
    "type" => "success",
    "typeLabel" => "Success Story",
    "quote" => "My journey with Lotoks Consulting has been nothing short of amazing. Their team provided exceptional support and guidance throughout the entire process. I am truly grateful for their professionalism and dedication.",
    "rating" => 5,
    "hasVideo" => true,
    "videoUrl" => "./public/Ruvarashe-Vanessa-Mashange-video/ruvarashe-vanessa-mashange.mp4",
  ],
  [
    "id" => 9,
    "name" => "Qhubekani Ngwenya",
    "country" => "United Kingdom",
    "flag" => "🇬🇧",
    "type" => "jobs",
    "typeLabel" => "Health-Care Worker (UK)",
    "quote" => "I want to thank Lotoks Consulting Agency through their help I am able to work as a health-care worker in the UK.",
    "rating" => 5,
    "hasVideo" => true,
    "poster" => "./public/Care-worker/Qhubekani Ngwenya.png",
    "videoUrl" => "",
  ]
];

$featuredTestimonials = array_slice($testimonials, 0, 3);
$videoTestimonials = array_filter($testimonials, function($t) { return $t['hasVideo']; });

$truckDriverProofs = [
  [
    "id" => 1,
    "title" => "Skilled Driver Visa Confirmation",
    "image" => "./public/ugc-testimonials/Truck-drivers/WhatsApp Image 2026-05-29 at 00.05.50.jpeg",
    "description" => "Successful Work Visa Grant and Passport Stamp package verifying European relocation path.",
  ],
  [
    "id" => 2,
    "title" => "Federal Skilled Entry Approval",
    "image" => "./public/ugc-testimonials/Truck-drivers/WhatsApp Image 2026-05-29 at 00.07.53.jpeg",
    "description" => "Federal Skilled Worker Invitation to Apply (ITA) letter with official consular registration seals.",
  ],
  [
    "id" => 3,
    "title" => "LMIA positive Assessment Letter",
    "image" => "./public/ugc-testimonials/Truck-drivers/WhatsApp Image 2026-05-29 at 00.07.54.jpeg",
    "description" => "Labour Market Impact Assessment (LMIA) positive decision confirming the positive job recruitment clearance.",
  ],
  [
    "id" => 4,
    "title" => "Employment Visa Grant Stamp",
    "image" => "./public/ugc-testimonials/Truck-drivers/WhatsApp Image 2026-05-29 at 00.07.54 (1).jpeg",
    "description" => "Visa grant document confirming high-skilled commercial driver employment authorization.",
  ],
  [
    "id" => 5,
    "title" => "Schengen Visa Residence Seal",
    "image" => "./public/ugc-testimonials/Truck-drivers/WhatsApp Image 2026-05-29 at 00.07.55.jpeg",
    "description" => "Passport copy displaying the official border agency temporary resident clearance permit.",
  ],
  [
    "id" => 6,
    "title" => "Sponsorship Allocation Confirmation",
    "image" => "./public/ugc-testimonials/Truck-drivers/WhatsApp Image 2026-05-29 at 00.07.55 (2).jpeg",
    "description" => "Direct logistics corporation employer allocation clearance confirming sponsorship certificate.",
  ],
  [
    "id" => 7,
    "title" => "Logistics Skilled Class Visa",
    "image" => "./public/ugc-testimonials/Truck-drivers/WhatsApp Image 2026-05-29 at 00.07.56.jpeg",
    "description" => "Official consulate entry visa foil stamped in the applicant passport enabling skilled work.",
  ],
  [
    "id" => 8,
    "title" => "Consular Application Receipt",
    "image" => "./public/ugc-testimonials/Truck-drivers/WhatsApp Image 2026-05-29 at 00.07.56 (1).jpeg",
    "description" => "Verified biometric file confirmation and official case registration receipt from immigration services.",
  ],
  [
    "id" => 9,
    "title" => "Corporate Work Placement Contract",
    "image" => "./public/ugc-testimonials/Truck-drivers/WhatsApp Image 2026-05-29 at 00.50.24.jpeg",
    "description" => "Verified employment contract signed by European logistics enterprise sponsoring class 1 drivers.",
  ],
  [
    "id" => 10,
    "title" => "Professional Driver Work Stamp",
    "image" => "./public/ugc-testimonials/Truck-drivers/WhatsApp Image 2026-05-29 at 00.50.24 (1).jpeg",
    "description" => "Verified transport authority work authorization certificate enabling heavy machinery logistics operations.",
  ],
  [
    "id" => 11,
    "title" => "EU Biometric Residence Permit",
    "image" => "./public/ugc-testimonials/Truck-drivers/WhatsApp Image 2026-05-29 at 00.50.24 (2).jpeg",
    "description" => "Official biometric temporary residence permit card confirming local residency status.",
  ],
  [
    "id" => 12,
    "title" => "Travel Visa stamp & Border Clearance",
    "image" => "./public/ugc-testimonials/Truck-drivers/WhatsApp Image 2026-05-29 at 00.50.25 (1).jpeg",
    "description" => "Border control confirmation stamp on official visa sheet validating legal employment arrival.",
  ]
];

$galleryImages = [
  [
    "src" => "./public/Gallery/After a long search of jobs in luxembourg.jpeg",
    "title" => "After a long search of jobs in Luxembourg",
  ],
  [
    "src" => "./public/Gallery/after a successful collaboration in warsaw poland.jpeg",
    "title" => "Successful Collaboration in Warsaw, Poland",
  ],
  [
    "src" => "./public/Gallery/Another collaboration.jpeg",
    "title" => "Another Successful Collaboration",
  ],
  [
    "src" => "./public/Gallery/OLD TOWN in warsaw.jpeg",
    "title" => "Old Town, Warsaw",
  ],
  [
    "src" => "./public/Gallery/Poland.jpeg",
    "title" => "Poland",
  ],
  [
    "src" => "./public/Gallery/visiting a company that I had a contract with poland.jpeg",
    "title" => "Partner in Poland for trucking company",
  ]
];
?>

<!-- ════════════════════════════════════════════════════════════
     HERO SECTION
     ════════════════════════════════════════════════════════════ -->
<section class="page-hero">
  <div class="hero-overlay-img">
    <img src="<?= BASE ?>/public/images/Testimonials.png" alt="Testimonials Background" loading="eager" />
  </div>
  <div class="hero-overlay-dark"></div>
  
  <!-- Decorative spinning globe pattern mimicking React animations -->
  <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); width:600px; height:600px; border:1px solid rgba(255,255,255,0.03); border-radius:50%; animation: spin 120s linear infinite; pointer-events:none;"></div>
  <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); width:400px; height:400px; border:1px solid rgba(255,255,255,0.03); border-radius:50%; animation: spin 80s linear infinite reverse; pointer-events:none;"></div>

  <div class="container" style="position:relative; z-index:10; text-align:center;">
    <h1 style="font-size:clamp(2.3rem, 6vw, 3.8rem); color:#fff; margin-bottom:1rem; font-family:var(--font-heading); font-weight:700;" data-animate="fade-up">
      Candidate Success Stories
    </h1>
    <p style="font-size:clamp(1.1rem, 2vw, 1.35rem); color:rgba(255,255,255,0.8); max-width:44rem; margin-inline:auto;" data-animate="fade-up" data-delay="100">
      Real user-generated videos, verified legal approvals, and genuine global admissions journeys.
    </p>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     VIDEO TESTIMONIALS SECTION
     ════════════════════════════════════════════════════════════ -->
<section class="section-wrapper" style="background:linear-gradient(to bottom, var(--color-surface), #fff); position:relative; overflow:hidden;">
  <div style="position:absolute; top:10rem; right:0; width:min(500px, 75vw); height:min(500px, 75vw); background:rgba(201,164,75,0.03); border-radius:50%; filter:blur(100px); pointer-events:none;"></div>
  <div style="position:absolute; bottom:5rem; left:0; width:min(400px, 60vw); height:min(400px, 60vw); background:rgba(29,122,122,0.03); border-radius:50%; filter:blur(100px); pointer-events:none;"></div>

  <div class="container" style="position:relative; z-index:10;">
    <div class="section-heading center dark" data-animate="fade-up">
      <h2>Video Stories</h2>
      <p>Hear and see directly from our successful graduates and sponsored candidates</p>
    </div>

    <div style="display:grid; gap:2rem;" class="videos-grid-layout">
      <?php 
      $vIdx = 0;
      foreach ($videoTestimonials as $t): 
        $hasActualVideo = !empty($t['videoUrl']);
      ?>
        <div class="video-testimonial-card <?= $hasActualVideo ? 'playable' : '' ?>" 
             data-video-url="<?= htmlspecialchars($t['videoUrl']) ?>" 
             data-animate="fade-up" 
             data-delay="<?= $vIdx * 120 ?>">
          
          <div class="video-thumbnail-box">
            <?php if (!empty($t['poster'])): ?>
              <img src="<?= htmlspecialchars($t['poster']) ?>" alt="<?= htmlspecialchars($t['name']) ?>" loading="lazy" />
              <div class="video-thumbnail-overlay"></div>
            <?php else: ?>
              <div style="position:absolute; inset:0; background:linear-gradient(to bottom right, var(--color-navy), #08152b);"></div>
            <?php endif; ?>

            <!-- Premium play button decoration -->
            <div class="play-button-outer">
              <div class="play-ring-glow"></div>
              <div class="play-button-inner">
                <?php if ($hasActualVideo): ?>
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                <?php else: ?>
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                <?php endif; ?>
              </div>
            </div>

            <!-- Card top line glow -->
            <div class="card-gold-line"></div>

            <!-- Bottom info strip overlay -->
            <div class="card-bottom-strip">
              <div style="display:flex; align-items:center; gap:0.5rem; width:100%;">
                <div style="display:flex; gap:2px;">
                  <?php for($i=0; $i<5; $i++): ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="var(--color-gold)" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                  <?php endfor; ?>
                </div>
                <span class="video-story-badge"><?= $hasActualVideo ? 'Video Story' : 'Success Story' ?></span>
              </div>
            </div>
          </div>

          <!-- Bottom detailed card info -->
          <div class="video-card-body">
            <!-- Top line accent -->
            <div class="card-body-accent"></div>

            <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
              <div class="avatar-flag-box">
                <?= $t['flag'] ?>
              </div>
              <div style="text-align:left;">
                <h4 style="font-family:var(--font-heading); font-size:1.05rem; font-weight:700; color:var(--color-navy); margin-bottom:0.15rem;"><?= htmlspecialchars($t['name']) ?></h4>
                <p style="font-size:0.75rem; color:rgba(11,29,58,0.5); font-weight:500; display:flex; align-items:center; gap:0.25rem;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                  <?= htmlspecialchars($t['country']) ?>
                </p>
              </div>
            </div>

            <p style="color:var(--color-on-surface-variant); font-size:0.875rem; font-style:italic; line-height:1.5; flex:1; text-align:left; margin-bottom:1.5rem;">
              "<?= htmlspecialchars($t['quote']) ?>"
            </p>

            <div style="display:flex; align-items:center; justify-content:space-between; margin-top:auto; padding-top:1rem; border-top:1px solid rgba(11,29,58,0.05);">
              <span class="video-type-label"><?= htmlspecialchars($t['typeLabel']) ?></span>
              <span style="font-size:0.75rem; font-weight:700; color:var(--color-navy); display:inline-flex; align-items:center; gap:0.25rem;" class="cta-label">
                <?= $hasActualVideo ? 'Watch Video' : 'View Story' ?>
                <?php if ($hasActualVideo): ?>
                  <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                <?php else: ?>
                  <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                <?php endif; ?>
              </span>
            </div>
          </div>

        </div>
      <?php 
        $vIdx++;
      endforeach; 
      ?>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     FEATURED STORIES (CARDS)
     ════════════════════════════════════════════════════════════ -->
<section class="section-wrapper bg-navy">
  <div class="container">
    <div class="section-heading center light" data-animate="fade-up">
      <h2>Success Stories</h2>
      <p>Real experiences from real people who transformed their lives</p>
    </div>

    <div style="display:grid; gap:2rem;" class="featured-grid-layout">
      <?php 
      foreach ($featuredTestimonials as $idx => $t): 
        $isLong = strlen($t['quote']) > 240;
      ?>
        <div class="glass-card success-story-card" data-animate="fade-up" data-delay="<?= $idx * 150 ?>">
          <div style="display:flex; flex-direction:column; height:100%;">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="rgba(201,164,75,0.15)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position:absolute; top:1.5rem; right:1.5rem; pointer-events:none;"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 2v4c0 1.25.757 2 2 2h2c0 0 0 3-3 4m14-1c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 2v4c0 1.25.757 2 2 2h2c0 0 0 3-3 4"/></svg>

            <?php if (!empty($t['image'])): ?>
              <div style="width:100%; aspect-ratio:16/10; border-radius:0.75rem; overflow:hidden; margin-bottom:1.5rem; position:relative; flex-shrink:0;">
                <img src="<?= htmlspecialchars($t['image']) ?>" alt="<?= htmlspecialchars($t['name']) ?>" style="width:100%; height:100%; object-fit:cover; object-position:top;" />
                <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(11,29,58,0.6) 0%, transparent 80%);"></div>
              </div>
            <?php endif; ?>

            <span class="story-category-tag"><?= htmlspecialchars($t['typeLabel']) ?></span>

            <div style="display:flex; gap:3px; margin-bottom:1rem;">
              <?php for($i=0; $i<5; $i++): ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="var(--color-gold)" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              <?php endfor; ?>
            </div>

            <!-- Quote content -->
            <div style="flex:1; margin-bottom:1.5rem;">
              <p class="quote-text <?= $isLong ? 'clamped' : '' ?>" style="color:rgba(255,255,255,0.85); font-style:italic; font-size:1.05rem; line-height:1.65; transition:all 0.3s ease;">
                "<?= htmlspecialchars($t['quote']) ?>"
              </p>
              <?php if ($isLong): ?>
                <button class="expand-quote-btn" style="color:var(--color-gold); font-size:0.85rem; font-weight:700; margin-top:0.5rem; text-decoration:none; cursor:pointer;">Read more</button>
              <?php endif; ?>
            </div>

            <!-- Author signature -->
            <div style="display:flex; align-items:center; gap:0.75rem; margin-top:auto; padding-top:1.25rem; border-top:1px solid rgba(255,255,255,0.08);">
              <div class="author-avatar-flag">
                <?= $t['flag'] ?>
              </div>
              <div style="text-align:left;">
                <div style="font-weight:700; color:#fff; font-size:1.05rem;"><?= htmlspecialchars($t['name']) ?></div>
                <div style="color:rgba(255,255,255,0.45); font-size:0.85rem; font-weight:500;"><?= htmlspecialchars($t['country']) ?></div>
              </div>
            </div>

          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     TRUCK DRIVERS TESTING GALLERY (SUCCESS PROOFS)
     ════════════════════════════════════════════════════════════ -->
<section class="section-wrapper" style="background:#fff; position:relative; overflow:hidden;">
  <!-- Ambient background accents -->
  <div style="position:absolute; top:0; right:25%; width:min(24rem,60vw); height:min(24rem,60vw); background:rgba(201,164,75,0.03); border-radius:50%; filter:blur(80px); pointer-events:none;"></div>
  <div style="position:absolute; bottom:0; left:25%; width:min(24rem,60vw); height:min(24rem,60vw); background:rgba(29,122,122,0.03); border-radius:50%; filter:blur(80px); pointer-events:none;"></div>

  <div class="container" style="position:relative; z-index:10;">
    <div style="margin-bottom:3.5rem;" data-animate="fade-up">
      <h2 style="font-family:var(--font-heading); font-size:clamp(1.8rem, 4vw, 2.5rem); font-weight:700; color:var(--color-navy); margin-bottom:1rem;">
        Testing Of Truck Drivers In <span style="color:var(--color-gold);">Zimbabwe</span>
      </h2>
      <p style="color:var(--color-on-surface-variant); font-size:1.05rem; max-width:44rem; line-height:1.6;">
        Professional truck driver assessment and certification processes for skilled transportation roles across African routes. Review verified documents from our candidates.
      </p>
    </div>

    <!-- Proof Gallery Grid -->
    <div style="display:grid; gap:1.25rem;" class="proofs-grid-layout" data-animate="fade-up">
      <?php foreach ($truckDriverProofs as $proof): ?>
        <div class="proof-card" 
             data-image="<?= htmlspecialchars($proof['image']) ?>" 
             data-title="<?= htmlspecialchars($proof['title']) ?>" 
             data-description="<?= htmlspecialchars($proof['description']) ?>"
             style="background:rgba(11,29,58,0.03); border:1px solid rgba(11,29,58,0.06); padding:0.5rem; border-radius:1rem; cursor:pointer; position:relative; overflow:hidden; transition:all 0.3s ease;">
          
          <div style="aspect-ratio:3/4; border-radius:0.75rem; overflow:hidden; background:var(--color-navy); position:relative;" class="image-box">
            <img src="<?= htmlspecialchars($proof['image']) ?>" alt="<?= htmlspecialchars($proof['title']) ?>" style="width:100%; height:100%; object-fit:cover; transition:transform 0.5s;" />
            
            <!-- Hover sweep -->
            <div class="proof-hover-overlay" style="position:absolute; inset:0; background:rgba(11,29,58,0.7); display:flex; align-items:center; justify-content:center; opacity:0; transition:opacity 0.3s ease;">
              <div style="width:3rem; height:3rem; border-radius:50%; background:var(--color-gold); color:var(--color-navy); display:flex; align-items:center; justify-content:center; box-shadow:var(--shadow-gold);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     OUR GALLERY SECTION
     ════════════════════════════════════════════════════════════ -->
<section class="section-wrapper" style="background:linear-gradient(to bottom, #fff, var(--color-surface)); position:relative; overflow:hidden;">
  <div style="position:absolute; top:5rem; left:0; width:min(400px, 60vw); height:min(400px, 60vw); background:rgba(201,164,75,0.03); border-radius:50%; filter:blur(80px); pointer-events:none;"></div>
  <div style="position:absolute; bottom:5rem; right:0; width:min(400px, 60vw); height:min(400px, 60vw); background:rgba(29,122,122,0.03); border-radius:50%; filter:blur(80px); pointer-events:none;"></div>

  <div class="container" style="position:relative; z-index:10;">
    <div style="text-align:center; margin-bottom:4rem;" data-animate="fade-up">
      <h2 style="font-family:var(--font-heading); font-size:clamp(1.8rem, 4vw, 2.5rem); font-weight:700; color:var(--color-navy); margin-bottom:1rem;">
        Our <span style="color:var(--color-gold);">Gallery</span>
      </h2>
      <p style="color:var(--color-on-surface-variant); font-size:1.05rem; max-width:36rem; margin-inline:auto; line-height:1.6; font-weight:500;">
        Real moments captured across Europe — from successful collaborations to life-changing milestones
      </p>
    </div>

    <!-- Gallery Uniform Grid -->
    <div style="display:grid; gap:2rem;" class="gallery-grid-layout" data-animate="fade-up">
      <?php foreach ($galleryImages as $gidx => $img): ?>
        <div class="gallery-card" data-image="<?= htmlspecialchars($img['src']) ?>" style="background:#fff; border-radius:1rem; overflow:hidden; box-shadow:var(--shadow-card); border:1px solid rgba(11,29,58,0.04); cursor:pointer; transition:all 0.4s ease;">
          <div style="aspect-ratio:4/3; overflow:hidden; position:relative; background:var(--color-navy);" class="img-container">
            <img src="<?= htmlspecialchars($img['src']) ?>" alt="<?= htmlspecialchars($img['title']) ?>" style="width:100%; height:100%; object-fit:cover; transition:transform 0.6s ease;" />
            <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,0.5) 0%, transparent 60%); opacity:0; transition:opacity 0.4s ease;" class="overlay"></div>
            
            <div style="position:absolute; inset:0; display:flex; align-items:center; justify-content:center; opacity:0; transition:all 0.4s ease;" class="view-icon">
              <div style="width:3rem; height:3rem; border-radius:50%; background:rgba(255,255,255,0.9); backdrop-filter:blur(4px); color:var(--color-navy); display:flex; align-items:center; justify-content:center; box-shadow:0 8px 20px rgba(0,0,0,0.25);">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </div>
            </div>
          </div>

          <!-- Card label strip -->
          <div style="padding:1.25rem; display:flex; align-items:center; justify-content:space-between; gap:1rem;">
            <div style="min-width:0; flex:1;">
              <span style="display:block; font-size:0.65rem; font-weight:700; color:var(--color-gold); letter-spacing:0.1em; text-transform:uppercase; margin-bottom:0.25rem;">Gallery • <?= str_pad($gidx+1, 2, '0', STR_PAD_LEFT) ?></span>
              <h4 style="font-family:var(--font-heading); font-size:0.9rem; font-weight:700; color:var(--color-navy); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"><?= htmlspecialchars($img['title']) ?></h4>
            </div>
            <div class="gallery-arrow-box" style="width:2.25rem; height:2.25rem; border-radius:50%; background:rgba(201,164,75,0.1); color:var(--color-gold); display:flex; align-items:center; justify-content:center; transition:background 0.3s;">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"/></svg>
            </div>
          </div>

          <!-- Accent gold line -->
          <div class="gallery-card-accent-line"></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     STATISTICS / NUMBERS SECTION
     ════════════════════════════════════════════════════════════ -->
<section style="padding-block:5rem; background:rgba(11,29,58,0.03); border-block:1px solid rgba(11,29,58,0.05);">
  <div class="container">
    <div style="display:grid; gap:2rem;" class="stats-grid-testimonials">
      <?php
      $stats = [
        ["count" => 50000, "suffix" => "+", "label" => "Happy Applicants"],
        ["count" => 98,    "suffix" => "%", "label" => "Success Rate"],
        ["count" => 150,   "suffix" => "+", "label" => "Countries"],
        ["count" => 4.9,   "suffix" => "",  "label" => "Average Rating", "is_decimal" => true]
      ];
      foreach ($stats as $idx => $st): ?>
        <div class="stat-card" data-animate="scale-in" data-delay="<?= $idx * 100 ?>" style="background:#fff; border-radius:1rem; padding:2rem; box-shadow:var(--shadow-card); border:1px solid rgba(11,29,58,0.05); text-align:center;">
          <div class="stat-number" style="font-size:3rem; font-weight:700; color:var(--color-gold); display:flex; align-items:center; justify-content:center; margin-bottom:0.5rem;">
            <span
              data-count="<?= htmlspecialchars($st['count']) ?>"
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
     ALL STORIES GRID WITH CLIENT FILTERING
     ════════════════════════════════════════════════════════════ -->
<section class="section-wrapper" style="background:#fff;">
  <div class="container">
    <div class="section-heading center dark" data-animate="fade-up">
      <h2>All Success Stories</h2>
      <p>Browse through hundreds of journeys that started with Lotoks</p>
    </div>

    <!-- Filter Buttons -->
    <div class="stories-filter-buttons" data-animate="fade-up" style="display:flex; flex-wrap:wrap; gap:0.75rem; justify-content:center; margin-bottom:3.5rem;">
      <button class="filter-btn active" data-filter="all">All Stories</button>
      <button class="filter-btn" data-filter="visa">Visa Sponsorship</button>
      <button class="filter-btn" data-filter="education">Education</button>
      <button class="filter-btn" data-filter="jobs">Job Placement</button>
      <button class="filter-btn" data-filter="residence">Residence</button>
    </div>

    <!-- Grid -->
    <div style="display:grid; gap:2rem;" class="stories-main-grid" data-animate="fade-up">
      <?php 
      foreach ($testimonials as $tIdx => $t): 
        $isLong = strlen($t['quote']) > 240;
      ?>
        <div class="filterable-testimonial-item" data-type="<?= htmlspecialchars($t['type']) ?>">
          <div class="elevated-card filterable-card" style="display:flex; flex-direction:column; height:100%; position:relative; overflow:hidden;">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="rgba(11,29,58,0.05)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position:absolute; top:1.5rem; right:1.5rem; pointer-events:none;"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 2v4c0 1.25.757 2 2 2h2c0 0 0 3-3 4m14-1c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 2v4c0 1.25.757 2 2 2h2c0 0 0 3-3 4"/></svg>

            <?php if (!empty($t['image'])): ?>
              <div style="width:100%; aspect-ratio:16/10; border-radius:0.75rem; overflow:hidden; margin-bottom:1.5rem; position:relative; flex-shrink:0;">
                <img src="<?= htmlspecialchars($t['image']) ?>" alt="<?= htmlspecialchars($t['name']) ?>" style="width:100%; height:100%; object-fit:cover; object-position:top;" />
                <div style="position:absolute; inset:0; background:linear-gradient(to top, rgba(11,29,58,0.1) 0%, transparent 80%);"></div>
              </div>
            <?php endif; ?>

            <span class="story-category-tag-dark"><?= htmlspecialchars($t['typeLabel']) ?></span>

            <div style="display:flex; gap:3px; margin-bottom:1rem;">
              <?php for($i=0; $i<5; $i++): ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="var(--color-gold)" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              <?php endfor; ?>
            </div>

            <!-- Quote content -->
            <div style="flex:1; margin-bottom:1.5rem;">
              <p class="quote-text-dark <?= $isLong ? 'clamped' : '' ?>" style="color:var(--color-on-surface-variant); font-style:italic; font-size:0.95rem; line-height:1.65; transition:all 0.3s ease;">
                "<?= htmlspecialchars($t['quote']) ?>"
              </p>
              <?php if ($isLong): ?>
                <button class="expand-quote-btn-dark" style="color:var(--color-primary); font-size:0.8rem; font-weight:700; margin-top:0.5rem; text-decoration:none; cursor:pointer;">Read more</button>
              <?php endif; ?>
            </div>

            <!-- Author signature -->
            <div style="display:flex; align-items:center; gap:0.75rem; margin-top:auto; padding-top:1.25rem; border-top:1px solid rgba(11,29,58,0.06);">
              <div class="author-avatar-flag">
                <?= $t['flag'] ?>
              </div>
              <div style="text-align:left;">
                <div style="font-weight:700; color:var(--color-navy); font-size:0.95rem;"><?= htmlspecialchars($t['name']) ?></div>
                <div style="color:rgba(11,29,58,0.5); font-size:0.8rem; font-weight:500;"><?= htmlspecialchars($t['country']) ?></div>
              </div>
            </div>

          </div>
        </div>
      <?php endforeach; ?>
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
      Share Your Success Story
    </h2>
    <p style="font-size:1.15rem; color:rgba(255,255,255,0.7); margin-bottom:2.5rem;" data-animate="fade-up" data-delay="100">
      Join thousands of others who have achieved their dreams. Your journey could inspire others.
    </p>
    <div style="display:flex; flex-direction:column; justify-content:center; gap:1rem; align-items:center;" class="cta-btns" data-animate="fade-up" data-delay="200">
      <a href="<?= BASE ?>/eligibility.php" class="btn btn-primary btn-lg btn-pill" style="min-width:14rem;">
        Start Your Journey
      </a>
      <a href="<?= BASE ?>/contact.php" class="btn btn-secondary btn-lg btn-pill" style="min-width:14rem; border-color:var(--color-gold); color:var(--color-gold);">
        Get in Touch
      </a>
    </div>
  </div>
</section>

<!-- ════════════════════════════════════════════════════════════
     MODALS SECTION (CINEMATIC OVERLAY LIGHTBOXES)
     ════════════════════════════════════════════════════════════ -->

<!-- 1. HTML5 Video Player overlay Modal -->
<div id="video-modal" class="modal-overlay" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(11,29,58,0.95); backdrop-filter:blur(10px); justify-content:center; align-items:center; padding:1.5rem;">
  <div style="position:relative; width:100%; max-width:56rem; aspect-ratio:16/9; background:#000; border-radius:1rem; overflow:hidden; border:1px solid rgba(255,255,255,0.1);">
    <button class="modal-close" style="position:absolute; top:1rem; right:1rem; z-index:10000; width:2.5rem; height:2.5rem; border-radius:50%; background:rgba(11,29,58,0.7); border:1px solid rgba(255,255,255,0.15); color:#fff; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:1.5rem; line-height:1;">&times;</button>
    <video id="modal-video-player" controls style="width:100%; height:100%; object-fit:contain;" src=""></video>
  </div>
</div>

<!-- 2. Interactive Success Proof Lightbox Modal -->
<div id="proof-modal" class="modal-overlay" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(11,29,58,0.95); backdrop-filter:blur(10px); justify-content:center; align-items:center; flex-direction:column; padding:1.5rem;">
  <div style="position:relative; max-width:100%; max-height:80vh; display:flex; flex-direction:column; align-items:center;">
    <!-- download + close controls -->
    <div style="position:absolute; top:-3.5rem; right:0; display:flex; gap:0.75rem; z-index:10000;">
      <a id="proof-download-btn" href="" download target="_blank" title="Download verification document" class="btn btn-secondary btn-sm" style="border-radius:0.5rem; border-color:rgba(255,255,255,0.15); color:#fff; display:flex; align-items:center; justify-content:center; padding:0.5rem 0.75rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
      </a>
      <button id="proof-modal-close" style="width:2.25rem; height:2.25rem; border-radius:0.5rem; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15); color:#fff; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:1.25rem;">&times;</button>
    </div>
    
    <div style="background:var(--color-navy); border:1px solid rgba(255,255,255,0.1); border-radius:1rem; padding:0.5rem; display:flex; align-items:center; justify-content:center; box-shadow:var(--shadow-navy);">
      <img id="proof-modal-img" src="" alt="" style="max-width:90vw; max-height:60vh; object-fit:contain; border-radius:0.5rem;" />
    </div>
    
    <div style="margin-top:1.5rem; text-align:center; max-width:32rem;">
      <h3 id="proof-modal-title" style="color:#fff; font-family:var(--font-heading); font-size:1.2rem; font-weight:700;"></h3>
      <p id="proof-modal-desc" style="color:rgba(255,255,255,0.6); font-size:0.85rem; margin-top:0.5rem; line-height:1.4;"></p>
      <div style="display:inline-flex; align-items:center; gap:0.5rem; margin-top:1rem; font-size:0.7rem; font-weight:700; color:var(--color-gold); background:rgba(201,164,75,0.1); border:1px solid rgba(201,164,75,0.2); padding:0.25rem 0.75rem; border-radius:9999px; text-transform:uppercase; letter-spacing:0.05em;">
        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
        Verified Official Document
      </div>
    </div>
  </div>
</div>

<!-- 3. Gallery Lightbox Modal -->
<div id="gallery-modal" class="modal-overlay" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(11,29,58,0.95); backdrop-filter:blur(10px); justify-content:center; align-items:center; padding:1.5rem;">
  <div style="position:relative; max-width:56rem; max-height:80vh; width:100%; display:flex; align-items:center; justify-content:center;">
    <button class="modal-close" style="position:absolute; top:-3.5rem; right:0; z-index:10000; width:2.25rem; height:2.25rem; border-radius:0.5rem; background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15); color:#fff; display:flex; align-items:center; justify-content:center; cursor:pointer; font-size:1.25rem;">&times;</button>
    <img id="gallery-modal-img" src="" alt="" style="max-width:100%; max-height:75vh; object-fit:contain; border-radius:1rem; box-shadow:var(--shadow-navy); border:1px solid rgba(255,255,255,0.05);" />
  </div>
</div>

<!-- Styling for dynamic components -->
<style>
/* Video Grid layout */
.videos-grid-layout { grid-template-columns: 1fr; }
@media (min-width: 576px) { .videos-grid-layout { grid-template-columns: repeat(2, 1fr); } }
@media (min-width: 992px) { .videos-grid-layout { grid-template-columns: repeat(4, 1fr); } }

/* Video card component styles */
.video-testimonial-card {
  background: #fff;
  border-radius: 1.25rem;
  overflow: hidden;
  border: 1px solid rgba(11,29,58,0.05);
  box-shadow: var(--shadow-card);
  display: flex;
  flex-direction: column;
  height: 100%;
  transition: all 0.4s ease;
}

.video-testimonial-card.playable {
  cursor: pointer;
}

.video-thumbnail-box {
  position: relative;
  aspect-ratio: 4/3;
  overflow: hidden;
  background: var(--color-navy);
}

.video-thumbnail-box img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.6s ease;
}

.video-testimonial-card:hover .video-thumbnail-box img {
  transform: scale(1.08);
}

.video-thumbnail-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0.1) 60%, transparent 100%);
}

.play-button-outer {
  position: absolute;
  inset: 0;
  display: flex;
  align-items: center;
  justify-content: center;
}

.play-ring-glow {
  position: absolute;
  width: 3.5rem;
  height: 3.5rem;
  border-radius: 50%;
  background: rgba(201,164,75,0.3);
  filter: blur(4px);
  animation: pulse 2s infinite;
  opacity: 0;
  transition: opacity 0.3s;
}

.video-testimonial-card:hover .play-ring-glow {
  opacity: 1;
}

.play-button-inner {
  position: relative;
  width: 3.5rem;
  height: 3.5rem;
  border-radius: 50%;
  background: var(--color-gold);
  color: var(--color-navy);
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 8px 20px rgba(201,164,75,0.3);
  border: 2px solid rgba(255,255,255,0.25);
  transition: all 0.3s ease;
}

.play-button-inner svg {
  margin-left: 2px;
}

.video-testimonial-card:hover .play-button-inner {
  transform: scale(1.1);
  box-shadow: 0 10px 25px rgba(201,164,75,0.45);
}

.card-gold-line {
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 2px;
  background: linear-gradient(to right, transparent, var(--color-gold), transparent);
}

.card-bottom-strip {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  padding: 1rem;
  background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 70%, transparent 100%);
  display: flex;
  align-items: center;
}

.video-story-badge {
  margin-left: auto;
  font-size: 0.65rem;
  font-weight: 700;
  color: rgba(255,255,255,0.8);
  background: rgba(255,255,255,0.15);
  backdrop-filter: blur(4px);
  padding: 0.2rem 0.5rem;
  border-radius: 9999px;
  letter-spacing: 0.02em;
}

.video-card-body {
  padding: 1.25rem;
  position: relative;
  display: flex;
  flex-direction: column;
  flex: 1;
}

.card-body-accent {
  position: absolute;
  top: 0;
  left: 1rem;
  right: 1rem;
  height: 1px;
  background: linear-gradient(to right, transparent, rgba(201,164,75,0.3), transparent);
}

.avatar-flag-box {
  width: 2.5rem;
  height: 2.5rem;
  border-radius: 50%;
  background: rgba(201,164,75,0.15);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.25rem;
  box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
  flex-shrink: 0;
}

.video-type-label {
  font-size: 0.7rem;
  font-weight: 700;
  color: var(--color-gold);
  background: rgba(201,164,75,0.08);
  padding: 0.25rem 0.6rem;
  border-radius: 9999px;
  letter-spacing: 0.02em;
}

.video-testimonial-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 15px 35px rgba(11,29,58,0.15);
}

.video-testimonial-card:hover .cta-label {
  color: var(--color-gold) !important;
}

/* Featured Success Stories cards */
.featured-grid-layout { grid-template-columns: 1fr; }
@media (min-width: 768px) { .featured-grid-layout { grid-template-columns: repeat(3, 1fr); } }

.success-story-card {
  position: relative;
  overflow: hidden;
  padding: 2.25rem 2rem;
  border-radius: 1.5rem;
}

.story-category-tag {
  align-self: flex-start;
  font-size: 0.7rem;
  font-weight: 700;
  color: var(--color-gold);
  background: rgba(201,164,75,0.1);
  padding: 0.25rem 0.6rem;
  border-radius: 9999px;
  margin-bottom: 1rem;
}

.success-story-card .quote-text.clamped {
  display: -webkit-box;
  -webkit-line-clamp: 4;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.author-avatar-flag {
  width: 3rem;
  height: 3rem;
  border-radius: 50%;
  background: rgba(255,255,255,0.08);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  box-shadow: inset 0 2px 4px rgba(255,255,255,0.05);
  flex-shrink: 0;
}

/* Proofs layout */
.proofs-grid-layout { grid-template-columns: repeat(2, 1fr); }
@media (min-width: 768px) { .proofs-grid-layout { grid-template-columns: repeat(3, 1fr); } }
@media (min-width: 992px) { .proofs-grid-layout { grid-template-columns: repeat(4, 1fr); } }

.proof-card:hover {
  border-color: rgba(201,164,75,0.4) !important;
  box-shadow: 0 15px 30px rgba(183,151,74,0.25);
  transform: translateY(-5px);
}
.proof-card:hover img {
  transform: scale(1.08);
}
.proof-card:hover .proof-hover-overlay {
  opacity: 1 !important;
}

/* Gallery layout */
.gallery-grid-layout { grid-template-columns: 1fr; }
@media (min-width: 576px) { .gallery-grid-layout { grid-template-columns: repeat(2, 1fr); } }
@media (min-width: 992px) { .gallery-grid-layout { grid-template-columns: repeat(3, 1fr); } }

.gallery-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 40px rgba(11,29,58,0.15);
}
.gallery-card:hover img {
  transform: scale(1.06);
}
.gallery-card:hover .overlay {
  opacity: 1 !important;
}
.gallery-card:hover .view-icon {
  opacity: 1 !important;
  transform: scale(1) !important;
}
.gallery-card:hover .gallery-arrow-box {
  background: var(--color-gold) !important;
  color: var(--color-navy) !important;
}

.gallery-card-accent-line {
  height: 2px;
  background: linear-gradient(to right, transparent, var(--color-gold), transparent);
  transform: scaleX(0);
  transition: transform 0.5s ease;
}
.gallery-card:hover .gallery-card-accent-line {
  transform: scaleX(1);
}

/* Stats layout */
.stats-grid-testimonials { grid-template-columns: repeat(2, 1fr); }
@media (min-width: 768px) { .stats-grid-testimonials { grid-template-columns: repeat(4, 1fr); } }

/* Stories grid client filtering */
.stories-main-grid { grid-template-columns: 1fr; }
@media (min-width: 768px) { .stories-main-grid { grid-template-columns: repeat(2, 1fr); } }
@media (min-width: 992px) { .stories-main-grid { grid-template-columns: repeat(3, 1fr); } }

.stories-filter-buttons .filter-btn {
  padding: 0.6rem 1.25rem;
  border-radius: 0.75rem;
  font-family: var(--font-heading);
  font-weight: 600;
  font-size: 0.85rem;
  cursor: pointer;
  background: #fff;
  color: rgba(11,29,58,0.7);
  border: 1px solid rgba(11,29,58,0.08);
  transition: all 0.25s ease;
}

.stories-filter-buttons .filter-btn:hover {
  border-color: rgba(201,164,75,0.3);
  color: var(--color-navy);
  transform: translateY(-2px);
}

.stories-filter-buttons .filter-btn.active {
  background: var(--color-gold);
  color: var(--color-navy);
  border-color: var(--color-gold);
  box-shadow: 0 8px 20px rgba(201,164,75,0.2);
  transform: translateY(-2px);
  font-weight: 700;
}

.story-category-tag-dark {
  align-self: flex-start;
  font-size: 0.7rem;
  font-weight: 700;
  color: var(--color-gold);
  background: rgba(201,164,75,0.08);
  padding: 0.25rem 0.6rem;
  border-radius: 9999px;
  margin-bottom: 1rem;
}

.filterable-card {
  padding: 2rem;
  background: #fff;
  border: 1px solid rgba(11,29,58,0.05);
  box-shadow: var(--shadow-card);
  transition: all 0.3s ease;
  border-radius: 1.5rem;
}

.filterable-card:hover {
  transform: translateY(-5px);
  border-color: rgba(201,164,75,0.3);
  box-shadow: 0 15px 35px rgba(11,29,58,0.12);
}

.filterable-card .quote-text-dark.clamped {
  display: -webkit-box;
  -webkit-line-clamp: 4;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* CTA buttons layout */
.cta-btns { flex-direction: column; }
@media (min-width: 576px) {
  .cta-btns { flex-direction: row; }
}

/* Modals overlays default open transitions */
.modal-overlay {
  animation: fadeIn 0.25s ease both;
}
</style>

<!-- Scripts for Modal triggers & Quote expanders -->
<script>
document.addEventListener('DOMContentLoaded', () => {
  
  // 1. Play Video Modal
  const videoModal = document.getElementById('video-modal');
  const videoPlayer = document.getElementById('modal-video-player');
  
  document.querySelectorAll('.video-testimonial-card.playable').forEach(card => {
    card.addEventListener('click', () => {
      const videoUrl = card.dataset.videoUrl;
      if (videoUrl && videoModal && videoPlayer) {
        videoPlayer.src = videoUrl;
        videoModal.style.display = 'flex';
        videoPlayer.play().catch(e => console.log('Autoplay blocked', e));
      }
    });
  });

  // 2. Success Proof Document Lightbox
  const proofModal = document.getElementById('proof-modal');
  const proofImg = document.getElementById('proof-modal-img');
  const proofTitle = document.getElementById('proof-modal-title');
  const proofDesc = document.getElementById('proof-modal-desc');
  const proofDownload = document.getElementById('proof-download-btn');
  
  document.querySelectorAll('.proof-card').forEach(card => {
    card.addEventListener('click', () => {
      const src = card.dataset.image;
      const title = card.dataset.title;
      const desc = card.dataset.description;
      
      if (proofModal && proofImg && proofTitle && proofDesc && proofDownload) {
        proofImg.src = src;
        proofTitle.textContent = title;
        proofDesc.textContent = desc;
        proofDownload.href = src;
        proofModal.style.display = 'flex';
      }
    });
  });

  // 3. Gallery Lightbox
  const galleryModal = document.getElementById('gallery-modal');
  const galleryImg = document.getElementById('gallery-modal-img');
  
  document.querySelectorAll('.gallery-card').forEach(card => {
    card.addEventListener('click', () => {
      const src = card.dataset.image;
      if (galleryModal && galleryImg) {
        galleryImg.src = src;
        galleryModal.style.display = 'flex';
      }
    });
  });

  // Close Modals Helper
  function closeAllModals() {
    document.querySelectorAll('.modal-overlay').forEach(modal => {
      modal.style.display = 'none';
      const player = modal.querySelector('video');
      if (player) {
        player.pause();
        player.src = "";
      }
    });
  }

  // Click outside to close
  document.querySelectorAll('.modal-overlay').forEach(modal => {
    modal.addEventListener('click', (e) => {
      if (e.target === modal) {
        closeAllModals();
      }
    });
  });

  // Click X buttons to close
  document.querySelectorAll('.modal-overlay .modal-close, #proof-modal-close').forEach(btn => {
    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      closeAllModals();
    });
  });

  // Escape key to close
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
      closeAllModals();
    }
  });

  // 4. Quote Expanders (Featured Cards)
  document.querySelectorAll('.success-story-card').forEach(card => {
    const btn = card.querySelector('.expand-quote-btn');
    const p = card.querySelector('.quote-text');
    if (btn && p) {
      btn.addEventListener('click', () => {
        const isClamped = p.classList.contains('clamped');
        if (isClamped) {
          p.classList.remove('clamped');
          btn.textContent = 'Read less';
        } else {
          p.classList.add('clamped');
          btn.textContent = 'Read more';
        }
      });
    }
  });

  // Quote Expanders (Filterable Cards)
  document.querySelectorAll('.filterable-testimonial-item').forEach(card => {
    const btn = card.querySelector('.expand-quote-btn-dark');
    const p = card.querySelector('.quote-text-dark');
    if (btn && p) {
      btn.addEventListener('click', () => {
        const isClamped = p.classList.contains('clamped');
        if (isClamped) {
          p.classList.remove('clamped');
          btn.textContent = 'Read less';
        } else {
          p.classList.add('clamped');
          btn.textContent = 'Read more';
        }
      });
    }
  });

  // 5. Grid client filter trigger
  const filterButtons = document.querySelectorAll('.stories-filter-buttons .filter-btn');
  const gridItems = document.querySelectorAll('.filterable-testimonial-item');

  filterButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      filterButtons.forEach(b => b.classList.remove('active'));
      btn.classList.add('active');
      
      const filter = btn.dataset.filter;
      gridItems.forEach(item => {
        if (filter === 'all' || item.dataset.type === filter) {
          item.style.display = 'block';
        } else {
          item.style.display = 'none';
        }
      });
    });
  });

});
</script>

<?php
require_once __DIR__ . '/includes/footer.php';
require_once __DIR__ . '/includes/scripts.php';
?>
</body>
</html>
