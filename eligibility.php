<?php
/**
 * Lotoks — Eligibility Check (eligibility.php)
 * Converted from pages/Eligibility.tsx
 * Pure client-side interactive quiz — no PHP state needed.
 */
require_once __DIR__ . '/includes/auth.php';

$is_logged_in = is_user_logged_in();

$page_title       = 'Check Your Eligibility | Lotoks';
$page_description = 'Answer a few questions and discover which global opportunities you qualify for — visa sponsorships, scholarships, job placements, and more.';
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<style>
.elig-page {
  min-height: 100vh;
  background: linear-gradient(135deg, var(--color-surface), #fff, var(--color-surface));
  padding: 5rem 1.5rem 3rem;
  position: relative;
  overflow: hidden;
}

.elig-page::before {
  content: '';
  position: absolute;
  top: 10rem;
  left: -5rem;
  width: 18rem;
  height: 18rem;
  background: rgba(35,73,225,0.05);
  border-radius: 50%;
  filter: blur(60px);
  pointer-events: none;
}

.elig-page::after {
  content: '';
  position: absolute;
  bottom: 10rem;
  right: -5rem;
  width: 24rem;
  height: 24rem;
  background: rgba(29,122,122,0.05);
  border-radius: 50%;
  filter: blur(60px);
  pointer-events: none;
}

.elig-option {
  display: flex;
  align-items: flex-start;
  gap: 1rem;
  padding: 1.25rem;
  text-align: left;
  border-radius: 0.75rem;
  border: 1px solid rgba(172,172,189,0.3);
  background: var(--color-surface-container-low);
  transition: all 0.25s ease;
  cursor: pointer;
  width: 100%;
  background: none;
}

.elig-option:hover {
  border-color: var(--color-primary);
  background: rgba(35,73,225,0.05);
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(35,73,225,0.1);
}

.elig-option.job-card {
  background: #fff;
  border-color: rgba(11,29,58,0.1);
}

.elig-option.job-card:hover {
  border-color: rgba(35,73,225,0.3);
  box-shadow: 0 8px 24px rgba(35,73,225,0.12);
}

.elig-icon {
  width: 3rem;
  height: 3rem;
  border-radius: 0.75rem;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #fff;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
  color: var(--color-primary);
  flex-shrink: 0;
  transition: transform 0.25s;
}

.elig-option:hover .elig-icon { transform: scale(1.1); }

.elig-icon.job { background: rgba(35,73,225,0.08); }

.elig-arrow {
  width: 2rem;
  height: 2rem;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--color-outline-variant);
  flex-shrink: 0;
  transition: color 0.2s, background 0.2s;
}

.elig-option:hover .elig-arrow {
  color: var(--color-primary);
  background: rgba(35,73,225,0.08);
}

/* Result section */
.elig-result-icon {
  width: 7rem;
  height: 7rem;
  border-radius: 50%;
  background: linear-gradient(135deg, #4ade80, #10b981);
  display: flex;
  align-items: center;
  justify-content: center;
  color: #fff;
  margin-inline: auto;
  margin-bottom: 2rem;
  position: relative;
  animation: scaleSpring 0.5s cubic-bezier(0.34,1.56,0.64,1) 0.2s both;
}

.elig-result-ring {
  position: absolute;
  inset: 0;
  border-radius: 50%;
  background: rgba(74,222,128,0.2);
  animation: pulsePing 2s ease-in-out infinite;
}

/* Back button */
.elig-back {
  margin-top: 2rem;
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.8rem;
  font-weight: 700;
  color: var(--color-outline-variant);
  background: none;
  border: none;
  cursor: pointer;
  transition: color 0.2s;
}
.elig-back:hover { color: var(--color-primary); }
.elig-back svg { transition: transform 0.2s; }
.elig-back:hover svg { transform: translateX(-4px); }
</style>

<div class="elig-page">
  <div style="max-width:48rem;margin-inline:auto;position:relative;z-index:10;">

    <!-- Back link -->
    <a href="/" style="display:inline-flex;align-items:center;gap:0.5rem;font-size:0.875rem;font-weight:700;color:var(--color-outline-variant);text-decoration:none;margin-bottom:2rem;transition:color 0.2s;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='var(--color-outline-variant)'">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
      Back to Home
    </a>

    <!-- Header -->
    <header style="text-align:center;margin-bottom:3rem;" class="page-enter">
      <div style="display:inline-flex;align-items:center;gap:0.5rem;background:rgba(35,73,225,0.05);border:1px solid rgba(35,73,225,0.1);color:var(--color-primary);font-size:0.75rem;font-weight:700;padding:0.375rem 1rem;border-radius:9999px;margin-bottom:1rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
        Eligibility Check
      </div>
      <h1 style="font-family:var(--font-heading);font-size:clamp(2rem,5vw,2.75rem);font-weight:700;color:var(--color-on-surface);margin-bottom:0.5rem;">
        Find Your <span style="color:var(--color-primary);">Pathway</span>
      </h1>
      <p style="font-size:0.9rem;color:var(--color-on-surface-variant);font-weight:500;">Answer a few questions and discover your global opportunities.</p>
    </header>

    <!-- Quiz card -->
    <div class="eligibility-card" id="quiz-card">

      <!-- Step panel -->
      <div id="quiz-step" class="elig-step-enter" style="flex:1;display:flex;flex-direction:column;">

        <!-- Progress bar -->
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:2.5rem;">
          <div class="progress-bar-track">
            <div class="progress-bar-fill" id="progress-fill" style="width:25%;"></div>
          </div>
          <span style="font-size:0.625rem;font-weight:700;color:var(--color-primary);letter-spacing:0.1em;text-transform:uppercase;background:rgba(35,73,225,0.05);padding:0.25rem 0.75rem;border-radius:9999px;white-space:nowrap;" id="step-counter">1 / 3</span>
        </div>

        <!-- Question -->
        <h2 style="font-family:var(--font-heading);font-size:1.5rem;font-weight:700;color:var(--color-on-surface);margin-bottom:2rem;display:flex;align-items:center;gap:0.75rem;" id="question-text">
          <!-- question renders here via JS -->
        </h2>

        <!-- Options -->
        <div id="options-grid" style="display:grid;grid-template-columns:1fr;gap:0.75rem;flex:1;"></div>

        <!-- Back button (hidden on step 1) -->
        <button class="elig-back" id="back-btn" style="display:none;" onclick="goBack()">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
          Previous Question
        </button>
      </div>

      <!-- Result panel (hidden until complete) -->
      <div id="quiz-result" style="display:none;flex:1;display:none;flex-direction:column;align-items:center;justify-content:center;text-align:center;padding:1.5rem 0;" class="elig-step-enter">
        <div class="elig-result-icon" id="result-icon">
          <div class="elig-result-ring"></div>
          <svg xmlns="http://www.w3.org/2000/svg" width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        </div>

        <h2 style="font-family:var(--font-heading);font-size:2rem;font-weight:700;color:var(--color-on-surface);margin-bottom:1rem;">You're Eligible!</h2>
        <p style="color:var(--color-on-surface-variant);max-width:28rem;margin-inline:auto;margin-bottom:1.5rem;font-weight:500;line-height:1.6;" id="result-message"></p>

        <div id="result-job-badge" style="display:none;margin-bottom:2rem;">
          <span style="display:inline-flex;align-items:center;gap:0.5rem;background:rgba(35,73,225,0.05);border:1px solid rgba(35,73,225,0.1);border-radius:9999px;padding:0.5rem 1rem;font-size:0.75rem;font-weight:700;color:var(--color-primary);" id="result-job-label"></span>
        </div>

        <div style="display:grid;gap:0.75rem;width:100%;max-width:22rem;">
          <a id="apply-btn" href="#" class="btn btn-primary btn-xl btn-pill arrow-parent" style="justify-content:center;">
            <?php if ($is_logged_in): ?>
              Start Your Application
            <?php else: ?>
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path><polyline points="10 17 15 12 10 7"></polyline><line x1="15" y1="12" x2="3" y2="12"></line></svg>
              Apply Now – Login Required
            <?php endif; ?>
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="arrow-hover"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
          </a>
          <?php if (!$is_logged_in): ?>
            <a href="<?= BASE ?>/register.php" style="font-size:0.75rem;font-weight:700;color:var(--color-outline-variant);text-align:center;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='var(--color-outline-variant)'">No account? Create one</a>
          <?php endif; ?>
          <button onclick="retake()" style="font-size:0.75rem;font-weight:700;color:var(--color-outline-variant);background:none;border:none;cursor:pointer;padding:0.5rem;transition:color 0.2s;" onmouseover="this.style.color='var(--color-primary)'" onmouseout="this.style.color='var(--color-outline-variant)'">
            Retake Assessment
          </button>
        </div>
      </div>

    </div><!-- /quiz-card -->
  </div>
</div>

<script>
(function () {
  const IS_LOGGED_IN = <?= json_encode($is_logged_in) ?>;

  // ── Data (exact from Eligibility.tsx) ──
  const JOB_CATEGORIES = [
    { label: 'Truck Driving & Logistics',   value: 'truck',       desc: 'Drive routes across Europe — Lithuania, Poland, Germany & more' },
    { label: 'Healthcare & Nursing',         value: 'health',      desc: 'Nursing, caregiving & medical positions in UK & Europe' },
    { label: 'IT & Technology',              value: 'tech',        desc: 'Software dev, IT support & tech roles with full sponsorship' },
    { label: 'Engineering & Construction',   value: 'engineering', desc: 'Civil, mechanical & electrical engineering opportunities' },
    { label: 'Hospitality & Services',       value: 'hospitality', desc: 'Chef, hotel, restaurant & tourism positions abroad' },
    { label: 'Skilled Trades & Labor',       value: 'trades',      desc: 'Welding, plumbing, electrical & general skilled labor' },
  ];

  const BASE_STEPS = [
    {
      id: 1,
      question: 'What is your primary goal?',
      icon: 'building',
      options: [
        { label: 'Work Overseas',      value: 'job',  icon: 'briefcase' },
        { label: 'Study & Scholarship',value: 'edu',  icon: 'graduation' },
        { label: 'Family Relocation',  value: 'visa', icon: 'globe' },
        { label: 'Permanent Residency',value: 'pr',   icon: 'user' },
      ]
    }
  ];

  const AGE_STEP = {
    id: 2, question: 'What is your current age group?', icon: 'users',
    options: [
      { label: '18 – 24', value: 'young', icon: 'users' },
      { label: '25 – 34', value: 'prime', icon: 'users' },
      { label: '35 – 44', value: 'mature', icon: 'users' },
      { label: '45+',     value: 'senior', icon: 'users' },
    ]
  };

  const EDU_STEP = {
    id: 3, question: 'Highest level of education?', icon: 'graduation',
    options: [
      { label: "PhD / Master's",         value: 'high', icon: 'graduation' },
      { label: "Bachelor's Degree",       value: 'mid',  icon: 'graduation' },
      { label: 'Diploma / High School',   value: 'low',  icon: 'graduation' },
    ]
  };

  // ── State ──
  let currentStep = 0;
  let answers     = {};
  let selectedGoal = null;
  let steps       = [];

  function buildSteps() {
    const s = [...BASE_STEPS];
    if (selectedGoal === 'job') {
      s.push({
        id: 2, question: 'Which job category interests you?', icon: 'briefcase', isJob: true,
        options: JOB_CATEGORIES.map(j => ({ label: j.label, value: j.value, desc: j.desc, icon: 'briefcase' }))
      });
    }
    s.push(AGE_STEP);
    s.push(EDU_STEP);
    // Fix IDs
    s.forEach((st, i) => st.id = i + 1);
    steps = s;
  }

  buildSteps();

  // ── Icon SVG factory ──
  function iconSvg(name, size = 22) {
    const icons = {
      briefcase:  `<rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>`,
      graduation: `<path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path>`,
      globe:      `<circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>`,
      user:       `<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle>`,
      users:      `<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path>`,
      building:   `<rect x="2" y="7" width="20" height="15"></rect><polyline points="17 2 12 7 7 2"></polyline>`,
    };
    return `<svg xmlns="http://www.w3.org/2000/svg" width="${size}" height="${size}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">${icons[name] || icons.briefcase}</svg>`;
  }

  // ── Render current step ──
  function render() {
    const step = steps[currentStep];
    const total = steps.length;
    const progress = ((currentStep + 1) / total) * 100;

    document.getElementById('progress-fill').style.width = progress + '%';
    document.getElementById('step-counter').textContent = `${currentStep + 1} / ${total}`;
    document.getElementById('question-text').innerHTML = iconSvg(step.icon || 'briefcase', 24) + ' ' + step.question;
    document.getElementById('back-btn').style.display = currentStep > 0 ? 'flex' : 'none';

    const grid = document.getElementById('options-grid');
    // 2-col for job categories, 1-col otherwise (responsive)
    grid.style.gridTemplateColumns = (step.isJob) ? 'repeat(auto-fill, minmax(14rem, 1fr))' : '1fr';

    // Animate out / in
    const quizStep = document.getElementById('quiz-step');
    quizStep.style.animation = 'none';
    quizStep.offsetHeight; // reflow
    quizStep.style.animation = '';
    quizStep.className = 'elig-step-enter';

    grid.innerHTML = step.options.map((opt, i) => {
      const isJob = step.isJob;
      return `
        <button
          class="elig-option${isJob ? ' job-card' : ''}"
          onclick="select('${opt.value}', ${step.id})"
          style="animation: fadeUpIn 0.3s ease ${i * 60}ms both;"
          aria-label="${opt.label}"
        >
          <div class="elig-icon${isJob ? ' job' : ''}">
            ${iconSvg(isJob ? 'briefcase' : (opt.icon || 'briefcase'))}
          </div>
          <div style="flex:1;min-width:0;">
            <span style="font-weight:700;display:block;color:var(--color-on-surface);font-size:${isJob ? '0.875rem' : '1rem'};">${opt.label}</span>
            ${opt.desc ? `<span style="font-size:0.75rem;color:var(--color-outline-variant);font-weight:500;margin-top:0.25rem;display:block;line-height:1.5;">${opt.desc}</span>` : ''}
          </div>
          <div class="elig-arrow">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
          </div>
        </button>`;
    }).join('');
  }

  // ── Handle selection ──
  window.select = function (value, stepId) {
    answers[stepId] = value;

    if (stepId === 1) {
      selectedGoal = value;
      buildSteps(); // rebuild with job categories if needed
    }

    if (currentStep < steps.length - 1) {
      currentStep++;
      render();
    } else {
      showResult();
    }
  };

  window.goBack = function () {
    if (currentStep > 0) { currentStep--; render(); }
  };

  // ── Show result ──
  function showResult() {
    document.getElementById('quiz-step').style.display = 'none';
    const resultEl = document.getElementById('quiz-result');
    resultEl.style.display = 'flex';
    resultEl.className = 'elig-step-enter';

    const selectedJob = selectedGoal === 'job'
      ? JOB_CATEGORIES.find(j => j.value === answers[2]) || null
      : null;

    let msg;
    if (selectedJob) {
      msg = `Based on your profile, you have an <strong>85% match</strong> for <strong>${selectedJob.label}</strong> positions in Europe. Our partners are actively hiring in this sector.`;
    } else {
      msg = `Based on your profile, you have an <strong>85% match</strong> for skilled worker sponsorship in the UK and Canada.`;
    }
    document.getElementById('result-message').innerHTML = msg;

    if (selectedJob) {
      document.getElementById('result-job-badge').style.display = 'block';
      document.getElementById('result-job-label').textContent = selectedJob.label;
    }

    const type = selectedGoal || 'visa';
    const catParam = (selectedGoal === 'job' && answers[2]) ? `&category=${answers[2]}` : '';
    const applyUrl = `/apply.php?type=${type}${catParam}`;

    const applyBtn = document.getElementById('apply-btn');
    if (IS_LOGGED_IN) {
      applyBtn.href = applyUrl;
    } else {
      applyBtn.href = `/login.php?redirect=${encodeURIComponent(applyUrl)}`;
    }
  }

  window.retake = function () {
    currentStep  = 0;
    answers      = {};
    selectedGoal = null;
    buildSteps();
    document.getElementById('quiz-step').style.display = '';
    document.getElementById('quiz-result').style.display = 'none';
    render();
  };

  // Init
  render();
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<?php require_once __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
