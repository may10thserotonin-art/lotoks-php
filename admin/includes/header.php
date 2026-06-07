<?php
/**
 * admin/includes/header.php
 */
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/db/connect.php';

// Force admin auth
require_admin_auth();
$admin = get_admin();

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'Lotoks Admin' ?></title>

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="<?= BASE ?>/public/favicon.svg" />
  <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE ?>/public/logo.png" />
  <link rel="apple-touch-icon" href="<?= BASE ?>/public/logo.png" />

  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= BASE ?>/admin/assets/css/admin.css?v=<?= filemtime(__DIR__ . '/../assets/css/admin.css') ?>">
</head>
<body>

<!-- Sidebar Overlay for Mobile -->
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<!-- Sidebar -->
<aside class="admin-sidebar">
  <div class="sidebar-header">
    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
    Lotoks<span>.</span>
  </div>
  <nav class="sidebar-nav">
    <a href="index.php" class="nav-link <?= $current_page === 'index.php' ? 'active' : '' ?>">
      <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      Dashboard
    </a>
    <a href="applications.php" class="nav-link <?= $current_page === 'applications.php' ? 'active' : '' ?>">
      <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      Applications
    </a>
    <a href="listings.php" class="nav-link <?= $current_page === 'listings.php' ? 'active' : '' ?>">
      <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
      Listings
    </a>
    <?php if (is_super_admin()): ?>
    <a href="users.php" class="nav-link <?= $current_page === 'users.php' ? 'active' : '' ?>">
      <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
      Users
    </a>
    <a href="staff.php" class="nav-link <?= $current_page === 'staff.php' ? 'active' : '' ?>">
      <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
      Staff
    </a>
    <?php endif; ?>
    <a href="requirements.php" class="nav-link <?= $current_page === 'requirements.php' ? 'active' : '' ?>">
      <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
      Requirements
    </a>
    <?php if (is_super_admin()): ?>
    <a href="newsletter.php" class="nav-link <?= $current_page === 'newsletter.php' ? 'active' : '' ?>">
      <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
      Newsletter
    </a>
    <a href="security.php" class="nav-link <?= $current_page === 'security.php' ? 'active' : '' ?>">
      <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      Security
    </a>
    <?php endif; ?>
    <a href="logs.php" class="nav-link <?= $current_page === 'logs.php' ? 'active' : '' ?>">
      <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
      Activity Log
    </a>
    <a href="settings.php" class="nav-link <?= $current_page === 'settings.php' ? 'active' : '' ?>">
      <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
      Settings
    </a>
  </nav>
  <div style="padding:1.5rem;border-top:1px solid rgba(255,255,255,0.1)">
    <a href="logout.php" class="nav-link" style="color:#f87171">
      <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
      Sign Out
    </a>
  </div>
</aside>

<main class="admin-main">
  <div class="admin-topbar">
    <div style="display:flex;align-items:center;gap:1rem;">
      <button class="menu-toggle" id="sidebar-toggle">
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
      </button>
      <h1 class="admin-title"><?= isset($page_title) ? htmlspecialchars($page_title) : 'Dashboard' ?></h1>
    </div>
    <div class="admin-profile">
      <div style="text-align:right">
        <div style="font-weight:700;font-size:0.875rem"><?= htmlspecialchars($admin['name'] ?? 'Admin') ?></div>
        <div style="font-size:0.75rem;color:var(--text-light);text-transform:uppercase"><?= htmlspecialchars(str_replace('_',' ',$admin['role'])) ?></div>
      </div>
      <div style="width:2.5rem;height:2.5rem;background:var(--gold);color:var(--navy);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:800;">
        <?= strtoupper(substr($admin['name'] ?? 'A', 0, 1)) ?>
      </div>
    </div>
  </div>
  
  <?php
  // Render flashes
  $flashes = get_flash();
  foreach($flashes as $f) {
      $color = $f['type'] === 'error' ? 'var(--danger)' : 'var(--success)';
      $bg = $f['type'] === 'error' ? 'var(--danger-bg)' : 'var(--success-bg)';
      echo "<div style='background:{$bg};color:{$color};padding:1rem;border-radius:0.5rem;margin-bottom:1.5rem;font-weight:600;font-size:0.875rem;'>".htmlspecialchars($f['message'])."</div>";
  }
  ?>
