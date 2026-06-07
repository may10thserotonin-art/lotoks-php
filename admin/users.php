<?php
/**
 * Lotoks — Admin User Management (admin/users.php)
 * Enhanced with clickable user rows, detail modal, and search.
 */
$page_title = 'Users';
require_once __DIR__ . '/includes/header.php';

$db = getDb();

$searchQuery = trim($_GET['q'] ?? '');

// ── Build query ────────────────────────────────────────────────
$where  = [];
$params = [];

if ($searchQuery) {
    $where[]  = '(u.name LIKE ? OR u.email LIKE ? OR u.country LIKE ?)';
    $params[] = "%{$searchQuery}%";
    $params[] = "%{$searchQuery}%";
    $params[] = "%{$searchQuery}%";
}

$whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

$users = $db->prepare("
    SELECT u.id, u.name, u.email, u.country, u.verified, u.suspended, u.created_at,
           (SELECT COUNT(*) FROM applications a WHERE a.user_id = u.id) as app_count
    FROM users u 
    {$whereSQL}
    ORDER BY u.created_at DESC
");
$users->execute($params);
$userList = $users->fetchAll();
$resultCount = count($userList);
?>

<style>
/* ── Clickable rows ─────────────────────────────── */
tr.clickable-row {
  cursor: pointer;
}
tr.clickable-row:hover td {
  background: rgba(11,29,58,0.02);
}

/* ── User modal content ─────────────────────────── */
.user-modal-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 1.5rem;
}
@media (max-width: 500px) {
  .user-modal-grid { grid-template-columns: 1fr; }
}
.user-info-card {
  background: #f9fafb;
  border-radius: 0.5rem;
  padding: 1rem;
}
.user-info-card h4 {
  font-size: 0.75rem;
  font-weight: 700;
  color: var(--text-light);
  text-transform: uppercase;
  margin-bottom: 0.75rem;
}
.user-info-item {
  display: flex;
  justify-content: space-between;
  padding: 0.35rem 0;
  border-bottom: 1px solid #eee;
  font-size: 0.85rem;
}
.user-info-item:last-child { border-bottom: none; }
.user-info-item .label { color: var(--text-light); }
.user-info-item .value { font-weight: 600; color: var(--navy); }

/* ── User activity list ─────────────────────────── */
.activity-feed {
  max-height: 12rem;
  overflow-y: auto;
  margin-bottom: 0;
}
.activity-item {
  padding: 0.5rem 0;
  border-bottom: 1px solid #f0f0f0;
  font-size: 0.8rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 0.5rem;
}
.activity-item:last-child { border-bottom: none; }
.activity-item .act-action { font-weight: 600; color: var(--navy); }
.activity-item .act-time { font-size: 0.7rem; color: var(--text-light); white-space: nowrap; }

/* ── Mini table for user apps ───────────────────── */
.mini-table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.85rem;
}
.mini-table th {
  text-align: left;
  padding: 0.5rem;
  background: rgba(11,29,58,0.03);
  font-weight: 700;
  font-size: 0.7rem;
  text-transform: uppercase;
  color: var(--navy);
  border-bottom: 1px solid var(--border);
}
.mini-table td {
  padding: 0.5rem;
  border-bottom: 1px solid #f0f0f0;
}
.mini-table tr:last-child td { border-bottom: none; }

/* ── Search bar ─────────────────────────────────── */
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
</style>

<!-- ── Search ───────────────────────────────────── -->
<form method="GET" class="search-bar">
  <input type="text" name="q" placeholder="Search by name, email, or country…" value="<?= htmlspecialchars($searchQuery) ?>">
  <button type="submit" class="btn btn-outline" style="padding:0.65rem 1.25rem;">Search</button>
  <?php if ($searchQuery): ?>
    <a href="?" class="btn btn-outline" style="padding:0.65rem 1.25rem;">Clear</a>
  <?php endif; ?>
</form>

<!-- ── Users Table ───────────────────────────────── -->
<div class="card">
  <div class="card-header">
    <h2><?= $searchQuery ? 'Search Results' : 'Registered Users' ?></h2>
    <span style="font-size:0.8rem;color:var(--text-light)"><?= number_format($resultCount) ?> user<?= $resultCount !== 1 ? 's' : '' ?></span>
  </div>
  <div class="table-responsive">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Country</th>
          <th>Status</th>
          <th>Apps</th>
          <th>Registered</th>
          <?php if (is_super_admin()): ?>
          <th style="text-align:center">Actions</th>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($userList)): ?>
          <tr>
            <td colspan="7" style="text-align:center;padding:3rem 1rem;color:var(--text-light);">
              <?php if ($searchQuery): ?>
                <p>No users match your search.</p>
              <?php else: ?>
                <p>No users registered yet.</p>
              <?php endif; ?>
            </td>
          </tr>
        <?php else: foreach ($userList as $user): ?>
          <tr class="clickable-row" onclick="viewUser(<?= (int)$user['id'] ?>)">
            <td style="color:var(--text-light);font-size:0.75rem">#<?= htmlspecialchars($user['id']) ?></td>
            <td style="font-weight:600"><?= htmlspecialchars($user['name']) ?></td>
            <td style="color:var(--text-light);font-size:0.85rem"><?= htmlspecialchars($user['email']) ?></td>
            <td style="font-size:0.85rem"><?= htmlspecialchars($user['country'] ?: '—') ?></td>
            <td style="white-space:nowrap">
              <?php if (!empty($user['suspended'])): ?>
                <span class="badge badge-red">Suspended</span>
              <?php elseif ($user['verified']): ?>
                <span class="badge badge-green">Verified</span>
              <?php else: ?>
                <span class="badge badge-yellow">Unverified</span>
              <?php endif; ?>
            </td>
            <td><span class="badge badge-blue"><?= (int)$user['app_count'] ?></span></td>
            <td style="color:var(--text-light);font-size:0.75rem;white-space:nowrap">
              <?= date('M j, Y', strtotime($user['created_at'])) ?>
              <span style="display:block;font-size:0.65rem"><?= date('g:i A', strtotime($user['created_at'])) ?></span>
            </td>
            <?php if (is_super_admin()): ?>
            <td style="text-align:center">
              <a href="impersonate.php?user_id=<?= (int)$user['id'] ?>" class="btn btn-outline" style="font-size:0.7rem;padding:0.25rem 0.5rem;" onclick="event.stopPropagation(); return confirm('Login as <?= htmlspecialchars(addslashes($user['name'])) ?>?')">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7"/></svg>
                Impersonate
              </a>
            </td>
            <?php endif; ?>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
