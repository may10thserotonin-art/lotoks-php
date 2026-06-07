<?php
/**
 * notifications.php
 * User Notifications page — shows recent activity from the activity_log table.
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/db/connect.php';
requireUserAuth('/login.php');

$current_page = 'notifications';
$user = getCurrentUser();

$db = getDb();
$userId = (int)($user['id'] ?? 0);

// Fetch recent activity entries with admin names via LEFT JOIN
$stmt = $db->prepare("
    SELECT a.id, a.action, a.description, a.admin_id, a.created_at, ad.name AS admin_name
    FROM activity_log a
    LEFT JOIN admins ad ON a.admin_id = ad.id
    WHERE a.user_id = ?
    ORDER BY a.created_at DESC
    LIMIT 50
");
$stmt->execute([$userId]);
$activities = $stmt->fetchAll();

$totalCount = count($activities);

/**
 * Determine the date group label for a given datetime string.
 */
function getDateGroup(string $createdAt): string {
    $timestamp = strtotime($createdAt);
    $today     = strtotime('today');
    $yesterday = strtotime('yesterday');

    $dateOnly = strtotime(date('Y-m-d', $timestamp));

    if ($dateOnly === $today) {
        return 'Today';
    }
    if ($dateOnly === $yesterday) {
        return 'Yesterday';
    }
    return date('F j, Y', $timestamp);
}

/**
 * Return a colour hex value for the action dot.
 */
function getActionColor(string $action): string {
    return match (strtolower($action)) {
        'submitted' => '#3b82f6',  // blue
        'approved'  => '#16a34a',  // green
        'rejected'  => '#dc2626',  // red
        'uploaded'  => '#eab308',  // gold
        default     => '#6b7280',  // gray
    };
}

/**
 * Simple pluraliser.
 */
function pluralize(int $count, string $singular, string $plural = null): string {
    return $count === 1 ? $singular : ($plural ?? $singular . 's');
}

// Group activities by date label, preserving chronological order
$grouped   = [];
$dateOrder = [];
foreach ($activities as $act) {
    $group = getDateGroup($act['created_at']);
    if (!isset($grouped[$group])) {
        $grouped[$group] = [];
        $dateOrder[] = $group;
    }
    $grouped[$group][] = $act;
}

$page_title       = 'Notifications — Lotoks';
$page_description = 'Your recent activity and notifications on Lotoks.';
?><!DOCTYPE html>
<html lang="en">
<?php include __DIR__ . '/includes/head.php'; ?>
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
                <h1>Notifications</h1>
                <p>Your recent activity</p>
            </div>
            <div class="portal-topbar-actions">
                <a id="dash-logout-btn" href="#" class="logout-topbar-btn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                    Logout
                </a>
            </div>
        </header>

        <!-- Notifications Content -->
        <div class="portal-content" id="notif-content" style="opacity:0; transform:translateY(16px); transition: opacity .5s ease, transform .5s ease;">

            <!-- Header Row: count + mark-read button -->
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem">
                <div>
                    <h2 style="font-size:1.125rem;font-weight:700;color:#fff;font-family:var(--font-heading);margin:0 0 .25rem">
                        <?= $totalCount ?> <?= pluralize($totalCount, 'notification') ?>
                    </h2>
                    <p style="font-size:.75rem;color:rgba(255,255,255,.4);margin:0">
                        Most recent activity from your account
                    </p>
                </div>
                <?php if ($totalCount > 0): ?>
                <button id="mark-read-btn" style="display:inline-flex;align-items:center;gap:.375rem;padding:.5rem 1rem;border-radius:9999px;border:1px solid rgba(201,164,75,.3);background:rgba(201,164,75,.1);color:var(--color-gold);font-size:.75rem;font-weight:600;cursor:pointer;transition:all .2s;white-space:nowrap" onmouseover="this.style.background='rgba(201,164,75,.2)'" onmouseout="this.style.background='rgba(201,164,75,.1)'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Mark all as read
                </button>
                <?php endif; ?>
            </div>

            <?php if ($totalCount > 0): ?>

                <!-- Grouped Activity List -->
                <?php foreach ($dateOrder as $groupLabel): $items = $grouped[$groupLabel]; ?>
                <div style="margin-bottom:1.75rem">
                    <h3 style="font-size:.6875rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.3);margin-bottom:.75rem;padding-left:.25rem">
                        <?= htmlspecialchars($groupLabel) ?>
                    </h3>
                    <div style="display:flex;flex-direction:column;gap:.5rem">
                        <?php foreach ($items as $act):
                            $color   = getActionColor($act['action']);
                            $time    = date('g:i A', strtotime($act['created_at']));
                            $desc    = htmlspecialchars($act['description'] ?? '');
                            $admin   = $act['admin_name'] ?? null;
                        ?>
                        <div style="display:flex;align-items:flex-start;gap:.875rem;padding:1rem 1.25rem;border-radius:.75rem;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);transition:all .2s" onmouseover="this.style.background='rgba(255,255,255,.08)'" onmouseout="this.style.background='rgba(255,255,255,.04)'">
                            <!-- Action Dot -->
                            <div style="flex-shrink:0;width:10px;height:10px;border-radius:50%;background:<?= $color ?>;margin-top:.375rem;box-shadow:0 0 6px <?= $color ?>80"></div>

                            <!-- Content -->
                            <div style="flex:1;min-width:0">
                                <p style="color:#fff;font-size:.875rem;font-weight:500;line-height:1.5;margin:0 0 .125rem">
                                    <?= $desc ?>
                                </p>
                                <div style="display:flex;align-items:center;gap:.5rem;flex-wrap:wrap">
                                    <span style="font-size:.6875rem;color:rgba(255,255,255,.35)"><?= $time ?></span>
                                    <?php if ($admin): ?>
                                    <span style="font-size:.6875rem;color:rgba(255,255,255,.25)">·</span>
                                    <span style="font-size:.6875rem;color:rgba(201,164,75,.6);display:flex;align-items:center;gap:.25rem">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                        by <?= htmlspecialchars($admin) ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Action badge -->
                            <span style="flex-shrink:0;display:inline-block;padding:.125rem .5rem;border-radius:9999px;font-size:.625rem;font-weight:600;text-transform:capitalize;background:<?= $color ?>20;color:<?= $color ?>;border:1px solid <?= $color ?>40">
                                <?= htmlspecialchars(str_replace('_', ' ', $act['action'])) ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>

            <?php else: ?>
                <!-- Empty State -->
                <div style="text-align:center;padding:3rem 2rem;border-radius:.75rem;background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08)">
                    <div style="display:flex;align-items:center;justify-content:center;width:56px;height:56px;border-radius:9999px;background:rgba(255,255,255,.05);margin:0 auto 1rem">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,.25)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    </div>
                    <p style="color:rgba(255,255,255,.4);font-size:.9375rem;font-weight:500;margin:0 0 .25rem">No notifications yet</p>
                    <p style="color:rgba(255,255,255,.2);font-size:.8125rem;margin:0 0 1.25rem">You'll see activity here once you start applying</p>
                    <a href="<?= BASE ?>/dashboard.php" style="display:inline-block;padding:.625rem 1.5rem;border-radius:9999px;background:var(--color-gold);color:var(--color-navy);font-weight:700;font-size:.875rem;text-decoration:none">Go to Dashboard</a>
                </div>
            <?php endif; ?>

            <!-- Back to Dashboard Link -->
            <div style="margin-top:2rem;text-align:center;padding-bottom:5rem">
                <a href="<?= BASE ?>/dashboard.php" style="display:inline-flex;align-items:center;gap:.5rem;color:rgba(255,255,255,.4);font-size:.8125rem;font-weight:500;text-decoration:none;transition:color .2s" onmouseover="this.style.color='rgba(255,255,255,.7)'" onmouseout="this.style.color='rgba(255,255,255,.4)'">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Back to Dashboard
                </a>
            </div>
        </div><!-- /portal-content -->
    </main>
</div>

<?php include __DIR__ . '/includes/scripts.php'; ?>
<script>
(function () {
    'use strict';

    var BASE = window.LOTOKS_CONFIG?.BASE || '<?= BASE ?>';

    // ── Resolve logout link ──
    var logoutBtn = document.getElementById('dash-logout-btn');
    if (logoutBtn) logoutBtn.href = BASE + '/logout.php';

    // ── Fade-in animation ──
    var content = document.getElementById('notif-content');
    if (content) {
        requestAnimationFrame(function () {
            content.style.opacity = '1';
            content.style.transform = 'translateY(0)';
        });
    }

    // ── Toast helper ──
    function showToast(text, type) {
        type = type || 'info';
        var container = document.getElementById('toast-container');
        if (!container) return;
        var el = document.createElement('div');
        el.className = 'toast toast-' + type;
        el.textContent = text;
        container.appendChild(el);
        setTimeout(function () {
            el.classList.add('fade-out');
            setTimeout(function () { el.remove(); }, 300);
        }, 2500);
    }

    // ── Mark all as read button ──
    var markBtn = document.getElementById('mark-read-btn');
    if (markBtn) {
        markBtn.addEventListener('click', function () {
            var apiUrl = (window.LOTOKS_CONFIG?.API_BASE || BASE + '/api') + '/user/notifications.php?action=mark_all_read';

            markBtn.disabled = true;
            markBtn.textContent = 'Marking…';

            fetch(apiUrl, {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin',
            })
            .then(function (r) { return r.json(); })
            .then(function (data) {
                if (data.success) {
                    showToast('All notifications marked as read', 'success');
                    markBtn.textContent = '✓ Done';
                    markBtn.style.opacity = '0.5';
                    markBtn.style.pointerEvents = 'none';
                } else {
                    showToast('Failed to mark as read', 'error');
                    markBtn.disabled = false;
                    markBtn.textContent = 'Mark all as read';
                }
            })
            .catch(function () {
                showToast('Network error', 'error');
                markBtn.disabled = false;
                markBtn.textContent = 'Mark all as read';
            });
        });
    }

    // ── Sidebar Toggle Logic (mirrors dashboard pattern) ──
    var toggle  = document.getElementById('sidebar-toggle');
    var sidebar = document.querySelector('.portal-sidebar');
    var overlay = document.getElementById('sidebar-overlay');

    function openSidebar() {
        if (sidebar) sidebar.classList.add('is-open');
        if (overlay) overlay.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        if (sidebar) sidebar.classList.remove('is-open');
        if (overlay) overlay.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    if (toggle) toggle.addEventListener('click', openSidebar);
    if (overlay) overlay.addEventListener('click', closeSidebar);

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeSidebar();
    });
})();
</script>
</body>
</html>
