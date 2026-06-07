<?php
/**
 * admin/settings.php
 * Admin Settings — account, notifications, and site-wide configuration.
 */
$page_title = 'Settings';
require_once __DIR__ . '/includes/header.php';

$db      = getDb();
$adminId = (int)($admin['id'] ?? 0);
$isSuper = is_super_admin();
$message = '';
$error   = '';

// ── Handle POST actions ─────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        flash('error', 'Security token mismatch.');
        redirect('/admin/settings.php');
    }

    $action = $_POST['action'] ?? '';

    try {

        // ── Update Admin Profile ──────────────────────────────
        if ($action === 'update_profile') {
            $name  = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');

            if (empty($name) || empty($email)) {
                throw new Exception('Name and email are required.');
            }

            $stmt = $db->prepare("UPDATE admins SET name = ?, email = ? WHERE id = ?");
            $stmt->execute([$name, $email, $adminId]);

            $_SESSION['admin']['name']  = $name;
            $_SESSION['admin']['email'] = $email;

            log_activity(null, $adminId, 'profile_updated', 'Admin updated their profile');
            flash('success', 'Profile updated successfully.');
            redirect('/admin/settings.php');
        }

        // ── Change Admin Password ─────────────────────────────
        if ($action === 'change_password') {
            $current   = $_POST['current_password'] ?? '';
            $newPass   = $_POST['new_password'] ?? '';

            if (empty($current) || empty($newPass)) {
                throw new Exception('All password fields are required.');
            }
            if (strlen($newPass) < 8) {
                throw new Exception('New password must be at least 8 characters.');
            }

            $stmt = $db->prepare("SELECT password_hash FROM admins WHERE id = ?");
            $stmt->execute([$adminId]);
            $row = $stmt->fetch();

            if (!$row || !password_verify($current, $row['password_hash'])) {
                throw new Exception('Current password is incorrect.');
            }

            $hash = password_hash($newPass, PASSWORD_BCRYPT, ['cost' => 10]);
            $stmt = $db->prepare("UPDATE admins SET password_hash = ? WHERE id = ?");
            $stmt->execute([$hash, $adminId]);

            log_activity(null, $adminId, 'password_changed', 'Admin changed their password');
            flash('success', 'Password changed successfully.');
            redirect('/admin/settings.php');
        }

        // ── Update Notification Preferences ───────────────────
        if ($action === 'update_notifications') {
            $emailNotif = !empty($_POST['email_notifications']) ? 1 : 0;
            $loginAlerts= !empty($_POST['login_alerts']) ? 1 : 0;

            $stmt = $db->prepare("UPDATE admins SET email_notifications = ?, login_alerts = ? WHERE id = ?");
            $stmt->execute([$emailNotif, $loginAlerts, $adminId]);

            log_activity(null, $adminId, 'notifications_updated', 'Admin updated notification preferences');
            flash('success', 'Notification preferences saved.');
            redirect('/admin/settings.php');
        }

        // ── Update Site Setting (super_admin only) ────────────
        if ($action === 'update_site_setting' && $isSuper) {
            $key   = trim($_POST['setting_key'] ?? '');
            $value = trim($_POST['setting_value'] ?? '');

            if (empty($key)) {
                throw new Exception('Setting key is required.');
            }

            $stmt = $db->prepare("INSERT INTO site_settings (setting_key, setting_value, updated_by) VALUES (?, ?, ?)
                                  ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value), updated_by = VALUES(updated_by), updated_at = NOW()");
            $stmt->execute([$key, $value, $adminId]);

            log_activity(null, $adminId, 'site_setting_updated', "Updated site setting: {$key}");
            flash('success', "Setting '{$key}' updated.");
            redirect('/admin/settings.php');
        }

    } catch (Exception $e) {
        flash('error', $e->getMessage());
        redirect('/admin/settings.php');
    }
}

// ── Load admin data ─────────────────────────────────────────────
$stmt = $db->prepare("SELECT id, name, email, role, email_notifications, login_alerts FROM admins WHERE id = ?");
$stmt->execute([$adminId]);
$adminData = $stmt->fetch();

// ── Load site settings (super_admin only) ───────────────────────
$siteSettings = [];
if ($isSuper) {
    $siteSettings = $db->query("SELECT setting_key, setting_value, description FROM site_settings ORDER BY setting_key")->fetchAll();
}

$tab = $_GET['tab'] ?? 'account';
?>

<style>
/* ── Settings tabs ────────────────────────────── */
.settings-tabs {
  display: flex;
  gap: 0.25rem;
  margin-bottom: 1.5rem;
  border-bottom: 1px solid var(--border);
  padding-bottom: 0;
  overflow-x: auto;
}
.settings-tab {
  padding: 0.65rem 1.25rem;
  font-size: 0.85rem;
  font-weight: 600;
  color: var(--text-light);
  text-decoration: none;
  border-bottom: 2px solid transparent;
  transition: all 0.2s;
  display: flex;
  align-items: center;
  gap: 0.4rem;
  white-space: nowrap;
}
.settings-tab:hover { color: var(--navy); }
.settings-tab.active {
  color: var(--navy);
  border-bottom-color: var(--gold);
}

/* ── Setting form rows ───────────────────────── */
.setting-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 0;
  border-bottom: 1px solid var(--border);
}
.setting-row:last-child { border-bottom: none; }
.setting-label { font-weight: 600; font-size: 0.9rem; }
.setting-desc { font-size: 0.8rem; color: var(--text-light); margin-top: 0.15rem; }

/* ── Toggle switch ───────────────────────────── */
.switch {
  position: relative;
  display: inline-block;
  width: 44px;
  height: 24px;
  flex-shrink: 0;
}
.switch input { opacity: 0; width: 0; height: 0; }
.switch-slider {
  position: absolute;
  cursor: pointer;
  top: 0; left: 0; right: 0; bottom: 0;
  background: #d1d5db;
  border-radius: 24px;
  transition: .3s;
}
.switch-slider::before {
  content: "";
  position: absolute;
  width: 18px; height: 18px;
  left: 3px; bottom: 3px;
  background: #fff;
  border-radius: 50%;
  transition: .3s;
}
.switch input:checked + .switch-slider {
  background: var(--gold);
}
.switch input:checked + .switch-slider::before {
  transform: translateX(20px);
}

/* ── Site settings grid ──────────────────────── */
.site-settings-grid {
  display: grid;
  gap: 1rem;
}
.site-setting-card {
  background: #f9fafb;
  border-radius: 0.5rem;
  padding: 1rem;
  border: 1px solid var(--border);
}
.site-setting-card label {
  font-weight: 600;
  font-size: 0.85rem;
  display: block;
  margin-bottom: 0.25rem;
}
.site-setting-card .hint {
  font-size: 0.75rem;
  color: var(--text-light);
  margin-bottom: 0.5rem;
}
.site-setting-card input,
.site-setting-card textarea {
  width: 100%;
  padding: 0.5rem 0.75rem;
  border: 1px solid var(--border);
  border-radius: 0.375rem;
  font-size: 0.85rem;
  box-sizing: border-box;
}
.site-setting-card textarea { min-height: 60px; resize: vertical; }
.site-setting-card .btn-sm {
  margin-top: 0.5rem;
}
</style>

<!-- ── Tab Navigation ───────────────────────────── -->
<div class="settings-tabs">
    <a href="?tab=account"       class="settings-tab <?= $tab === 'account'       ? 'active' : '' ?>">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
        Account
    </a>
    <a href="?tab=notifications" class="settings-tab <?= $tab === 'notifications' ? 'active' : '' ?>">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
        Notifications
    </a>
    <a href="?tab=security"      class="settings-tab <?= $tab === 'security'      ? 'active' : '' ?>">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
        Security
    </a>
    <?php if ($isSuper): ?>
    <a href="?tab=site"          class="settings-tab <?= $tab === 'site'          ? 'active' : '' ?>">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/></svg>
        Site Settings
    </a>
    <?php endif; ?>
</div>


<?php if ($tab === 'account'): ?>
<!-- ════════════════ ACCOUNT ════════════════ -->
<div class="card">
    <div class="card-header"><h2>Account Information</h2></div>
    <div class="card-body">
        <form method="POST" action="">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="update_profile">

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;max-width:500px">
                <div>
                    <label style="font-weight:600;font-size:0.85rem;display:block;margin-bottom:0.35rem">Name</label>
                    <input type="text" name="name" value="<?= htmlspecialchars($adminData['name'] ?? '') ?>" required
                           style="width:100%;padding:0.6rem 0.75rem;border:1px solid var(--border);border-radius:0.375rem;font-size:0.85rem;box-sizing:border-box">
                </div>
                <div>
                    <label style="font-weight:600;font-size:0.85rem;display:block;margin-bottom:0.35rem">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($adminData['email'] ?? '') ?>" required
                           style="width:100%;padding:0.6rem 0.75rem;border:1px solid var(--border);border-radius:0.375rem;font-size:0.85rem;box-sizing:border-box">
                </div>
            </div>

            <div style="margin-top:1rem">
                <span style="font-size:0.8rem;color:var(--text-light);text-transform:uppercase">Role: <strong style="color:var(--navy)"><?= htmlspecialchars(str_replace('_', ' ', $adminData['role'] ?? '')) ?></strong></span>
            </div>

            <div style="margin-top:1.5rem">
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>


<?php elseif ($tab === 'notifications'): ?>
<!-- ════════════════ NOTIFICATIONS ════════════════ -->
<div class="card">
    <div class="card-header"><h2>Notification Preferences</h2></div>
    <div class="card-body">
        <form method="POST" action="">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="update_notifications">

            <div class="setting-row">
                <div>
                    <div class="setting-label">Email Notifications</div>
                    <div class="setting-desc">Receive email updates for new applications and inquiries</div>
                </div>
                <label class="switch">
                    <input type="checkbox" name="email_notifications" value="1" <?= !empty($adminData['email_notifications']) ? 'checked' : '' ?>>
                    <span class="switch-slider"></span>
                </label>
            </div>

            <div class="setting-row">
                <div>
                    <div class="setting-label">Login Alerts</div>
                    <div class="setting-desc">Receive an email when someone logs into your admin account</div>
                </div>
                <label class="switch">
                    <input type="checkbox" name="login_alerts" value="1" <?= !empty($adminData['login_alerts']) ? 'checked' : '' ?>>
                    <span class="switch-slider"></span>
                </label>
            </div>

            <div style="margin-top:1.5rem">
                <button type="submit" class="btn btn-primary">Save Preferences</button>
            </div>
        </form>
    </div>
</div>


<?php elseif ($tab === 'security'): ?>
<!-- ════════════════ SECURITY ════════════════ -->
<div class="card" style="margin-bottom:1.5rem">
    <div class="card-header"><h2>Change Password</h2></div>
    <div class="card-body">
        <form method="POST" action="">
            <?= csrf_field() ?>
            <input type="hidden" name="action" value="change_password">

            <div style="display:grid;grid-template-columns:1fr;gap:1rem;max-width:400px">
                <div>
                    <label style="font-weight:600;font-size:0.85rem;display:block;margin-bottom:0.35rem">Current Password</label>
                    <input type="password" name="current_password" required
                           style="width:100%;padding:0.6rem 0.75rem;border:1px solid var(--border);border-radius:0.375rem;font-size:0.85rem;box-sizing:border-box">
                </div>
                <div>
                    <label style="font-weight:600;font-size:0.85rem;display:block;margin-bottom:0.35rem">New Password</label>
                    <input type="password" name="new_password" required minlength="8"
                           style="width:100%;padding:0.6rem 0.75rem;border:1px solid var(--border);border-radius:0.375rem;font-size:0.85rem;box-sizing:border-box">
                    <p style="font-size:0.75rem;color:var(--text-light);margin-top:0.25rem">Minimum 8 characters</p>
                </div>
                <div>
                    <label style="font-weight:600;font-size:0.85rem;display:block;margin-bottom:0.35rem">Confirm New Password</label>
                    <input type="password" name="new_password_confirm" required minlength="8"
                           style="width:100%;padding:0.6rem 0.75rem;border:1px solid var(--border);border-radius:0.375rem;font-size:0.85rem;box-sizing:border-box"
                           oninput="this.value !== document.querySelector('[name=new_password]').value ? this.setCustomValidity('Passwords do not match') : this.setCustomValidity('')">
                </div>
            </div>

            <div style="margin-top:1.5rem">
                <button type="submit" class="btn btn-primary">Update Password</button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><h2>Two-Factor Authentication</h2></div>
    <div class="card-body">
        <?php if (!defined('TWO_FACTOR_ENABLED') || !TWO_FACTOR_ENABLED): ?>
            <div style="display:flex;align-items:flex-start;gap:0.75rem;padding:1rem;background:#fefce8;border:1px solid #eab308;border-radius:0.5rem;">
                <svg width="20" height="20" fill="none" stroke="#ca8a04" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;margin-top:0.125rem;">
                    <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                    <line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/>
                </svg>
                <div>
                    <strong style="color:#854d0e;">2FA is not yet available</strong>
                    <p style="color:#a16207;font-size:0.85rem;margin-top:0.25rem;">
                        Two-factor authentication can be configured once it is enabled on the server. Visit the
                        <a href="security.php" style="color:var(--gold);">Security page</a> for more info.
                    </p>
                </div>
            </div>
        <?php else: ?>
            <p style="color:var(--text-light);font-size:0.9rem">2FA is available. Manage it on the <a href="security.php">Security page</a>.</p>
        <?php endif; ?>
    </div>
</div>


<?php elseif ($tab === 'site' && $isSuper): ?>
<!-- ════════════════ SITE SETTINGS ════════════════ -->
<div class="card">
    <div class="card-header"><h2>Site Configuration</h2></div>
    <div class="card-body">
        <p style="color:var(--text-light);font-size:0.85rem;margin-bottom:1.5rem">
            Manage global site-wide settings. Changes take effect immediately.
        </p>

        <div class="site-settings-grid">
            <?php foreach ($siteSettings as $s):
                $key   = htmlspecialchars($s['setting_key']);
                $value = htmlspecialchars($s['setting_value']);
                $desc  = htmlspecialchars($s['description'] ?? '');
                $isBool = in_array($s['setting_key'], ['applications_open', 'maintenance_mode', 'registration_open']);
            ?>
            <form method="POST" action="" class="site-setting-card">
                <?= csrf_field() ?>
                <input type="hidden" name="action" value="update_site_setting">
                <input type="hidden" name="setting_key" value="<?= $key ?>">

                <label for="setting-<?= $key ?>"><?= $key ?></label>
                <div class="hint"><?= $desc ?></div>

                <?php if ($isBool): ?>
                <div style="display:flex;align-items:center;gap:0.75rem">
                    <label class="switch">
                        <input type="checkbox" name="setting_value" value="1" id="setting-<?= $key ?>"
                               onchange="this.form.querySelector('.bool-val').textContent = this.checked ? 'Yes' : 'No'"
                               <?= $value === '1' ? 'checked' : '' ?>>
                        <span class="switch-slider"></span>
                    </label>
                    <span class="bool-val" style="font-weight:600;font-size:0.85rem"><?= $value === '1' ? 'Yes' : 'No' ?></span>
                    <button type="submit" class="btn btn-sm btn-outline" style="margin:0 0 0 auto">Save</button>
                </div>
                <?php else: ?>
                <div style="display:flex;gap:0.5rem;align-items:flex-start">
                    <div style="flex:1">
                        <?php if (in_array($s['setting_key'], ['site_tagline'])): ?>
                        <textarea name="setting_value" id="setting-<?= $key ?>" style="width:100%"><?= $value ?></textarea>
                        <?php else: ?>
                        <input type="text" name="setting_value" id="setting-<?= $key ?>" value="<?= $value ?>">
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-sm btn-outline" style="margin-top:0.35rem">Save</button>
                </div>
                <?php endif; ?>
            </form>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<?php require __DIR__ . '/includes/footer.php'; ?>
