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
    ['href' => $_BASE . '/opportunities.php','key' => 'opportunities','icon' => 'search',      'label' => 'Opportunities'],
];

function sidebar_icon(string $name): string {
    $icons = [
        'grid' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>',
        'plus-circle' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>',
        'folder' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg>',
        'search' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>',
        'log-out' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>',
        'menu' => '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/></svg>',
        'x' => '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>',
    ];
    return $icons[$name] ?? '';
}
?>

<!-- Desktop Sidebar -->
<aside class="sidebar hidden lg:flex">
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
        </a>
        <?php endforeach; ?>
        <a href="<?= $_BASE ?>/logout.php" class="sidebar-link logout-link">
            <?= sidebar_icon('log-out') ?>
            <span>Logout</span>
        </a>
    </nav>
</aside>

<!-- Mobile Menu Toggle -->
<button id="mobile-menu-toggle" class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-full bg-white shadow-lg text-slate-700" aria-label="Open menu" onclick="openMobileMenu()">
    <?= sidebar_icon('menu') ?>
</button>

<!-- Mobile Menu Overlay -->
<div id="mobile-menu-overlay" class="lg:hidden fixed inset-0 bg-black/50 z-50 hidden" onclick="closeMobileMenu()"></div>

<!-- Mobile Drawer -->
<div id="mobile-menu-drawer" class="lg:hidden fixed top-0 left-0 h-full w-60 z-50 sidebar" style="transform: translateX(-100%); transition: transform 0.3s ease;">
    <div class="flex justify-between items-center mb-8 px-2">
        <a href="<?= $_BASE ?>/index.php" class="sidebar-brand">Lotoks<span>.</span></a>
        <button onclick="closeMobileMenu()" class="p-1 rounded-full hover:bg-white/10 text-white/60">
            <?= sidebar_icon('x') ?>
        </button>
    </div>
    <nav class="sidebar-nav">
        <?php foreach ($nav_links as $link): ?>
        <a href="<?= $link['href'] ?>" class="sidebar-link <?= $current_page === $link['key'] ? 'active' : '' ?>" onclick="closeMobileMenu()">
            <?= sidebar_icon($link['icon']) ?>
            <span><?= $link['label'] ?></span>
        </a>
        <?php endforeach; ?>
        <a href="<?= $_BASE ?>/logout.php" class="sidebar-link logout-link">
            <?= sidebar_icon('log-out') ?>
            <span>Logout</span>
        </a>
    </nav>
</div>

<!-- Mobile Tab Bar -->
<div class="mobile-tab-bar md:hidden">
    <?php
    $tabs = [
        ['href' => $_BASE . '/dashboard.php',    'key' => 'dashboard',    'icon' => 'grid',        'label' => 'Home'],
        ['href' => $_BASE . '/apply.php',         'key' => 'apply',        'icon' => 'plus-circle', 'label' => 'Apply'],
        ['href' => $_BASE . '/documents.php',     'key' => 'documents',    'icon' => 'folder',      'label' => 'Files'],
        ['href' => $_BASE . '/opportunities.php', 'key' => 'opportunities','icon' => 'search',      'label' => 'Jobs'],
    ];
    foreach ($tabs as $tab):
        $active = $current_page === $tab['key'];
    ?>
    <a href="<?= $tab['href'] ?>" class="mobile-tab-item <?= $active ? 'active' : '' ?>">
        <div class="mobile-tab-icon <?= $active ? 'active' : '' ?>">
            <?= sidebar_icon($tab['icon']) ?>
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
