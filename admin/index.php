<?php
$page_title = 'Dashboard';
require_once __DIR__ . '/includes/header.php';

$db = getDb();

// Get metrics
$totalApps = $db->query("SELECT COUNT(*) FROM applications")->fetchColumn();
$pendingApps = $db->query("SELECT COUNT(*) FROM applications WHERE status IN ('submitted', 'under_review', 'more_info')")->fetchColumn();
$approvedApps = $db->query("SELECT COUNT(*) FROM applications WHERE status = 'approved'")->fetchColumn();
$totalUsers = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$rejectedApps = $db->query("SELECT COUNT(*) FROM applications WHERE status = 'rejected'")->fetchColumn();
$todayApps = $db->query("SELECT COUNT(*) FROM applications WHERE DATE(created_at) = CURDATE()")->fetchColumn();

// Get latest 5 applications
$recentApps = $db->query("SELECT id, applicant_name, email, sponsorship_type, status, created_at FROM applications ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Get latest 5 activity logs
$recentLogs = $db->query("SELECT a.action, a.description, a.created_at, u.name as user_name, ad.name as admin_name 
                          FROM activity_log a 
                          LEFT JOIN users u ON a.user_id = u.id 
                          LEFT JOIN admins ad ON a.admin_id = ad.id 
                          ORDER BY a.created_at DESC LIMIT 5")->fetchAll();
?>

<div class="grid-cards">
  <div class="stat-card">
    <div class="stat-title">Total Applications</div>
    <div class="stat-value"><?= number_format($totalApps) ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-title">Pending Review</div>
    <div class="stat-value" style="color:var(--gold)"><?= number_format($pendingApps) ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-title">Approved</div>
    <div class="stat-value" style="color:var(--success)"><?= number_format($approvedApps) ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-title">Total Users</div>
    <div class="stat-value"><?= number_format($totalUsers) ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-title">Rejected</div>
    <div class="stat-value" style="color:#ef4444"><?= number_format($rejectedApps) ?></div>
  </div>
  <div class="stat-card">
    <div class="stat-title">Today's Submissions</div>
    <div class="stat-value" style="color:var(--gold)"><?= number_format($todayApps) ?></div>
  </div>
</div>

<div class="dashboard-two-col" style="display:grid;grid-template-columns:minmax(0,2fr) minmax(0,1fr);gap:2rem;">
  
  <!-- Recent Apps -->
  <div class="card">
    <div class="card-header">
      <h2>Recent Applications</h2>
      <a href="applications.php" class="btn btn-outline" style="padding:0.25rem 0.75rem;font-size:0.75rem">View All</a>
    </div>
    <div class="table-responsive">
      <table>
        <thead>
          <tr>
            <th>Applicant</th>
            <th>Type</th>
            <th>Status</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($recentApps)): ?>
            <tr><td colspan="4" style="text-align:center;color:var(--text-light)">No applications found.</td></tr>
          <?php else: foreach ($recentApps as $app): ?>
            <tr>
              <td>
                <div style="font-weight:600"><?= htmlspecialchars($app['applicant_name']) ?></div>
                <div style="font-size:0.75rem;color:var(--text-light)"><?= htmlspecialchars($app['email']) ?></div>
              </td>
              <td style="text-transform:capitalize"><?= htmlspecialchars($app['sponsorship_type']) ?></td>
              <td>
                <?php
                  $s = $app['status'];
                  $sc = 'blue';
                  if ($s === 'approved') $sc = 'green';
                  if ($s === 'rejected') $sc = 'red';
                  if ($s === 'under_review' || $s === 'more_info') $sc = 'yellow';
                ?>
                <span class="badge badge-<?= $sc ?>"><?= str_replace('_', ' ', $s) ?></span>
              </td>
              <td><?= date('M j, Y', strtotime($app['created_at'])) ?></td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Recent Activity -->
  <div class="card">
    <div class="card-header">
      <h2>Recent Activity</h2>
      <a href="logs.php" class="btn btn-outline" style="padding:0.25rem 0.75rem;font-size:0.75rem">View All</a>
    </div>
    <div style="padding:1.5rem">
      <?php if (empty($recentLogs)): ?>
        <p style="text-align:center;color:var(--text-light);font-size:0.875rem">No recent activity.</p>
      <?php else: ?>
        <div style="display:flex;flex-direction:column;gap:1.25rem">
          <?php foreach ($recentLogs as $log): 
             $actor = $log['admin_name'] ? "Admin ({$log['admin_name']})" : ($log['user_name'] ? "User ({$log['user_name']})" : "System");
          ?>
            <div style="display:flex;gap:1rem;">
              <div style="width:0.5rem;background:var(--gold);border-radius:9999px;flex-shrink:0;"></div>
              <div>
                <p style="font-size:0.875rem;font-weight:600;margin-bottom:0.1rem;color:var(--navy)"><?= htmlspecialchars($log['description']) ?></p>
                <p style="font-size:0.75rem;color:var(--text-light)"><?= $actor ?> &bull; <?= date('M j, Y g:i A', strtotime($log['created_at'])) ?></p>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
