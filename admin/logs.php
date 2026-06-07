<?php
/**
 * Lotoks — Admin Activity Log (admin/logs.php)
 * Enhanced with action type badges, filters, and responsive layout.
 */
$page_title = 'Activity Log';
require_once __DIR__ . '/includes/header.php';

$db = getDb();

// ── Filters ──────────────────────────────────────────────────
$actionFilter = $_GET['action'] ?? '';
$searchLog    = trim($_GET['q'] ?? '');
$page         = max(1, (int)($_GET['p'] ?? 1));
$limit        = 50;
$offset       = ($page - 1) * $limit;

// ── Build WHERE clause ───────────────────────────────────────
$where  = [];
$params = [];

if ($actionFilter) {
    $where[]  = 'a.action = ?';
    $params[] = $actionFilter;
}

if ($searchLog) {
    $where[]  = 'a.description LIKE ?';
    $params[] = "%{$searchLog}%";
}

$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// ── Fetch distinct action types for filter dropdown ──────────
$actionTypes = $db->query("SELECT DISTINCT action FROM activity_log ORDER BY action")->fetchAll(PDO::FETCH_COLUMN);

// ── Count & paginate ─────────────────────────────────────────
$total     = $db->prepare("SELECT COUNT(*) FROM activity_log a {$whereSQL}");
$total->execute($params);
$totalCount = (int)$total->fetchColumn();
$totalPages = max(1, (int)ceil($totalCount / $limit));

// ── Fetch logs ───────────────────────────────────────────────
$logs = $db->prepare("
    SELECT a.*, u.name as user_name, ad.name as admin_name 
    FROM activity_log a 
    LEFT JOIN users u ON a.user_id = u.id 
    LEFT JOIN admins ad ON a.admin_id = ad.id 
    {$whereSQL}
    ORDER BY a.created_at DESC 
    LIMIT {$limit} OFFSET {$offset}
");
$logs->execute($params);
$logEntries = $logs->fetchAll();

// ── Action badge color map ───────────────────────────────────
function actionBadgeClass(string $action): string {
    if (str_contains($action, 'login'))     return 'badge-blue';
    if (str_contains($action, 'logout'))    return 'badge-blue';
    if (str_contains($action, 'Approved'))  return 'badge-green';
    if (str_contains($action, 'Rejected'))  return 'badge-red';
    if (str_contains($action, 'deleted'))   return 'badge-red';
    if (str_contains($action, 'upload'))    return 'badge-purple';
    if (str_contains($action, 'registration')) return 'badge-purple';
    if (str_contains($action, 'submitted')) return 'badge-yellow';
    if (str_contains($action, 'Info'))      return 'badge-yellow';
    if (str_contains($action, 'updated'))   return 'badge-yellow';
    return 'badge-blue';
}
?>

<style>
/* ── Log-specific styles ────────────────────────── */
.log-actor-admin   { color: #9333ea; }
.log-actor-user    { color: #2563eb; }
.log-actor-system  { color: var(--text-light); }

.log-description {
  max-width: 32rem;
  word-break: break-word;
}

.log-timestamp {
  white-space: nowrap;
  font-size: 0.75rem;
  color: var(--text-light);
}

/* ── Filter bar ─────────────────────────────────── */
.filter-bar {
  display: flex;
  flex-wrap: wrap;
  gap: 0.75rem;
  align-items: center;
  margin-bottom: 1.5rem;
}
.filter-bar select,
.filter-bar input {
  padding: 0.5rem 0.75rem;
  border: 1px solid var(--border);
  border-radius: 0.5rem;
  font-family: inherit;
  font-size: 0.85rem;
  background: white;
}
.filter-bar select:focus,
.filter-bar input:focus {
  outline: none;
  border-color: var(--gold);
  box-shadow: 0 0 0 3px rgba(201,164,75,0.15);
}
.filter-bar .btn-clear {
  color: var(--text-light);
  font-size: 0.8rem;
  text-decoration: none;
}
.filter-bar .btn-clear:hover {
  color: var(--danger);
}

/* ── Pagination ─────────────────────────────────── */
.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 0.375rem;
  padding: 1.25rem;
  border-top: 1px solid var(--border);
  flex-wrap: wrap;
}
.paginate-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 2.25rem;
  height: 2.25rem;
  padding: 0 0.75rem;
  border-radius: 0.375rem;
  font-size: 0.8rem;
  font-weight: 600;
  text-decoration: none;
  color: var(--text-dark);
  background: transparent;
  border: 1px solid transparent;
  transition: all 0.15s;
}
.paginate-btn:hover {
  background: rgba(0,0,0,0.04);
  border-color: var(--border);
}
.paginate-btn.active {
  background: var(--navy);
  color: white;
  border-color: var(--navy);
}
.paginate-btn.disabled {
  opacity: 0.3;
  pointer-events: none;
}
</style>

<!-- ── Filter Bar ────────────────────────────────── -->
<form method="GET" class="filter-bar">
  <select name="action" onchange="this.form.submit()">
    <option value="">All Actions</option>
    <?php foreach ($actionTypes as $at): ?>
      <option value="<?= htmlspecialchars($at) ?>" <?= $actionFilter === $at ? 'selected' : '' ?>>
        <?= htmlspecialchars($at) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <input type="text" name="q" placeholder="Search descriptions…" value="<?= htmlspecialchars($searchLog) ?>">

  <button type="submit" class="btn btn-outline" style="padding:0.5rem 1rem;">Filter</button>

  <?php if ($actionFilter || $searchLog): ?>
    <a href="?" class="btn-clear">Clear filters</a>
  <?php endif; ?>
</form>

<!-- ── Log Table ──────────────────────────────────── -->
<div class="card">
  <div class="card-header">
    <h2>System Activity Logs</h2>
    <div style="display:flex;gap:1rem;align-items:center;font-size:0.8rem;color:var(--text-light)">
      <span><?= number_format($totalCount) ?> records</span>
      <?php if ($totalPages > 1): ?>
        <span>Page <?= $page ?> of <?= $totalPages ?></span>
      <?php endif; ?>
    </div>
  </div>

  <div class="table-responsive">
    <table>
      <thead>
        <tr>
          <th style="min-width:140px">Timestamp</th>
          <th style="min-width:120px">Action</th>
          <th>Description</th>
          <th style="min-width:120px">Actor</th>
          <th style="min-width:110px">IP Address</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($logEntries)): ?>
          <tr>
            <td colspan="5" style="text-align:center;padding:3rem 1rem;color:var(--text-light);">
              <svg width="36" height="36" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin-bottom:0.75rem;opacity:0.3">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
              </svg>
              <p>No log entries found.</p>
              <?php if ($actionFilter || $searchLog): ?>
                <p style="font-size:0.8rem;margin-top:0.25rem;">Try adjusting your filters.</p>
              <?php endif; ?>
            </td>
          </tr>
        <?php else: foreach ($logEntries as $log):
            $badgeClass = actionBadgeClass($log['action']);
            $actorClass = $log['admin_name'] ? 'log-actor-admin' : ($log['user_name'] ? 'log-actor-user' : 'log-actor-system');
            $actorLabel = $log['admin_name'] ? "Admin: {$log['admin_name']}" :
                          ($log['user_name'] ? "User: {$log['user_name']}" : 'System');
        ?>
          <tr>
            <td class="log-timestamp">
              <?= date('M j, Y', strtotime($log['created_at'])) ?>
              <span style="display:block;font-size:0.65rem;"><?= date('H:i:s', strtotime($log['created_at'])) ?></span>
            </td>
            <td>
              <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($log['action']) ?></span>
            </td>
            <td class="log-description"><?= htmlspecialchars($log['description']) ?></td>
            <td>
              <span class="<?= $actorClass ?>" style="font-weight:600;font-size:0.85rem;"><?= htmlspecialchars($actorLabel) ?></span>
            </td>
            <td style="font-family:monospace;font-size:0.7rem;color:var(--text-light)"><?= htmlspecialchars($log['ip_address']) ?></td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>

  <?php if ($totalPages > 1): ?>
  <div class="pagination">
    <?php
      // Build query string for pagination links (preserve filters)
      $qs = http_build_query(array_filter([
          'action' => $actionFilter,
          'q'      => $searchLog,
      ]));
      $qsPrefix = $qs ? "{$qs}&" : '';
    ?>
    <a href="?<?= $qsPrefix ?>p=1" class="paginate-btn <?= $page <= 1 ? 'disabled' : '' ?>">First</a>
    <a href="?<?= $qsPrefix ?>p=<?= $page - 1 ?>" class="paginate-btn <?= $page <= 1 ? 'disabled' : '' ?>">Prev</a>

    <?php
      // Show a window of pages around the current page
      $start = max(1, $page - 2);
      $end   = min($totalPages, $page + 2);
      for ($i = $start; $i <= $end; $i++):
    ?>
      <a href="?<?= $qsPrefix ?>p=<?= $i ?>" class="paginate-btn <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>

    <a href="?<?= $qsPrefix ?>p=<?= $page + 1 ?>" class="paginate-btn <?= $page >= $totalPages ? 'disabled' : '' ?>">Next</a>
    <a href="?<?= $qsPrefix ?>p=<?= $totalPages ?>" class="paginate-btn <?= $page >= $totalPages ? 'disabled' : '' ?>">Last</a>
  </div>
  <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
