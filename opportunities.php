<?php
require_once __DIR__ . '/includes/auth.php';
requireUserAuth('/login.php');

$current_page = 'opportunities';
$user = getCurrentUser();

// Fetch public listings from API
$listings = [];
try {
    $ch = curl_init(rtrim(getenv('API_BASE_URL') ?: 'http://localhost:3001/api', '/') . '/listings/public');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 5,
    ]);
    $body = curl_exec($ch);
    if (!curl_errno($ch)) {
        $data = json_decode($body, true);
        $listings = $data['listings'] ?? [];
    }
    curl_close($ch);
} catch (Throwable $e) {}

$page_title       = 'Opportunities — Lotoks';
$page_description = 'Browse job sponsorship, visa, and education opportunities on Lotoks.';
?>
<!DOCTYPE html>
<html lang="en">
<?php include __DIR__ . '/includes/head.php'; ?>
<body class="page-loaded" style="background-color:var(--color-surface)">

<div style="min-height:100vh;background:var(--color-surface)">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <main class="portal-main" style="background:var(--color-surface)">
        <!-- Header / Search Section -->
        <section class="opportunities-header">
            <h2>Global <span>Opportunities.</span></h2>

            <div class="opp-search-bar">
                <div class="opp-search-input-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="search-input" class="opp-search-input" placeholder="Search position, employer, or country..." oninput="filterListings()">
                </div>
                <div style="display:flex;gap:1rem">
                    <button style="display:flex;align-items:center;gap:.5rem;padding:.75rem 1.5rem;border-radius:.75rem;background:#fff;font-weight:700;font-size:.75rem;border:none;cursor:pointer;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--color-primary)"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                        All Countries
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="opacity:.5"><polyline points="6 9 12 15 18 9"/></svg>
                    </button>
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

function setFilter(filter, btn) {
    currentFilter = filter;
    document.querySelectorAll('.opp-filter-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
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

        const matchFilter = (currentFilter === 'all') || (type === currentFilter);
        const matchSearch = !query || title.includes(query) || employer.includes(query) || country.includes(query);

        if (matchFilter && matchSearch) {
            card.style.display = '';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    const emptyEl = document.getElementById('listings-empty');
    if (emptyEl) emptyEl.style.display = (visibleCount === 0 && cards.length > 0) ? 'flex' : 'none';
}

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
