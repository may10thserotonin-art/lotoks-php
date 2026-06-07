<?php
// includes/sidebar.php — User sidebar navigation
// Requires: $current_page variable set before including (e.g. 'dashboard', 'apply', 'documents', 'opportunities')
// Requires: BASE constant defined via includes/config.php
$current_page = $current_page ?? '';
$_BASE = defined('BASE') ? BASE : '';

$nav_links = [
    ['href' => $_BASE . '/dashboard.php',    'key' => 'dashboard',    'icon' => 'grid',        'label' => 'Dashboard'],
    ['href' => $_BASE . '/apply.php',        'key' => 'apply',        'icon' => 'plus-circle', 'label' => 'Apply New'],
    ['href' => $_BASE . '/documents.php',    'key' => 'documents',    'icon' => 'folder',      'label' => 'My Documents'],
    ['href' => $_BASE . '/profile.php',      'key' => 'profile',     'icon' => 'user',        'label' => 'My Profile'],
    ['href' => $_BASE . '/settings.php',     'key' => 'settings',    'icon' => 'settings',    'label' => 'Settings'],
    ['href' => $_BASE . '/notifications.php','key' => 'notifications','icon' => 'bell',       'label' => 'Notifications'],
    ['href' => $_BASE . '/opportunities.php','key' => 'opportunities','icon' => 'search',      'label' => 'Opportunities'],
];

function sidebar_icon(string $name): string {
    $icons = [
        'grid' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>',
        'plus-circle' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>',
        'folder' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>',
        'search' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
        'user' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>',
        'bell' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>',
        'settings' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>',
        'log-out' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>',
        'menu' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>',
        'x' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
    ];
    return $icons[$name] ?? '';
}
?>

<!-- Desktop Sidebar -->
<aside class="portal-sidebar sidebar">
    <div class="sidebar-logo">
        <a href="<?= $_BASE ?>/index.php" class="sidebar-brand">Lotoks<span>.</span></a>
    </div>
    <nav class="sidebar-nav">
        <?php foreach ($nav_links as $link): ?>
        <a href="<?= $link['href'] ?>" class="sidebar-link <?= $current_page === $link['key'] ? 'active' : '' ?>">
            <?php if ($current_page === $link['key']): ?>
            <div class="sidebar-active-bar"></div>
            <?php endif; ?>
            <?= sidebar_icon($link['icon']) ?>
            <span><?= $link['label'] ?></span>
            <?php if ($link['key'] === 'notifications'): ?>
            <span class="notif-badge" id="sidebar-notif-count" style="display:none">0</span>
            <?php endif; ?>
        </a>
        <?php endforeach; ?>
        <a href="<?= $_BASE ?>/logout.php" class="sidebar-link logout-link">
            <?= sidebar_icon('log-out') ?>
            <span>Logout</span>
        </a>
    </nav>
</aside>

<!-- Mobile Menu Toggle -->
<button id="mobile-menu-toggle" class="mobile-menu-toggle" aria-label="Open menu" onclick="openMobileMenu()">
    <?= sidebar_icon('menu') ?>
</button>

<!-- Mobile Menu Overlay -->
<div id="mobile-menu-overlay" class="mobile-menu-overlay hidden" onclick="closeMobileMenu()"></div>

<!-- Mobile Drawer -->
<div id="mobile-menu-drawer" class="mobile-menu-drawer sidebar">
    <div class="mobile-menu-drawer-header">
        <a href="<?= $_BASE ?>/index.php" class="sidebar-brand">Lotoks<span>.</span></a>
        <button onclick="closeMobileMenu()" class="mobile-menu-close" aria-label="Close menu">
            <?= sidebar_icon('x') ?>
        </button>
    </div>
    <nav class="sidebar-nav">
        <?php foreach ($nav_links as $link): ?>
        <a href="<?= $link['href'] ?>" class="sidebar-link <?= $current_page === $link['key'] ? 'active' : '' ?>" onclick="closeMobileMenu()">
            <?= sidebar_icon($link['icon']) ?>
            <span><?= $link['label'] ?></span>
            <?php if ($link['key'] === 'notifications'): ?>
            <span class="notif-badge" id="drawer-notif-count" style="display:none">0</span>
            <?php endif; ?>
        </a>
        <?php endforeach; ?>
        <a href="<?= $_BASE ?>/logout.php" class="sidebar-link logout-link">
            <?= sidebar_icon('log-out') ?>
            <span>Logout</span>
        </a>
    </nav>
</div>

<!-- Mobile Tab Bar -->
<div class="mobile-tab-bar">
    <?php
    $tabs = [
        ['href' => $_BASE . '/dashboard.php',    'key' => 'dashboard',    'icon' => 'grid',        'label' => 'Home'],
        ['href' => $_BASE . '/apply.php',         'key' => 'apply',        'icon' => 'plus-circle', 'label' => 'Apply'],
        ['href' => $_BASE . '/documents.php',     'key' => 'documents',    'icon' => 'folder',      'label' => 'Files'],
        ['href' => $_BASE . '/notifications.php','key' => 'notifications','icon' => 'bell',       'label' => 'Alerts'],
        ['href' => $_BASE . '/profile.php',      'key' => 'profile',     'icon' => 'user',        'label' => 'Profile'],
        ['href' => $_BASE . '/opportunities.php', 'key' => 'opportunities','icon' => 'search',      'label' => 'Jobs'],
    ];
    foreach ($tabs as $tab):
        $active = $current_page === $tab['key'];
    ?>
    <a href="<?= $tab['href'] ?>" class="mobile-tab-item <?= $active ? 'active' : '' ?>">
        <div class="mobile-tab-icon <?= $active ? 'active' : '' ?>">
            <?= sidebar_icon($tab['icon']) ?>
            <?php if ($tab['key'] === 'notifications'): ?>
            <span class="notif-badge tab-badge" id="tab-notif-count" style="display:none">0</span>
            <?php endif; ?>
        </div>
        <span><?= $tab['label'] ?></span>
    </a>
    <?php endforeach; ?>
</div>

<script>
function openMobileMenu() {
    document.getElementById('mobile-menu-overlay').classList.remove('hidden');
    document.getElementById('mobile-menu-drawer').style.transform = 'translateX(0)';
    document.body.style.overflow = 'hidden';
}
function closeMobileMenu() {
    document.getElementById('mobile-menu-overlay').classList.add('hidden');
    document.getElementById('mobile-menu-drawer').style.transform = 'translateX(-100%)';
    document.body.style.overflow = '';
}
</script>
