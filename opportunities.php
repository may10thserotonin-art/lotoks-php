<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/db/connect.php';
requireUserAuth('/login.php');

$current_page = 'opportunities';
$user = getCurrentUser();

// Fetch public listings from local database
$listings = [];
try {
    $db = getDb();
    $listings = $db->query("SELECT id, title, employer, country, sponsorship_type, salary_range as salary, type, created_at FROM listings WHERE active = 1 ORDER BY created_at DESC")->fetchAll();
} catch (Throwable $e) {}

// Build unique country list from listings for the dropdown
$uniqueCountries = [];
foreach ($listings as $item) {
    $c = trim($item['country'] ?? '');
    if ($c !== '' && !in_array($c, $uniqueCountries, true)) {
        $uniqueCountries[] = $c;
    }
}
sort($uniqueCountries);

$page_title       = 'Opportunities — Lotoks';
$page_description = 'Browse job sponsorship, visa, and education opportunities on Lotoks.';
?>
<!DOCTYPE html>
<html lang="en">
<?php include __DIR__ . '/includes/head.php'; ?>
<body class="page-loaded" style="background-color:var(--color-surface)">

<div style="min-height:100vh;background:var(--color-surface)">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <main class="portal-main" style="background:var(--color-surface)">
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
            <div><h1 style="font-size:1rem;color:var(--color-navy);font-weight:700;margin:0;">Opportunities</h1></div>
            <div></div>
        </header>
        <!-- Header / Search Section -->
        <section class="opportunities-header">
            <h2>Global <span>Opportunities.</span></h2>

            <div class="opp-search-bar">
                <div class="opp-search-input-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="search-input" class="opp-search-input" placeholder="Search position, employer, or country..." oninput="filterListings()">
                </div>
                <div style="display:flex;gap:1rem;position:relative">
                    <button id="country-btn" onclick="toggleCountryDropdown(event)" style="display:flex;align-items:center;gap:.5rem;padding:.75rem 1.5rem;border-radius:.75rem;background:#fff;font-weight:700;font-size:.75rem;border:none;cursor:pointer;white-space:nowrap">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--color-primary);flex-shrink:0"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        <span id="country-label">All Countries</span>
                        <svg id="country-chevron" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:.5;transition:transform .2s"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
                    <div id="country-dropdown" style="display:none;position:absolute;top:calc(100% + .5rem);left:0;z-index:50;min-width:200px;max-height:260px;overflow-y:auto;background:#fff;border-radius:.75rem;box-shadow:0 8px 32px rgba(0,0,0,.12);padding:.5rem" data-dropdown>
                        <div style="padding:.5rem .75rem;border-radius:.5rem;cursor:pointer;font-size:.8125rem;font-weight:600;transition:background .15s" data-country="" class="country-option">All Countries</div>
                        <?php foreach ($uniqueCountries as $c): ?>
                        <div style="padding:.5rem .75rem;border-radius:.5rem;cursor:pointer;font-size:.8125rem;font-weight:500;transition:background .15s" data-country="<?= htmlspecialchars($c, ENT_QUOTES) ?>" class="country-option"><?= htmlspecialchars($c) ?></div>
                        <?php endforeach; ?>
                    </div>
                    <button onclick="filterListings()" style="display:flex;align-items:center;gap:.5rem;padding:.75rem 1.5rem;border-radius:.75rem;background:var(--color-primary);color:#fff;font-weight:700;font-size:.75rem;border:none;cursor:pointer;box-shadow:0 4px 16px rgba(35,73,225,.25)">
                        Find Sponsorship
                    </button>
                </div>
            </div>
        </section>

        <!-- Listings Section -->
        <section style="padding:3rem 1.5rem">
            <div style="max-width:1200px;margin:0 auto">
                <!-- Filter Tabs -->
                <div class="opp-filter-tabs" id="filter-tabs">
                    <button class="opp-filter-tab active" data-filter="all" onclick="setFilter('all', this)">All Listings</button>
                    <button class="opp-filter-tab" data-filter="job" onclick="setFilter('job', this)">Job Sponsorship</button>
                    <button class="opp-filter-tab" data-filter="edu" onclick="setFilter('edu', this)">Education</button>
                </div>

                <!-- Loading / Empty / Grid -->
                <div id="listings-empty" class="dash-empty" style="display:none;background:#fff;border-color:rgba(0,0,0,.07)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--color-outline-variant)"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <p style="color:var(--color-on-surface-variant);font-weight:500">No listings found</p>
                    <small style="color:var(--color-outline-variant)">Check back later for new opportunities</small>
                </div>

                <div class="opp-grid" id="listings-grid">
                    <?php foreach ($listings as $item): ?>
                    <article class="opp-card fade-up"
                        data-type="<?= htmlspecialchars($item['type'] ?? '') ?>"
                        data-title="<?= strtolower(htmlspecialchars($item['title'] ?? '')) ?>"
                        data-employer="<?= strtolower(htmlspecialchars($item['employer'] ?? '')) ?>"
                        data-country="<?= strtolower(htmlspecialchars($item['country'] ?? '')) ?>">
                        <div class="opp-card-header">
                            <div class="opp-card-icon">
                                <?php if (($item['type'] ?? '') === 'job'): ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                                <?php else: ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                                <?php endif; ?>
                            </div>
                            <div class="opp-card-badges">
                                <span style="padding:.25rem .75rem;border-radius:9999px;background:rgba(34,197,94,.1);color:#16a34a;font-size:.625rem;font-weight:700;text-transform:uppercase;letter-spacing:.06em">
                                    <?= htmlspecialchars($item['sponsorship_type'] ?? 'Sponsored') ?>
                                </span>
                                <span style="font-size:.75rem;font-weight:700;color:var(--color-primary)"><?= htmlspecialchars($item['country'] ?? '') ?></span>
                            </div>
                        </div>

                        <h5><?= htmlspecialchars($item['title'] ?? 'Untitled') ?></h5>
                        <?php if (!empty($item['employer'])): ?>
                        <p class="employer"><?= htmlspecialchars($item['employer']) ?></p>
                        <?php endif; ?>

                        <div class="opp-card-tags">
                            <span class="opp-tag"><?= ($item['type'] ?? '') === 'job' ? 'Full-time' : 'Education' ?></span>
                            <span class="opp-tag"><?= htmlspecialchars(str_replace('_', ' ', $item['sponsorship_type'] ?? 'Sponsored')) ?></span>
                        </div>

                        <div class="opp-card-footer">
                            <div>
                                <p class="opp-salary-label">Salary/Funding</p>
                                <p class="opp-salary-value"><?= htmlspecialchars($item['salary'] ?? 'Competitive') ?></p>
                            </div>
                            <a href="#" class="opp-apply-btn opp-apply-link" data-type="<?= urlencode($item['type'] ?? '') ?>">
                                Apply Now
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="7" y1="17" x2="17" y2="7"/><polyline points="7 7 17 7 17 17"/></svg>
                            </a>
                        </div>
                    </article>
                    <?php endforeach; ?>
                </div>

                <?php if (empty($listings)): ?>
                <div class="dash-empty" style="background:#fff;border-color:rgba(0,0,0,.07)">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--color-outline-variant)"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <p style="color:var(--color-on-surface-variant);font-weight:500">No listings found</p>
                    <small style="color:var(--color-outline-variant)">Check back later for new opportunities</small>
                </div>
                <?php endif; ?>
            </div>
        </section>
    </main>
</div>

<!-- Mobile Tab Bar rendered inside sidebar.php -->

<?php include __DIR__ . '/includes/scripts.php'; ?>
<script>
let currentFilter = 'all';
let currentCountry = '';

function setFilter(filter, btn) {
    currentFilter = filter;
    document.querySelectorAll('.opp-filter-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    filterListings();
}

function toggleCountryDropdown(e) {
    e.stopPropagation();
    const dd = document.getElementById('country-dropdown');
    const ch = document.getElementById('country-chevron');
    const isOpen = dd.style.display !== 'none';
    dd.style.display = isOpen ? 'none' : 'block';
    if (ch) ch.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
}

function selectCountry(country) {
    currentCountry = country;
    const label = document.getElementById('country-label');
    label.textContent = country || 'All Countries';
    document.getElementById('country-dropdown').style.display = 'none';
    const ch = document.getElementById('country-chevron');
    if (ch) ch.style.transform = 'rotate(0deg)';

    // Highlight selected option
    document.querySelectorAll('.country-option').forEach(el => {
        el.style.background = el.dataset.country === country ? 'rgba(35,73,225,.08)' : '';
        el.style.fontWeight = el.dataset.country === country ? '700' : '';
    });

    filterListings();
}

function filterListings() {
    const query = document.getElementById('search-input').value.toLowerCase().trim();
    const cards = document.querySelectorAll('#listings-grid .opp-card');
    let visibleCount = 0;

    cards.forEach(card => {
        const type    = card.dataset.type    || '';
        const title   = card.dataset.title   || '';
        const employer= card.dataset.employer|| '';
        const country = card.dataset.country || '';

        const matchFilter  = (currentFilter === 'all') || (type === currentFilter);
        const matchCountry = !currentCountry || (country === currentCountry.toLowerCase());
        const matchSearch  = !query || title.includes(query) || employer.includes(query) || country.includes(query);

        if (matchFilter && matchCountry && matchSearch) {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    const emptyEl = document.getElementById('listings-empty');
    if (emptyEl) emptyEl.style.display = (visibleCount === 0 && cards.length > 0) ? 'flex' : 'none';
}

// Close dropdown when clicking outside
document.addEventListener('click', function (e) {
    var dd = document.getElementById('country-dropdown');
    var btn = document.getElementById('country-btn');
    if (dd && btn && !btn.contains(e.target) && !dd.contains(e.target)) {
        dd.style.display = 'none';
        var ch = document.getElementById('country-chevron');
        if (ch) ch.style.transform = 'rotate(0deg)';
    }
});

// Country dropdown click delegation
document.getElementById('country-dropdown').addEventListener('click', function (e) {
    var opt = e.target.closest('.country-option');
    if (!opt) return;
    selectCountry(opt.dataset.country || '');
});

document.addEventListener('DOMContentLoaded', function() {
    const BASE = window.LOTOKS_CONFIG?.BASE || '';
    document.querySelectorAll('.opp-apply-link').forEach(link => {
        const type = link.dataset.type || '';
        link.href = BASE + '/apply.php' + (type ? '?type=' + type : '');
    });
});
</script>
</body>
</html>
