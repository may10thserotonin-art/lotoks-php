<?php
require_once __DIR__ . '/includes/auth.php';
requireUserAuth('/login.php');

$current_page = 'apply';
$user = getCurrentUser();

$page_title       = 'Apply — Lotoks';
$page_description = 'Start a new visa, job, education, or permanent residency application with Lotoks.';
?>
<!DOCTYPE html>
<html lang="en">
<?php include __DIR__ . '/includes/head.php'; ?>
<body class="page-loaded" style="background-color:#0B1D3A">

<div class="portal-wrap">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <main class="portal-main">
        <!-- Mobile topbar -->
        <header class="portal-topbar">
            <button class="sidebar-toggle-btn" id="sidebar-toggle" aria-label="Open navigation menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="7" x2="21" y2="7"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="17" x2="21" y2="17"/>
                </svg>
            </button>
            <a href="<?= BASE ?>/index.php" class="topbar-brand">Lotoks<span>.</span></a>
            <div><h1 style="font-size:1rem;color:#fff;font-weight:700;margin:0;">Apply</h1></div>
            <div></div>
        </header>
        <div class="portal-content" style="padding-top:1.5rem;padding-bottom:6rem" id="apply-wrap">
            <!-- Header -->
            <header style="margin-bottom:2rem" id="apply-header">
                <h2 style="font-size:1.875rem;font-weight:700;color:#fff;margin-bottom:.5rem">
                    Your <span style="color:var(--color-gold)">Application.</span>
                </h2>
                <p style="font-size:.875rem;color:rgba(255,255,255,.5);font-weight:500">Complete the steps below to submit your sponsorship request.</p>
            </header>

            <!-- Progress Steps -->
            <div class="apply-steps" id="apply-steps-bar">
                <?php
                $steps = [
                    ['id'=>'types',    'label'=>'Type'],
                    ['id'=>'personal', 'label'=>'Personal'],
                    ['id'=>'interview','label'=>'Questions'],
                    ['id'=>'docs',     'label'=>'Docs List'],
                    ['id'=>'upload',   'label'=>'Upload'],
                    ['id'=>'review',   'label'=>'Review'],
                ];
                foreach ($steps as $i => $s):
                ?>
                <div class="apply-step" data-step="<?= $i ?>">
                    <div class="apply-step-dot <?= $i === 0 ? 'active' : 'inactive' ?>" id="step-dot-<?= $i ?>">
                        <?php if ($i < 0): /* replaced by JS */ ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        <?php else: ?>
                        <span><?= $i + 1 ?></span>
                        <?php endif; ?>
                    </div>
                    <span class="apply-step-label <?= $i === 0 ? 'active' : 'inactive' ?>" id="step-label-<?= $i ?>"><?= $s['label'] ?></span>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Step Panel -->
            <div class="apply-panel" id="step-panel">
                <!-- Steps injected by JS -->
            </div>

            <!-- Success Screen (hidden until submitted) -->
            <div class="apply-success" id="apply-success" style="display:none">
                <div class="success-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                </div>
                <h3 style="font-size:1.5rem;font-weight:700;color:#fff;margin-bottom:.75rem">Application Submitted!</h3>
                <p style="color:rgba(255,255,255,.6);font-size:.875rem;margin-bottom:2rem;max-width:28rem;margin-inline:auto">
                    Your application has been received. Our team will review it and get back to you within 3–5 business days.
                </p>
                <div style="display:flex;gap:.75rem;justify-content:center;flex-wrap:wrap">
                    <a id="link-dashboard" href="#" style="padding:.75rem 2rem;border-radius:9999px;background:var(--color-gold);color:var(--color-navy);font-weight:700;box-shadow:0 4px 16px rgba(201,164,75,.3)">Go to Dashboard</a>
                    <a id="link-opportunities" href="#" style="padding:.75rem 2rem;border-radius:9999px;border:1px solid rgba(255,255,255,.2);color:#fff;font-weight:700">Browse Opportunities</a>
                </div>
            </div>
        </div><!-- /portal-content -->
    </main>
</div>

<?php include __DIR__ . '/includes/scripts.php'; ?>
<script>
// ── Application State ──────────────────────────────────────────────────
const CSRF = '<?= htmlspecialchars(generateCsrfToken()) ?>';
const USER_NAME    = <?= json_encode($user['name']    ?? '') ?>;
const USER_COUNTRY = <?= json_encode($user['country'] ?? '') ?>;

// Country list for dropdowns — injected from PHP
const COUNTRIES = <?= json_encode(array_values(countryList())) ?>;

const SPONSORSHIP_TYPES = [
    { id: 'visa', label: 'Visa Sponsorship',      desc: 'Work, tourist, or family visas',            icon: 'globe' },
    { id: 'job',  label: 'Job Sponsorship',        desc: 'Employer-sponsored work positions',          icon: 'briefcase' },
    { id: 'edu',  label: 'Education Scholarship',  desc: 'Study abroad funding & scholarships',        icon: 'graduation' },
    { id: 'pr',   label: 'Permanent Residency',    desc: 'PR pathway programs & support',              icon: 'users' },
];

const INTERVIEW_QUESTIONS = [
    { id:'visa_purpose',       label:'What is the purpose of your travel?',                            type:'select', required:true,  types:['visa'],
      options:[{l:'Tourism',v:'tourism'},{l:'Business',v:'business'},{l:'Study',v:'study'},{l:'Family Visit',v:'family'},{l:'Medical',v:'medical'},{l:'Other',v:'other'}] },
    { id:'visa_travel_dates',  label:'What are your planned travel dates?',                            type:'text',   required:true,  types:['visa'], placeholder:'e.g., June 2026 – August 2026' },
    { id:'visa_visited_before',label:'Have you visited this country before?',                          type:'select', required:true,  types:['visa'], options:[{l:'Yes',v:'yes'},{l:'No',v:'no'}] },
    { id:'visa_accommodation', label:'Do you have accommodation arranged?',                            type:'select', required:false, types:['visa'], options:[{l:'Yes, I have accommodation',v:'yes'},{l:'Not yet arranged',v:'no'}] },
    { id:'visa_denied',        label:'Have you ever had a visa application denied?',                   type:'select', required:true,  types:['visa'], options:[{l:'Yes',v:'yes'},{l:'No',v:'no'}] },
    { id:'edu_field',          label:'What field of study are you interested in?',                     type:'text',   required:true,  types:['edu'], placeholder:'e.g., Computer Science, Medicine' },
    { id:'edu_universities',   label:'Do you have preferred universities or institutions?',            type:'textarea',required:false,types:['edu'], placeholder:'List any institutions you are considering...' },
    { id:'edu_level',          label:'What is your highest education level completed?',                type:'select', required:true,  types:['edu'],
      options:[{l:'High School / Secondary',v:'secondary'},{l:'Diploma / Associate',v:'diploma'},{l:"Bachelor's Degree",v:'bachelor'},{l:"Master's Degree",v:'master'},{l:'PhD / Doctorate',v:'phd'}] },
    { id:'edu_gpa',            label:'What is your current GPA or grade range?',                      type:'select', required:true,  types:['edu'],
      options:[{l:'4.0+ / A (Excellent)',v:'excellent'},{l:'3.0–3.9 / B (Good)',v:'good'},{l:'2.0–2.9 / C (Average)',v:'average'},{l:'Below 2.0 / D',v:'below'}] },
    { id:'edu_scholarship',    label:'Do you already have a scholarship or funding?',                  type:'select', required:true,  types:['edu'],
      options:[{l:'Yes, I have funding',v:'yes'},{l:'No, I need funding',v:'no'},{l:'Partial funding',v:'partial'}] },
    { id:'edu_english',        label:'Do you have an English proficiency test score?',                 type:'select', required:false, types:['edu'],
      options:[{l:'IELTS',v:'ielts'},{l:'TOEFL',v:'toefl'},{l:'PTE Academic',v:'pte'},{l:'None yet',v:'none'}] },
    { id:'job_title',          label:'What is your current job title?',                               type:'text',   required:true,  types:['job'], placeholder:'e.g., Software Engineer, Registered Nurse' },
    { id:'job_experience',     label:'How many years of professional experience do you have?',         type:'select', required:true,  types:['job'],
      options:[{l:'Less than 1 year',v:'entry'},{l:'1–3 years',v:'junior'},{l:'4–7 years',v:'mid'},{l:'8–15 years',v:'senior'},{l:'15+ years',v:'executive'}] },
    { id:'job_industry',       label:'What industry do you work in?',                                 type:'text',   required:true,  types:['job'], placeholder:'e.g., Healthcare, Technology, Finance' },
    { id:'job_relocate',       label:'Are you willing to relocate internationally?',                   type:'select', required:true,  types:['job'],
      options:[{l:'Yes, anywhere',v:'anywhere'},{l:'Yes, to specific countries',v:'specific'},{l:'I need more information',v:'unsure'},{l:'Not at this time',v:'no'}] },
    { id:'job_salary',         label:'What is your expected salary range (annual, gross)?',            type:'select', required:true,  types:['job'],
      options:[{l:'Under $30,000',v:'low'},{l:'$30,000 – $60,000',v:'mid_low'},{l:'$60,000 – $100,000',v:'mid'},{l:'$100,000 – $150,000',v:'high'},{l:'$150,000+',v:'exec'}] },
    { id:'job_cv',             label:'Do you have an updated CV/Resume ready?',                       type:'select', required:false, types:['job'],
      options:[{l:'Yes, ready to upload',v:'yes'},{l:'I need to prepare one',v:'no'}] },
    { id:'job_certifications', label:'Do you have any professional certifications?',                   type:'textarea',required:false,types:['job'], placeholder:'List relevant certifications...' },
    { id:'pr_reason',          label:'What is your primary reason for seeking permanent residence?',   type:'select', required:true,  types:['pr'],
      options:[{l:'Work & career opportunities',v:'work'},{l:'Family reunification',v:'family'},{l:'Quality of life',v:'quality'},{l:'Education for children',v:'education'},{l:'Safety & stability',v:'safety'},{l:'Other',v:'other'}] },
    { id:'pr_family',          label:'Do you have family members already living there?',               type:'select', required:true,  types:['pr'],
      options:[{l:'Yes, immediate family',v:'immediate'},{l:'Yes, extended family',v:'extended'},{l:'No',v:'no'}] },
    { id:'pr_lived',           label:'Have you previously lived in this country?',                    type:'select', required:true,  types:['pr'],
      options:[{l:'Yes, I lived there',v:'yes'},{l:'Yes, for short visits',v:'visits'},{l:'No, never',v:'no'}] },
    { id:'pr_job_offer',       label:'Do you have a job offer in that country?',                      type:'select', required:true,  types:['pr'],
      options:[{l:'Yes, I have an offer',v:'yes'},{l:'I am actively looking',v:'looking'},{l:'Not yet',v:'no'}] },
];

/** Loaded from api/requirements.php — replaces the old CORE_DOCS / TYPE_DOCS */
let LOADED_REQUIREMENTS = [];

// ── App State ──
const state = {
    step: 0,
    types: [],
    personalInfo: { fullName: USER_NAME, dateOfBirth:'', nationality:'', phoneNumber:'', currentCountry: USER_COUNTRY, destinationCountry:'' },
    answers: {},
    uploadedDocs: {},  // docId -> { name, size, status: 'uploading'|'done'|'error' }
    documents: [],     // [{id, url, name}]
    consented: false,
    submitting: false,
    submitError: '',
};

// ── Helpers ──
function getRelevantQuestions() {
    return INTERVIEW_QUESTIONS.filter(q => q.types.some(t => state.types.includes(t)));
}

function getRequiredDocs() {
    const seen = new Set();
    const docs = [];
    for (const t of state.types) {
        for (const d of LOADED_REQUIREMENTS) {
            if (d.service_type === t && !seen.has(d.id)) {
                seen.add(d.id);
                docs.push(d);
            }
        }
    }
    return docs;
}

function canProceed() {
    if (state.step === 0) return state.types.length > 0;
    if (state.step === 1) {
        const p = state.personalInfo;
        return p.fullName && p.dateOfBirth && p.nationality && p.phoneNumber && p.currentCountry;
    }
    if (state.step === 2) {
        const qs = getRelevantQuestions().filter(q => q.required);
        return qs.every(q => (state.answers[q.id] || '').trim());
    }
    if (state.step === 3) return getRequiredDocs().length > 0;
    if (state.step === 4) {
        return getRequiredDocs().filter(d => d.required).every(d => state.uploadedDocs[d.id]?.status === 'done');
    }
    return true;
}

// ── Icon SVG snippets ──
const ICONS = {
    globe:      `<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>`,
    briefcase:  `<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>`,
    graduation: `<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>`,
    users:      `<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>`,
    check:      `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>`,
    arrowLeft:  `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>`,
    arrowRight: `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>`,
    file:       `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>`,
    upload:     `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="16 16 12 12 8 16"/><line x1="12" y1="12" x2="12" y2="21"/><path d="M20.39 18.39A5 5 0 0 0 18 9h-1.26A8 8 0 1 0 3 16.3"/></svg>`,
    spinner:    `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="spin-anim"><line x1="12" y1="2" x2="12" y2="6"/><line x1="12" y1="18" x2="12" y2="22"/><line x1="4.93" y1="4.93" x2="7.76" y2="7.76"/><line x1="16.24" y1="16.24" x2="19.07" y2="19.07"/><line x1="2" y1="12" x2="6" y2="12"/><line x1="18" y1="12" x2="22" y2="12"/><line x1="4.93" y1="19.07" x2="7.76" y2="16.24"/><line x1="16.24" y1="7.76" x2="19.07" y2="4.93"/></svg>`,
    alert:      `<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>`,
    trash:      `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>`,
};

// ── Render Steps Bar ──
function renderStepsBar() {
    const totalSteps = 6;
    for (let i = 0; i < totalSteps; i++) {
        const dot   = document.getElementById('step-dot-' + i);
        const label = document.getElementById('step-label-' + i);
        if (!dot || !label) continue;
        const isPast    = i < state.step;
        const isCurrent = i === state.step;

        dot.className = 'apply-step-dot ' + (isCurrent || isPast ? 'active' : 'inactive');
        dot.innerHTML = isPast ? ICONS.check : `<span>${i+1}</span>`;
        label.className = 'apply-step-label ' + (isCurrent || isPast ? 'active' : 'inactive');
    }
}

// ── Render Panel ──
function renderPanel() {
    const panel = document.getElementById('step-panel');
    if (!panel) return;

    let html = '';

    if (state.step === 0) {
        html = `<h4 class="apply-panel-title">Select Sponsorship Type(s)</h4>
<p class="apply-panel-subtitle">Choose a sponsorship pathway that matches your goals.</p>
<div class="type-grid">
${SPONSORSHIP_TYPES.map(t => {
    const sel = state.types.includes(t.id);
    return `<button class="type-btn ${sel?'selected':''}" onclick="toggleType('${t.id}')">
        <div class="type-icon ${sel?'selected':'unselected'}">${ICONS[t.icon]}</div>
        <p class="type-label ${sel?'selected':'unselected'}">${t.label}</p>
        <p class="type-desc">${t.desc}</p>
    </button>`;
}).join('')}
</div>`;

    } else if (state.step === 1) {
        const p = state.personalInfo;
        html = `<h4 class="apply-panel-title">Personal Information</h4>
<p class="apply-panel-subtitle">Provide your personal details for the application.</p>
<div style="display:grid;grid-template-columns:1fr;gap:1.25rem">
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.25rem">
${field('fullName','Full Name *','text',p.fullName,'Your full name')}
${field('dateOfBirth','Date of Birth *','date',p.dateOfBirth,'')}
${countryField('nationality','Nationality *',p.nationality,'Select your nationality')}
${field('phoneNumber','Phone Number *','tel',p.phoneNumber,'+234 800 000 0000')}
${countryField('currentCountry','Current Country of Residence *',p.currentCountry,'Select your current country')}
${countryField('destinationCountry','Destination Country',p.destinationCountry,'Target country (if known)')}
</div>
</div>`;

    } else if (state.step === 2) {
        const qs = getRelevantQuestions();
        const req = qs.filter(q=>q.required);
        const answered = req.filter(q=>(state.answers[q.id]||'').trim());
        html = `<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem">
<div>
  <h4 class="apply-panel-title" style="margin-bottom:.25rem">Interview Questions</h4>
  <p class="apply-panel-subtitle" style="margin-bottom:0">Answer the questions based on your selected sponsorship type(s).</p>
</div>
<span style="font-size:.75rem;color:var(--color-gold);font-weight:500;background:rgba(201,164,75,.1);padding:.25rem .75rem;border-radius:9999px">${answered.length}/${req.length} required</span>
</div>
${qs.map(q => {
    const val = state.answers[q.id] || '';
    if (q.type === 'select') {
        return `<div class="interview-question">
<label class="interview-label">${q.label}${q.required?' <span style="color:#f87171">*</span>':''}</label>
<select class="form-input form-select" onchange="updateAnswer('${q.id}', this.value)">
<option value="">Select an option…</option>
${(q.options||[]).map(o=>`<option value="${o.v}" ${val===o.v?'selected':''}>${o.l}</option>`).join('')}
</select></div>`;
    } else if (q.type === 'textarea') {
        return `<div class="interview-question">
<label class="interview-label">${q.label}${q.required?' <span style="color:#f87171">*</span>':''}</label>
<textarea class="form-input form-textarea" rows="4" placeholder="${q.placeholder||''}" onchange="updateAnswer('${q.id}',this.value)">${escHtml(val)}</textarea></div>`;
    } else {
        return `<div class="interview-question">
<label class="interview-label">${q.label}${q.required?' <span style="color:#f87171">*</span>':''}</label>
<input type="text" class="form-input" value="${escHtml(val)}" placeholder="${q.placeholder||''}" oninput="updateAnswer('${q.id}',this.value)"></div>`;
    }
}).join('')}`;

    } else if (state.step === 3) {
        const docs = getRequiredDocs();
        html = `<h4 class="apply-panel-title">Required Documents</h4>
<p class="apply-panel-subtitle">Based on your selections, the following documents are required. You will upload them in the next step.</p>
<div>
${docs.map(doc => {
    const isReq = doc.required;
    return `<div class="doc-item ${isReq?'core':''}">
<div style="color:${isReq?'var(--color-gold)':'rgba(255,255,255,.4)'};flex-shrink:0">${ICONS.file}</div>
<div style="flex:1">
    <div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.25rem">
        <span style="font-size:.875rem;font-weight:600;color:#fff">${doc.name}</span>
        <span class="doc-req-badge ${doc.required?'required':'optional'}">${doc.required?'Required':'Optional'}</span>
    </div>
    <p style="font-size:.75rem;color:rgba(255,255,255,.4)">${doc.desc}</p>
    <p style="font-size:.625rem;color:rgba(255,255,255,.2);margin-top:.125rem">Accepted: ${doc.accept}</p>
</div>
</div>`;
}).join('')}
</div>`;

    } else if (state.step === 4) {
        const docs = getRequiredDocs();
        html = `<h4 class="apply-panel-title">Upload Documents</h4>
<p class="apply-panel-subtitle">Upload each required document. All files must be clear and legible.</p>
<div id="upload-doc-list">
${docs.map(doc => renderUploadZone(doc)).join('')}
</div>`;

    } else if (state.step === 5) {
        const qs = getRelevantQuestions();
        const allReqUploaded = getRequiredDocs().filter(d=>d.required).every(d=>state.uploadedDocs[d.id]?.status==='done');
        html = `<h4 class="apply-panel-title">Review & Submit</h4>
<p class="apply-panel-subtitle">Please review your application before submitting.</p>
${state.submitError ? `<div class="alert alert-error">${ICONS.alert} <span>${escHtml(state.submitError)}</span></div>` : ''}
<div>
<div class="review-section">
    <p class="review-section-label">Sponsorship Type(s)</p>
    <div style="display:flex;flex-wrap:wrap;gap:.5rem">
        ${state.types.map(t=>{const st=SPONSORSHIP_TYPES.find(s=>s.id===t);return `<span style="padding:.25rem .75rem;border-radius:9999px;background:rgba(201,164,75,.1);color:var(--color-gold);font-size:.75rem;font-weight:600">${st?st.label:t}</span>`;}).join('')}
    </div>
</div>
<div class="review-section">
    <p class="review-section-label">Personal Information</p>
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:.75rem;font-size:.875rem">
        <div><span style="color:rgba(255,255,255,.4)">Name: </span><span style="color:#fff">${escHtml(state.personalInfo.fullName)}</span></div>
        <div><span style="color:rgba(255,255,255,.4)">DOB: </span><span style="color:#fff">${escHtml(state.personalInfo.dateOfBirth)}</span></div>
        <div><span style="color:rgba(255,255,255,.4)">Nationality: </span><span style="color:#fff">${escHtml(state.personalInfo.nationality)}</span></div>
        <div><span style="color:rgba(255,255,255,.4)">Phone: </span><span style="color:#fff">${escHtml(state.personalInfo.phoneNumber)}</span></div>
        <div><span style="color:rgba(255,255,255,.4)">From: </span><span style="color:#fff">${escHtml(state.personalInfo.currentCountry)}</span></div>
        ${state.personalInfo.destinationCountry?`<div><span style="color:rgba(255,255,255,.4)">To: </span><span style="color:#fff">${escHtml(state.personalInfo.destinationCountry)}</span></div>`:''}
    </div>
</div>
${qs.length>0?`<div class="review-section">
    <p class="review-section-label">Questionnaire Answers</p>
    <div style="display:flex;flex-direction:column;gap:.5rem;font-size:.875rem">
        ${qs.map(q=>`<div style="display:flex;justify-content:space-between;gap:1rem"><span style="color:rgba(255,255,255,.5);flex:1">${q.label}</span><span style="color:#fff;font-weight:500">${escHtml(state.answers[q.id]||'—')}</span></div>`).join('')}
    </div>
</div>`:''}
<div class="review-section">
    <p class="review-section-label">Documents</p>
    <div style="display:flex;align-items:center;gap:.5rem;font-size:.875rem">
        ${allReqUploaded
            ? `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#4ade80" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg><span style="color:#4ade80">All required documents uploaded</span>`
            : `${ICONS.alert}<span style="color:var(--color-gold)">Some required documents still missing</span>`}
    </div>
</div>
<label class="consent-box" onclick="toggleConsent()">
    <input type="checkbox" id="consent-check" ${state.consented?'checked':''} onchange="state.consented=this.checked;renderNav()">
    <div>
        <p class="consent-title">I confirm that all the information provided is accurate and complete.</p>
        <p class="consent-sub">I understand that submitting false information may result in rejection.</p>
    </div>
</label>
</div>`;
    }

    panel.innerHTML = html;
    renderNav();
}

function renderUploadZone(doc) {
    const u = state.uploadedDocs[doc.id];
    let zoneClass = 'upload-drop-zone';
    let zoneContent = '';
    if (!u) {
        zoneContent = `<div style="color:rgba(255,255,255,.4);margin-bottom:.5rem">${ICONS.upload}</div>
<p style="font-size:.875rem;color:rgba(255,255,255,.6);font-weight:500">Drop file here or <label style="color:var(--color-gold);cursor:pointer"><input type="file" style="display:none" accept="${doc.accept}" onchange="uploadDoc('${doc.id}',this)">click to browse</label></p>
<p style="font-size:.7rem;color:rgba(255,255,255,.2);margin-top:.25rem">Accepted: ${doc.accept}</p>`;
    } else if (u.status === 'uploading') {
        zoneClass += ' uploading';
        zoneContent = `<p style="font-size:.875rem;color:var(--color-gold);font-weight:500">${ICONS.spinner} Uploading ${escHtml(u.name)}…</p>
<div class="upload-progress-bar"><div class="upload-progress-bar-fill"></div></div>`;
    } else if (u.status === 'done') {
        zoneClass += ' done';
        zoneContent = `<div style="display:flex;align-items:center;gap:.5rem;color:#4ade80;margin-bottom:.25rem">${ICONS.check}<span style="font-weight:600;font-size:.875rem">${escHtml(u.name)}</span></div>
<div style="display:flex;align-items:center;gap:.5rem;margin-top:.25rem">
<p style="font-size:.7rem;color:rgba(255,255,255,.3)">${formatBytes(u.size)}</p>
<button onclick="removeDoc('${doc.id}')" style="font-size:.7rem;color:#f87171;background:none;border:none;cursor:pointer;display:inline-flex;align-items:center;gap:.25rem">${ICONS.trash} Remove</button>
</div>`;
    } else {
        zoneClass += ' error';
        zoneContent = `<p style="font-size:.875rem;color:#f87171;font-weight:500">Upload failed — <label style="cursor:pointer;text-decoration:underline"><input type="file" style="display:none" accept="${doc.accept}" onchange="uploadDoc('${doc.id}',this)">Try again</label></p>`;
    }

    const isReq = doc.required;
    return `<div style="margin-bottom:1.25rem">
<div style="display:flex;align-items:center;gap:.5rem;margin-bottom:.5rem">
    <div style="color:${isReq?'var(--color-gold)':'rgba(255,255,255,.4)'}">${ICONS.file}</div>
    <span style="font-size:.875rem;font-weight:600;color:#fff">${doc.name}</span>
    <span class="doc-req-badge ${doc.required?'required':'optional'}">${doc.required?'Required':'Optional'}</span>
</div>
<div class="${zoneClass}">${zoneContent}</div>
</div>`;
}

function renderNav() {
    const panel = document.getElementById('step-panel');
    if (!panel) return;

    // Remove old nav if exists
    let navEl = document.getElementById('apply-nav');
    if (navEl) navEl.remove();

    if (state.step > 5) return; // success shown

    const canGo = canProceed();
    const isLast = state.step === 5;

    const nav = document.createElement('div');
    nav.id = 'apply-nav';
    nav.className = 'apply-nav';
    nav.innerHTML = `
${state.step > 0 ? `<button class="apply-back-btn" onclick="prevStep()">${ICONS.arrowLeft} Back</button>` : ''}
<div class="apply-spacer"></div>
${!isLast && state.step !== 0
    ? `<button class="apply-next-btn" ${canGo?'':'disabled'} onclick="nextStep()">Continue ${ICONS.arrowRight}</button>`
    : isLast 
        ? `<button class="apply-next-btn" id="submit-btn" ${(canGo && state.consented && !state.submitting)?'':'disabled'} onclick="submitApplication()">
        ${state.submitting ? ICONS.spinner + ' Submitting…' : ICONS.check + ' Submit Application'}
    </button>` 
        : ''}`;

    panel.appendChild(nav);
}

// ── Form field helper ──
function field(name, label, type, value, placeholder) {
    return `<div>
<label style="display:block;font-size:.625rem;font-weight:700;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.5rem">${label}</label>
<input type="${type}" class="form-input" value="${escHtml(value||'')}" placeholder="${placeholder||''}" oninput="updatePersonalInfo('${name}',this.value)" style="color-scheme:dark">
</div>`;
}

function countryField(name, label, value, placeholder) {
    let opts = '<option value="">' + (placeholder || 'Select Country') + '</option>';
    for (const c of COUNTRIES) {
        const sel = c === value ? ' selected' : '';
        opts += '<option value="' + escHtml(c) + '"' + sel + '>' + escHtml(c) + '</option>';
    }
    return `<div>
<label style="display:block;font-size:.625rem;font-weight:700;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.5rem">${label}</label>
<select class="form-input" onchange="updatePersonalInfo('${name}',this.value)" style="color-scheme:dark">${opts}</select>
</div>`;
}

// ── Handlers ──
function toggleType(id) {
    // Only allow single selection and automatically progress to next step
    state.types = [id];
    renderPanel();
    setTimeout(() => {
        if (state.step === 0 && canProceed()) {
            nextStep();
        }
    }, 200);
}

function updatePersonalInfo(field, value) {
    state.personalInfo[field] = value;
    document.getElementById('apply-nav') && (canProceed()
        ? document.querySelector('#apply-nav .apply-next-btn')?.removeAttribute('disabled')
        : document.querySelector('#apply-nav .apply-next-btn')?.setAttribute('disabled',''));
}

function updateAnswer(id, value) {
    state.answers[id] = value;
    const btn = document.querySelector('#apply-nav .apply-next-btn');
    if (btn) btn.disabled = !canProceed();
}

function toggleConsent() {
    state.consented = !state.consented;
    const cb = document.getElementById('consent-check');
    if (cb) cb.checked = state.consented;
    const btn = document.getElementById('submit-btn');
    if (btn) btn.disabled = !(state.consented && !state.submitting);
}

function nextStep() {
    if (!canProceed()) return;
    state.step = Math.min(state.step + 1, 5);
    renderStepsBar();
    renderPanel();
    document.getElementById('step-panel')?.scrollIntoView({behavior:'smooth',block:'start'});
}

function prevStep() {
    state.step = Math.max(state.step - 1, 0);
    renderStepsBar();
    renderPanel();
}

async function uploadDoc(docId, input) {
    const file = input.files[0];
    if (!file) return;

    state.uploadedDocs[docId] = { name: file.name, size: file.size, status: 'uploading' };
    refreshUploadZone(docId);

    try {
        const fd = new FormData();
        fd.append('file', file);
        fd.append('category', docId);
        fd.append('csrf_token', CSRF);

        const res = await fetch((window.LOTOKS_CONFIG?.API_BASE || '/api') + '/user/documents/upload.php', { method:'POST', body:fd, credentials:'include' });
        const data = await res.json();

        if (res.ok) {
            state.uploadedDocs[docId].status = 'done';
            state.documents.push({ id: docId, url: data.document?.filename || '', name: file.name });
        } else {
            state.uploadedDocs[docId].status = 'error';
        }
    } catch(e) {
        state.uploadedDocs[docId].status = 'error';
    }

    refreshUploadZone(docId);
    renderNav();
}

function removeDoc(docId) {
    delete state.uploadedDocs[docId];
    state.documents = state.documents.filter(d=>d.id!==docId);
    const docs = getRequiredDocs();
    const doc  = docs.find(d=>d.id===docId);
    if (doc) {
        const listEl = document.getElementById('upload-doc-list');
        if (listEl) listEl.innerHTML = docs.map(d=>renderUploadZone(d)).join('');
    }
    renderNav();
}

function refreshUploadZone(docId) {
    const docs = getRequiredDocs();
    const listEl = document.getElementById('upload-doc-list');
    if (listEl) listEl.innerHTML = docs.map(d=>renderUploadZone(d)).join('');
}

async function submitApplication() {
    if (!state.consented || state.submitting) return;
    state.submitting = true;
    state.submitError = '';
    renderNav();

    try {
        const res = await fetch((window.LOTOKS_CONFIG?.API_BASE || '/api') + '/user/applications.php', {
            method: 'POST',
            credentials: 'include',
            headers: { 'Content-Type':'application/json', 'X-CSRF-Token': CSRF },
            body: JSON.stringify({
                service_types:    state.types,
                sponsorship_type: state.types[0],
                personal_info:    state.personalInfo,
                answers:          state.answers,
                documents:        state.documents.map(d=>d.url),
                country:          state.personalInfo.currentCountry,
            })
        });
        const data = await res.json();
        if (res.ok) {
            // Show success
            document.getElementById('apply-steps-bar').style.display = 'none';
            document.getElementById('step-panel').style.display = 'none';
            document.getElementById('apply-header').innerHTML = `<h2 style="font-size:1.875rem;font-weight:700;color:#fff;margin-bottom:.5rem">Application <span style="color:var(--color-gold)">Submitted.</span></h2><p style="font-size:.875rem;color:rgba(255,255,255,.5)">Thank you for applying!</p>`;
            document.getElementById('apply-success').style.display = 'block';
        } else {
            state.submitError = data.message || 'Failed to submit application';
            state.submitting = false;
            renderPanel();
        }
    } catch(e) {
        state.submitError = e.message || 'Something went wrong';
        state.submitting = false;
        renderPanel();
    }
}

function escHtml(str) {
    return String(str || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

function formatBytes(b) {
    if (!b) return '0 B';
    const u = ['B','KB','MB','GB'];
    const i = Math.floor(Math.log(b)/Math.log(1024));
    return (b/Math.pow(1024,i)).toFixed(1)+' '+u[i];
}

// ── Spinner animation ──
const spinStyle = document.createElement('style');
spinStyle.textContent = '.spin-anim{animation:spin .75s linear infinite}@keyframes spin{from{transform:rotate(0deg)}to{transform:rotate(360deg)}}';
document.head.appendChild(spinStyle);

// ── Init ──
document.addEventListener('DOMContentLoaded', async function() {
    // Resolve BASE-prefixed links
    const BASE = window.LOTOKS_CONFIG?.BASE || '';
    const dlEl = document.getElementById('link-dashboard');
    const olEl = document.getElementById('link-opportunities');
    if (dlEl) dlEl.href = BASE + '/dashboard.php';
    if (olEl) olEl.href = BASE + '/opportunities.php';

    // Load document requirements from admin-managed table
    try {
        const res = await fetch(BASE + '/api/requirements.php');
        const data = await res.json();
        if (data.success) LOADED_REQUIREMENTS = data.requirements || [];
    } catch (e) {
        console.warn('Failed to load requirements, using empty list', e);
    }

    renderStepsBar();
    renderPanel();
});
</script>
</body>
</html>
