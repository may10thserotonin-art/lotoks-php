<?php
/**
 * Lotoks — Admin Applications Queue (admin/applications.php)
 * Enhanced with filters, search, inline actions, and AJAX detail modal.
 */
$page_title = 'Applications';
require_once __DIR__ . '/includes/header.php';

$db = getDb();

// ── Filters ──────────────────────────────────────────────────
$statusFilter = $_GET['status'] ?? '';
$searchQuery  = trim($_GET['q'] ?? '');

$validStatuses = ['submitted', 'under_review', 'more_info', 'approved', 'rejected'];
if ($statusFilter && !in_array($statusFilter, $validStatuses, true)) {
    $statusFilter = '';
}

// ── Counts per status (for tab badges) ────────────────────────
$counts = [];
$countStmt = $db->query("SELECT status, COUNT(*) as cnt FROM applications GROUP BY status");
foreach ($countStmt as $row) {
    $counts[$row['status']] = (int)$row['cnt'];
}
$totalAll = array_sum($counts);

// ── Build query ────────────────────────────────────────────────
$where  = [];
$params = [];

if ($statusFilter) {
    $where[]  = 'a.status = ?';
    $params[] = $statusFilter;
}

if ($searchQuery) {
    $where[]  = '(a.applicant_name LIKE ? OR a.email LIKE ?)';
    $params[] = "%{$searchQuery}%";
    $params[] = "%{$searchQuery}%";
}

$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$applications = $db->prepare("
    SELECT a.id, a.applicant_name, a.email, a.sponsorship_type, a.status, a.created_at, a.updated_at
    FROM applications a
    {$whereSQL}
    ORDER BY a.created_at DESC
");
$applications->execute($params);
$apps = $applications->fetchAll();

$resultCount = count($apps);

// ── Tab helper ────────────────────────────────────────────────
function tabClass(string $tab, string $current): string {
    return ($tab === $current) ? 'tab-active' : '';
}
function tabUrl(?string $status, string $search): string {
    $params = [];
    if ($status)  $params['status'] = $status;
    if ($search)  $params['q']      = $search;
    return '?' . http_build_query($params);
}
?>

<style>
/* ── Filter Tabs ─────────────────────────────────── */
.filter-tabs {
  display: flex;
  flex-wrap: wrap;
  gap: 0.375rem;
  margin-bottom: 1.5rem;
  padding: 0 0.25rem;
}
.filter-tab {
  padding: 0.5rem 1rem;
  border-radius: 9999px;
  font-size: 0.8rem;
  font-weight: 600;
  text-decoration: none;
  color: var(--text-light);
  background: transparent;
  border: 1px solid transparent;
  transition: all 0.2s;
  white-space: nowrap;
}
.filter-tab:hover {
  background: rgba(11,29,58,0.04);
  color: var(--navy);
}
.filter-tab.tab-active {
  background: var(--navy);
  color: white;
  border-color: var(--navy);
}
.tab-count {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 1.25rem;
  height: 1.25rem;
  border-radius: 9999px;
  font-size: 0.65rem;
  font-weight: 700;
  margin-left: 0.35rem;
  padding: 0 0.35rem;
}
.tab-active .tab-count {
  background: rgba(255,255,255,0.2);
  color: white;
}
.filter-tab:not(.tab-active) .tab-count {
  background: var(--bg-color);
  color: var(--text-light);
}

/* ── Search Bar ──────────────────────────────────── */
.search-bar {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  margin-bottom: 1.5rem;
}
.search-bar input {
  flex: 1;
  padding: 0.65rem 1rem;
  border: 1px solid var(--border);
  border-radius: 0.5rem;
  font-family: inherit;
  font-size: 0.875rem;
  background: white;
  transition: border-color 0.2s;
}
.search-bar input:focus {
  outline: none;
  border-color: var(--gold);
  box-shadow: 0 0 0 3px rgba(201,164,75,0.15);
}

/* ── Inline Action Buttons ───────────────────────── */
.action-cell {
  display: flex;
  gap: 0.375rem;
  align-items: center;
}
.action-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.25rem;
  padding: 0.35rem 0.65rem;
  border-radius: 0.375rem;
  font-size: 0.7rem;
  font-weight: 600;
  cursor: pointer;
  border: none;
  text-decoration: none;
  transition: all 0.15s;
}
.action-view {
  background: rgba(59,130,246,0.1);
  color: #2563eb;
}
.action-view:hover {
  background: #2563eb;
  color: white;
}
.action-delete {
  background: rgba(220,38,38,0.1);
  color: var(--danger);
}
.action-delete:hover {
  background: var(--danger);
  color: white;
}

/* ── Status Badge Enhancements ───────────────────── */
.badge-submitted   { background: rgba(59,130,246,0.1); color: #2563eb; }
.badge-under_review,
.badge-more_info   { background: rgba(234,179,8,0.1); color: #ca8a04; }
.badge-approved    { background: var(--success-bg); color: var(--success); }
.badge-rejected    { background: var(--danger-bg); color: var(--danger); }

/* ── Row hover & clickability ────────────────────── */
tr.clickable-row {
  cursor: pointer;
}
tr.clickable-row:hover td {
  background: rgba(11,29,58,0.02);
}

/* ── Empty state ─────────────────────────────────── */
.empty-state {
  padding: 3rem 2rem;
  text-align: center;
  color: var(--text-light);
}
.empty-state svg {
  margin-bottom: 1rem;
  opacity: 0.3;
}
.empty-state h3 {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--navy);
  margin-bottom: 0.25rem;
}
.empty-state p {
  font-size: 0.85rem;
}

/* ── Quick action buttons in modal ───────────────── */
.modal-actions {
  display: flex;
  gap: 0.75rem;
  justify-content: flex-end;
  flex-wrap: wrap;
}
@media (max-width: 600px) {
  .modal-actions {
    flex-direction: column;
  }
  .modal-actions .btn {
    width: 100%;
    justify-content: center;
  }
}
</style>

<!-- ── Filter Tabs ─────────────────────────────── -->
<div class="filter-tabs">
  <a href="<?= tabUrl(null, $searchQuery) ?>" class="filter-tab <?= tabClass('', $statusFilter) ?>">
    All <span class="tab-count"><?= $totalAll ?></span>
  </a>
  <?php foreach ($validStatuses as $s): ?>
    <?php $cnt = $counts[$s] ?? 0; ?>
    <a href="<?= tabUrl($s, $searchQuery) ?>" class="filter-tab <?= tabClass($s, $statusFilter) ?>">
      <?= str_replace('_', ' ', ucfirst($s)) ?> <span class="tab-count"><?= $cnt ?></span>
    </a>
  <?php endforeach; ?>
</div>

<!-- ── Search Bar ───────────────────────────────── -->
<form method="GET" class="search-bar">
  <?php if ($statusFilter): ?>
    <input type="hidden" name="status" value="<?= htmlspecialchars($statusFilter) ?>">
  <?php endif; ?>
  <input type="text" name="q" placeholder="Search by name or email…" value="<?= htmlspecialchars($searchQuery) ?>">
  <button type="submit" class="btn btn-outline" style="padding:0.65rem 1.25rem;">Search</button>
  <?php if ($searchQuery): ?>
    <a href="<?= tabUrl($statusFilter, '') ?>" class="btn btn-outline" style="padding:0.65rem 1.25rem;">Clear</a>
  <?php endif; ?>
</form>

<!-- ── Results ──────────────────────────────────── -->
<div class="card">
  <div class="card-header">
    <h2>
      <?php if ($statusFilter): ?>
        <?= str_replace('_', ' ', ucfirst($statusFilter)) ?> Applications
      <?php elseif ($searchQuery): ?>
        Search Results
      <?php else: ?>
        All Applications
      <?php endif; ?>
    </h2>
    <span style="font-size:0.8rem;color:var(--text-light)">
      <?= number_format($resultCount) ?> application<?= $resultCount !== 1 ? 's' : '' ?>
    </span>
  </div>

  <?php if (empty($apps)): ?>
    <div class="empty-state">
      <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
        <polyline points="14 2 14 8 20 8"/>
        <line x1="12" y1="18" x2="12" y2="12"/>
        <line x1="9" y1="15" x2="15" y2="15"/>
      </svg>
      <h3>No applications found</h3>
      <p>
        <?php if ($statusFilter): ?>
          No applications with status "<?= str_replace('_', ' ', $statusFilter) ?>" yet.
        <?php elseif ($searchQuery): ?>
          No applications match your search.
        <?php else: ?>
          No applications have been submitted yet.
        <?php endif; ?>
      </p>
    </div>
  <?php else: ?>
  <div class="table-responsive">
    <table>
      <thead>
        <tr>
          <th>#</th>
          <th>Applicant</th>
          <th>Type</th>
          <th>Status</th>
          <th>Submitted</th>
          <th style="text-align:right">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($apps as $app):
          $statusClass = 'badge-' . str_replace('_', '-', $app['status']); ?>
        <tr class="clickable-row" onclick="viewApplication(<?= (int)$app['id'] ?>)">
          <td style="color:var(--text-light);font-size:0.7rem">#<?= htmlspecialchars($app['id']) ?></td>
          <td>
            <div style="font-weight:600"><?= htmlspecialchars($app['applicant_name']) ?></div>
            <div style="font-size:0.75rem;color:var(--text-light)"><?= htmlspecialchars($app['email']) ?></div>
          </td>
          <td style="text-transform:capitalize;font-size:0.8rem"><?= htmlspecialchars($app['sponsorship_type']) ?></td>
          <td>
            <span class="badge <?= $statusClass ?>">
              <?= str_replace('_', ' ', $app['status']) ?>
            </span>
          </td>
          <td style="color:var(--text-light);font-size:0.75rem;white-space:nowrap">
            <?= date('M j, Y', strtotime($app['created_at'])) ?>
            <span style="display:block;font-size:0.65rem"><?= date('g:i A', strtotime($app['created_at'])) ?></span>
          </td>
          <td style="text-align:right">
            <div class="action-cell" style="justify-content:flex-end">
              <button type="button" class="action-btn action-view" onclick="event.stopPropagation(); viewApplication(<?= (int)$app['id'] ?>)">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                View
              </button>
              <button type="button" class="action-btn action-delete" onclick="event.stopPropagation(); deleteApplication(<?= (int)$app['id'] ?>)">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                Delete
              </button>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
