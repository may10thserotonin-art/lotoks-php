<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/db/connect.php';
requireUserAuth('/login.php');

$current_page = 'dashboard';
$user = getCurrentUser();

// Fetch dashboard stats from local database
$db = getDb();
$userId = (int)($user['id'] ?? 0);

$applications  = $db->prepare("SELECT COUNT(*) FROM applications WHERE user_id = ?");
$applications->execute([$userId]);
$applications = (int)$applications->fetchColumn();

$documents  = $db->prepare("SELECT COUNT(*) FROM user_documents WHERE user_id = ?");
$documents->execute([$userId]);
$documents = (int)$documents->fetchColumn();

$opportunities = $db->query("SELECT COUNT(*) FROM listings WHERE active = 1")->fetchColumn();
$opportunities = (int)$opportunities;

$recentApp = $db->prepare("SELECT id, sponsorship_type, status, created_at FROM applications WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
$recentApp->execute([$userId]);
$recentApp = $recentApp->fetch() ?: null;

$page_title       = 'Dashboard — Lotoks';
$page_description = 'Your Lotoks dashboard. Track applications, manage documents, and explore opportunities.';
?>
<!DOCTYPE html>
<html lang="en">
<?php
include __DIR__ . '/includes/head.php';
?>
<body class="page-loaded" style="background-color:#0B1D3A">

<div class="portal-wrap">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <main class="portal-main">
        <!-- Top Bar -->
        <header class="portal-topbar">
            <button class="sidebar-toggle-btn" id="sidebar-toggle" aria-label="Open navigation menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="7" x2="21" y2="7"/>
                    <line x1="3" y1="12" x2="21" y2="12"/>
                    <line x1="3" y1="17" x2="21" y2="17"/>
                </svg>
            </button>
            <a href="<?= BASE ?>/index.php" class="topbar-brand">Lotoks<span>.</span></a>
            <div>
                <h1>Dashboard</h1>
                <p>Welcome back, <?= htmlspecialchars($user['name'] ?? 'User') ?></p>
            </div>
            <div class="portal-topbar-actions">
                <button class="bell-btn" aria-label="Notifications">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    <span class="bell-dot"></span>
                </button>
                <a id="dash-logout-btn" href="#" class="logout-topbar-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Logout
                </a>
            </div>
        </header>

        <!-- Dashboard Content -->
        <div class="portal-content" id="dash-content" style="opacity:0; transform:translateY(16px); transition: opacity .5s ease, transform .5s ease;">
            <!-- Welcome Banner -->
            <div class="dash-welcome fade-up">
                <h2>Welcome to Lotoks</h2>
                <p>Your gateway to global opportunities. Track your applications, upload documents, and manage your journey all in one place.</p>
            </div>

            <!-- Quick Stats -->
            <div class="dash-stats">
                <div class="dash-stat-card fade-up" style="animation-delay:.1s">
                    <div class="dash-stat-icon" style="background:rgba(59,130,246,0.2);color:#60a5fa;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                    </div>
                    <p class="dash-stat-num"><?= $applications !== null ? htmlspecialchars($applications) : '—' ?></p>
                    <p class="dash-stat-label">Applications</p>
                    <p style="font-size:.7rem;color:rgba(255,255,255,.2);margin-top:.25rem">Active applications</p>
                </div>

                <div class="dash-stat-card fade-up" style="animation-delay:.15s">
                    <div class="dash-stat-icon" style="background:rgba(20,184,166,0.2);color:#2dd4bf;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    </div>
                    <p class="dash-stat-num"><?= $documents !== null ? htmlspecialchars($documents) : '—' ?></p>
                    <p class="dash-stat-label">Documents</p>
                    <p style="font-size:.7rem;color:rgba(255,255,255,.2);margin-top:.25rem">Uploaded documents</p>
                </div>

                <div class="dash-stat-card fade-up" style="animation-delay:.2s">
                    <div class="dash-stat-icon" style="background:rgba(201,164,75,0.2);color:var(--color-gold);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </div>
                    <p class="dash-stat-num"><?= $opportunities !== null ? htmlspecialchars($opportunities) : '—' ?></p>
                    <p class="dash-stat-label">Opportunities</p>
                    <p style="font-size:.7rem;color:rgba(255,255,255,.2);margin-top:.25rem">Available listings</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <h3 class="dash-section-title fade-up">Quick Actions</h3>
            <div class="dash-actions">
                <a id="dash-link-apply" href="#" class="dash-action-card fade-up" style="animation-delay:.1s">
                    <div class="icon" style="color:var(--color-gold)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                    </div>
                    <p class="title">New Application</p>
                    <p class="desc">Start a new visa or job application</p>
                </a>
                <a id="dash-link-docs" href="#" class="dash-action-card fade-up" style="animation-delay:.15s">
                    <div class="icon" style="color:#60a5fa">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    </div>
                    <p class="title">My Documents</p>
                    <p class="desc">Upload and manage your documents</p>
                </a>
                <a id="dash-link-opps" href="#" class="dash-action-card fade-up" style="animation-delay:.2s">
                    <div class="icon" style="color:#c084fc">
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    </div>
                    <p class="title">Opportunities</p>
                    <p class="desc">Browse job and visa listings</p>
                </a>
            </div>

            <!-- Account Information -->
            <h3 class="dash-section-title fade-up">Account Information</h3>
            <div class="dash-account fade-up" style="margin-bottom:2rem">
                <div style="display:flex;align-items:flex-start;gap:1rem">
                    <div style="padding:.75rem;border-radius:9999px;background:rgba(201,164,75,.2);color:var(--color-gold);flex-shrink:0">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div class="dash-account-grid" style="flex:1">
                        <div>
                            <p class="dash-account-field-label">Name</p>
                            <p class="dash-account-field-value"><?= htmlspecialchars($user['name'] ?? '—') ?></p>
                        </div>
                        <div>
                            <p class="dash-account-field-label">Email</p>
                            <p class="dash-account-field-value"><?= htmlspecialchars($user['email'] ?? '—') ?></p>
                        </div>
                        <div>
                            <p class="dash-account-field-label">Country</p>
                            <p class="dash-account-field-value"><?= htmlspecialchars($user['country'] ?? 'Not set') ?></p>
                        </div>
                        <div>
                            <p class="dash-account-field-label">Member Since</p>
                            <p class="dash-account-field-value">
                                <?php
                                if (!empty($user['created_at'])) {
                                    echo date('F Y', strtotime($user['created_at']));
                                } else {
                                    echo 'Today';
                                }
                                ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem" class="fade-up">
                <h3 class="dash-section-title" style="margin-bottom:0">Recent Activity</h3>
                <?php if ($recentApp): ?>
                <a id="dash-link-apply2" href="#" style="font-size:.75rem;color:var(--color-gold);display:flex;align-items:center;gap:.25rem;font-weight:500">
                    View All
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
                <?php endif; ?>
            </div>

            <?php if ($recentApp):
                $appId = (int)$recentApp['id'];
                $status = $recentApp['status'] ?? 'pending';
                $badgeClass = match($status) {
                    'submitted'    => 'badge-submitted',
                    'approved'     => 'badge-approved',
                    'rejected'     => 'badge-rejected',
                    'under_review' => 'badge-under_review',
                    default        => 'badge-pending',
                };
            ?>
            <a href="<?= BASE ?>/application-detail.php?id=<?= $appId ?>" class="dash-activity fade-up" style="text-decoration:none;display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:1rem 1.25rem;border-radius:0.75rem;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);transition:all .2s;" onmouseover="this.style.background='rgba(255,255,255,.08)'" onmouseout="this.style.background='rgba(255,255,255,.04)'">
                <div>
                    <p class="app-title" style="color:#fff;font-weight:600;font-size:0.9rem;margin-bottom:0.15rem"><?= htmlspecialchars(ucwords(str_replace('_', ' ', $recentApp['sponsorship_type'] ?? ''))) ?> Application</p>
                    <p class="app-date" style="color:rgba(255,255,255,.4);font-size:0.75rem">
                        <?= !empty($recentApp['created_at']) ? date('M j, Y', strtotime($recentApp['created_at'])) : '' ?>
                    </p>
                </div>
                <div style="display:flex;align-items:center;gap:0.75rem">
                    <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars(str_replace('_', ' ', $status)) ?></span>
                    <svg width="14" height="14" fill="none" stroke="rgba(255,255,255,.3)" stroke-width="2" viewBox="0 0 24 24"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </div>
            </a>
            <?php else: ?>
            <div class="dash-empty fade-up">
                <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <p>No recent activity</p>
                <small>Start by submitting your first application</small>
                <a id="dash-link-apply3" href="#" style="display:inline-block;margin-top:1rem;padding:.625rem 1.5rem;border-radius:9999px;background:var(--color-gold);color:var(--color-navy);font-weight:700;font-size:.875rem">Apply Now</a>
            </div>
            <?php endif; ?>
        </div><!-- /portal-content -->
    </main>
</div>

<?php include __DIR__ . '/includes/scripts.php'; ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const BASE = window.LOTOKS_CONFIG?.BASE || '';

    // Resolve internal links
    const links = {
        'dash-logout-btn':  BASE + '/logout.php',
        'dash-link-apply':  BASE + '/apply.php',
        'dash-link-apply2': BASE + '/apply.php',
        'dash-link-apply3': BASE + '/apply.php',
        'dash-link-docs':   BASE + '/documents.php',
        'dash-link-opps':   BASE + '/opportunities.php',
    };
    Object.entries(links).forEach(([id, href]) => {
        const el = document.getElementById(id);
        if (el) el.href = href;
    });

    // Fade-in animation
    const content = document.getElementById('dash-content');
    if (content) {
        requestAnimationFrame(() => {
            content.style.opacity = '1';
            content.style.transform = 'translateY(0)';
        });
    }

    // Sidebar Toggle Logic (uses .portal-sidebar added by sidebar.php)
    const toggle  = document.getElementById('sidebar-toggle');
    const sidebar = document.querySelector('.portal-sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    function openSidebar() {
      if(sidebar) sidebar.classList.add('is-open');
      if(overlay) overlay.classList.add('is-open');
      document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
      if(sidebar) sidebar.classList.remove('is-open');
      if(overlay) overlay.classList.remove('is-open');
      document.body.style.overflow = '';
    }

    if(toggle) toggle.addEventListener('click', openSidebar);
    if(overlay) overlay.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closeSidebar();
    });

    // ── Notification Bell ──
    const bellBtn = document.querySelector('.bell-btn');
    if (bellBtn) {
      bellBtn.addEventListener('click', function (e) {
        e.preventDefault();
        window.location.href = BASE + '/notifications.php';
      });
    }

    // ── Unread Notification Count ──
    (function fetchUnreadCount() {
      const dot = document.querySelector('.bell-dot');
      if (!dot) return;

      const apiUrl = (window.LOTOKS_CONFIG?.API_BASE || BASE + '/api') + '/user/notifications.php?action=get_unread_count';

      fetch(apiUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
        .then(function (r) { return r.json(); })
        .then(function (data) {
          if (data.success && data.count > 0) {
            dot.style.display = '';
          } else {
            dot.style.display = 'none';
          }
        })
        .catch(function () { /* silently ignore */ });
    })();
});
</script>
</body>
</html>
