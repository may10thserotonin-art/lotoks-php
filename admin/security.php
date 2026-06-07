<?php
/**
 * Lotoks — Admin Security Settings
 *
 * 2FA management and security configuration for admin accounts.
 * Requires super_admin role.
 */
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../db/connect.php';

require_admin_auth();
require_super_admin();

$page_title   = 'Security Settings | Lotoks Admin';
$page_heading = 'Security Settings';

$db      = getDb();
$adminId = (int)($_SESSION['admin']['id'] ?? 0);
$message = '';
$error   = '';

// ── Handle 2FA toggle ──────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    csrf_verify_or_fail();

    if ($_POST['action'] === 'toggle_2fa' && defined('TWO_FACTOR_ENABLED') && TWO_FACTOR_ENABLED) {
        $stmt = $db->prepare("SELECT two_factor_enabled FROM admins WHERE id = ?");
        $stmt->execute([$adminId]);
        $admin = $stmt->fetch();

        $newStatus = $admin['two_factor_enabled'] ? 0 : 1;
        $updateStmt = $db->prepare("UPDATE admins SET two_factor_enabled = ? WHERE id = ?");
        $updateStmt->execute([$newStatus, $adminId]);

        $message = $newStatus ? '2FA has been enabled for your account.' : '2FA has been disabled.';
        log_activity(null, $adminId, '2fa_toggle', "2FA " . ($newStatus ? 'enabled' : 'disabled') . " for admin #$adminId");
    }
}

// Get current admin info
$stmt = $db->prepare("SELECT id, name, email, role, two_factor_enabled, two_factor_secret FROM admins WHERE id = ?");
$stmt->execute([$adminId]);
$admin = $stmt->fetch();
?>
<?php require __DIR__ . '/includes/header.php'; ?>

<?php if ($message): ?>
  <div class="alert alert-success" style="margin-bottom:1rem;"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>
<?php if ($error): ?>
  <div class="alert alert-danger" style="margin-bottom:1rem;"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<!-- 2FA Section -->
<div class="card" style="margin-bottom:1.5rem;">
  <div class="card-header">
    <h2 style="font-size:1.1rem;font-weight:600;">Two-Factor Authentication</h2>
  </div>
  <div class="card-body">
    <?php if (!defined('TWO_FACTOR_ENABLED') || !TWO_FACTOR_ENABLED): ?>
      <div style="display:flex;align-items:flex-start;gap:0.75rem;padding:1rem;background:#fefce8;border:1px solid #eab308;border-radius:0.5rem;">
        <svg width="20" height="20" fill="none" stroke="#ca8a04" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:0.125rem;">
          <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
          <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
        </svg>
        <div>
          <strong style="color:#854d0e;">2FA is currently disabled</strong>
          <p style="color:#a16207;font-size:0.85rem;margin-top:0.25rem;">
            Two-factor authentication is not yet enabled on this server. To enable:
          </p>
          <ol style="color:#a16207;font-size:0.8rem;margin:0.5rem 0 0 1.25rem;">
            <li>Set <code>TWO_FACTOR_ENABLED = true</code> in <code>includes/config.php</code></li>
            <li>Run <code>migrations/migration_v2.sql</code> to add the required columns</li>
            <li>Install a 2FA library (e.g., PragmaRX/Google2FA or Sonata/GoogleAuthenticator)</li>
          </ol>
        </div>
      </div>
    <?php else: ?>
      <div class="sec-2fa-active">
        <div>
          <strong style="font-size:1rem;">Authenticator App</strong>
          <p style="color:#6b7280;font-size:0.85rem;margin-top:0.25rem;">
            Add an extra layer of security by requiring a one-time code from your authenticator app.
          </p>
          <span style="font-size:0.8rem;color:<?= $admin['two_factor_enabled'] ? '#16a34a' : '#9ca3af' ?>;">
            Status: <strong><?= $admin['two_factor_enabled'] ? 'Enabled' : 'Disabled' ?></strong>
          </span>
        </div>
        <form method="POST" action="" style="margin:0;">
          <?= csrf_field() ?>
          <input type="hidden" name="action" value="toggle_2fa" />
          <button type="submit" class="btn <?= $admin['two_factor_enabled'] ? 'btn-outline btn-danger' : 'btn-primary' ?>">
            <?= $admin['two_factor_enabled'] ? 'Disable 2FA' : 'Enable 2FA' ?>
          </button>
        </form>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Account Security Info -->
<div class="card">
  <div class="card-header">
    <h2 style="font-size:1.1rem;font-weight:600;">Account Security</h2>
  </div>
  <div class="card-body">
    <div class="sec-info-grid">
      <div>
        <span style="color:#6b7280;">Account Name:</span>
        <span style="font-weight:600;margin-left:0.5rem;"><?= htmlspecialchars($admin['name']) ?></span>
      </div>
      <div>
        <span style="color:#6b7280;">Email:</span>
        <span style="font-weight:600;margin-left:0.5rem;"><?= htmlspecialchars($admin['email']) ?></span>
      </div>
      <div>
        <span style="color:#6b7280;">Role:</span>
        <span style="font-weight:600;margin-left:0.5rem;text-transform:capitalize;"><?= htmlspecialchars($admin['role']) ?></span>
      </div>
      <div>
        <span style="color:#6b7280;">Password:</span>
        <span style="margin-left:0.5rem;">
          <a href="<?= BASE ?>/forgot-password.php" style="color:var(--gold);font-size:0.85rem;">Change Password</a>
        </span>
      </div>
    </div>
  </div>
</div>

<style>
/* ── Responsive Security Page Styles ────────── */
.sec-2fa-active {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
}
@media (max-width: 600px) {
  .sec-2fa-active {
    flex-direction: column;
    align-items: flex-start;
  }
}
.sec-info-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  font-size: 0.9rem;
}
@media (max-width: 600px) {
  .sec-info-grid {
    grid-template-columns: 1fr;
    gap: 0.75rem;
  }
  .sec-info-grid > div {
    display: flex;
    flex-direction: column;
    gap: 0.15rem;
  }
  .sec-info-grid > div span[style*="margin-left"] {
    margin-left: 0 !important;
  }
}
</style>

<?php require __DIR__ . '/includes/footer.php'; ?>
