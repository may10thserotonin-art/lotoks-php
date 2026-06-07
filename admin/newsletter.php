<?php
/**
 * Lotoks — Admin Newsletter Subscribers
 *
 * Lists all newsletter subscribers with status filtering.
 * Requires admin authentication.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../db/connect.php';

require_admin_auth();

$page_title = 'Newsletter Subscribers | Lotoks Admin';
$page_heading = 'Newsletter Subscribers';

// ── Filter ──────────────────────────────────────────────────────────
$statusFilter = $_GET['status'] ?? 'all';
$allowedFilters = ['all', 'active', 'pending', 'unsubscribed'];
if (!in_array($statusFilter, $allowedFilters)) {
    $statusFilter = 'all';
}

// ── Fetch subscribers ───────────────────────────────────────────────
$db = getDb();
$sql = "SELECT id, email, status, confirmed_at, created_at, updated_at FROM newsletter_subscribers";
$params = [];

if ($statusFilter !== 'all') {
    $sql .= " WHERE status = :status";
    $params[':status'] = $statusFilter;
}
$sql .= " ORDER BY created_at DESC";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$subscribers = $stmt->fetchAll();

// ── Counts for filter tabs ──────────────────────────────────────────
$counts = [];
$countStmt = $db->query("SELECT status, COUNT(*) as cnt FROM newsletter_subscribers GROUP BY status");
while ($row = $countStmt->fetch()) {
    $counts[$row['status']] = $row['cnt'];
}
$counts['all'] = array_sum($counts);
?>
<?php require __DIR__ . '/includes/header.php'; ?>

<!-- Page Header -->
<div class="header-card">
  <div class="header-content">
    <div>
      <h1 class="page-title">Newsletter Subscribers</h1>
      <p class="page-subtitle">Manage email subscribers and view subscription status</p>
    </div>
    <div class="header-actions">
      <a href="<?= BASE ?>/admin/export-subscribers.php?status=<?= urlencode($statusFilter) ?>" class="btn btn-sm btn-outline">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:0.375rem;">
          <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
          <polyline points="7 10 12 15 17 10"/>
          <line x1="12" y1="15" x2="12" y2="3"/>
        </svg>
        Export CSV
      </a>
    </div>
  </div>
</div>

<!-- Status Filter Tabs -->
<div class="filter-tabs">
  <a href="?status=all"        class="filter-tab <?= $statusFilter === 'all' ? 'active' : '' ?>">All (<?= (int)($counts['all'] ?? 0) ?>)</a>
  <a href="?status=active"     class="filter-tab <?= $statusFilter === 'active' ? 'active' : '' ?>">Active (<?= (int)($counts['active'] ?? 0) ?>)</a>
  <a href="?status=pending"    class="filter-tab <?= $statusFilter === 'pending' ? 'active' : '' ?>">Pending (<?= (int)($counts['pending'] ?? 0) ?>)</a>
  <a href="?status=unsubscribed" class="filter-tab <?= $statusFilter === 'unsubscribed' ? 'active' : '' ?>">Unsubscribed (<?= (int)($counts['unsubscribed'] ?? 0) ?>)</a>
</div>

<!-- Subscribers Table -->
<div class="card">
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>Email</th>
          <th>Status</th>
          <th>Confirmed</th>
          <th>Subscribed</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($subscribers)): ?>
          <tr>
            <td colspan="4" style="text-align:center; padding:2rem; color:#9ca3af;">No subscribers found.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($subscribers as $sub): ?>
            <tr>
              <td>
                <a href="mailto:<?= htmlspecialchars($sub['email']) ?>" style="color:var(--gold);text-decoration:none;">
                  <?= htmlspecialchars($sub['email']) ?>
                </a>
              </td>
              <td>
                <?php
                  $badgeClass = match($sub['status']) {
                    'active'      => 'badge-success',
                    'pending'     => 'badge-warning',
                    'unsubscribed' => 'badge-danger',
                    default       => 'badge-secondary',
                  };
                ?>
                <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars(ucfirst($sub['status'])) ?></span>
              </td>
              <td>
                <?= $sub['confirmed_at'] ? date('M j, Y', strtotime($sub['confirmed_at'])) : '—' ?>
              </td>
              <td>
                <?= date('M j, Y', strtotime($sub['created_at'])) ?>
              </td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- Info Card -->
<div class="card" style="margin-top:1.5rem; background:#f8f9fc; border:1px solid #e2e8f0;">
  <div class="newsletter-info" style="display:flex;align-items:flex-start;gap:0.75rem;">
    <svg width="20" height="20" fill="none" stroke="#6b7280" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:0.125rem;">
      <circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/>
    </svg>
    <div style="font-size:0.85rem;color:#6b7280;line-height:1.5;">
      <strong>About Newsletter Subscribers</strong><br>
      Subscribers are collected via the newsletter signup form in the website footer.
      New subscribers must confirm their email (double opt-in) before being marked as <span class="badge badge-success">Active</span>.
      Pending subscribers have not yet confirmed. Export data for use with your email marketing platform.
    </div>
  </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
