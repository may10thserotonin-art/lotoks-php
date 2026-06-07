<?php
/**
 * settings.php
 * User Settings — manage profile, notifications, and security preferences.
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/db/connect.php';
requireUserAuth('/login.php');

$current_page = 'settings';
$user = getCurrentUser();
$userId = (int)($user['id'] ?? 0);

$db = getDb();

// ── Load user profile ─────────────────────────────────────────
$stmt = $db->prepare("SELECT id, name, email, country, created_at FROM users WHERE id = ?");
$stmt->execute([$userId]);
$profile = $stmt->fetch();

if (!$profile) {
    redirect('/dashboard.php');
}

// ── Load or create user settings ──────────────────────────────
$stmt = $db->prepare("SELECT * FROM user_settings WHERE user_id = ?");
$stmt->execute([$userId]);
$userSettings = $stmt->fetch();

if (!$userSettings) {
    // Insert defaults
    $db->prepare("INSERT INTO user_settings (user_id) VALUES (?)")->execute([$userId]);
    $userSettings = [
        'email_notifications' => 1,
        'sms_notifications'   => 0,
        'application_updates' => 1,
        'marketing_emails'    => 0,
        'language'            => 'en',
        'timezone'            => 'Africa/Johannesburg',
    ];
}

$page_title       = 'Settings — Lotoks';
$page_description = 'Manage your account settings and preferences.';
?>
<!DOCTYPE html>
<html lang="en">
<?php include __DIR__ . '/includes/head.php'; ?>
<body class="page-loaded" style="background-color:#0B1D3A">

<div class="portal-wrap">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <main class="portal-main">
        <header class="portal-topbar">
            <button class="sidebar-toggle-btn" id="sidebar-toggle" aria-label="Open navigation menu">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="7" x2="21" y2="7"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="17" x2="21" y2="17"/>
                </svg>
            </button>
            <a href="<?= BASE ?>/index.php" class="topbar-brand">Lotoks<span>.</span></a>
            <div><h1 style="font-size:1rem;color:#fff;font-weight:700;margin:0;">Settings</h1></div>
            <div></div>
        </header>

        <div class="portal-content" style="padding-top:1.5rem;padding-bottom:6rem">
            <!-- Breadcrumb -->
            <div style="margin-bottom:1.5rem;font-size:0.8rem">
                <a href="<?= BASE ?>/dashboard.php" style="color:rgba(255,255,255,.4);text-decoration:none">Dashboard</a>
                <span style="color:rgba(255,255,255,.2);margin:0 0.5rem">→</span>
                <span style="color:var(--color-gold);font-weight:600">Settings</span>
            </div>

            <!-- Tab Navigation -->
            <div class="settings-tabs" style="display:flex;gap:0.5rem;margin-bottom:1.5rem;border-bottom:1px solid rgba(255,255,255,.1);padding-bottom:0.75rem;overflow-x:auto;white-space:nowrap;">
                <button class="settings-tab active" data-tab="profile"
                        style="padding:0.5rem 1.25rem;border-radius:0.5rem;border:none;background:var(--color-gold);color:var(--color-navy);font-weight:700;font-size:0.8rem;cursor:pointer;transition:all .2s">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:0.35rem"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Profile
                </button>
                <button class="settings-tab" data-tab="notifications"
                        style="padding:0.5rem 1.25rem;border-radius:0.5rem;border:none;background:rgba(255,255,255,.06);color:rgba(255,255,255,.6);font-weight:600;font-size:0.8rem;cursor:pointer;transition:all .2s">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:0.35rem"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                    Notifications
                </button>
                <button class="settings-tab" data-tab="security"
                        style="padding:0.5rem 1.25rem;border-radius:0.5rem;border:none;background:rgba(255,255,255,.06);color:rgba(255,255,255,.6);font-weight:600;font-size:0.8rem;cursor:pointer;transition:all .2s">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:0.35rem"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    Security
                </button>
            </div>

            <!-- ─── Tab: Profile ──────────────────────────────── -->
            <div class="settings-panel active" id="panel-profile">
                <div style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:1rem;padding:1.5rem">
                    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem">
                        <div style="padding:0.75rem;border-radius:9999px;background:rgba(201,164,75,.2);color:var(--color-gold)">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        </div>
                        <div>
                            <h2 style="font-size:1.25rem;font-weight:700;color:#fff;margin:0">Profile Information</h2>
                            <p style="font-size:0.8rem;color:rgba(255,255,255,.4);margin:0.25rem 0 0 0">Update your personal details</p>
                        </div>
                    </div>

                    <div id="profile-status" style="display:none;padding:0.75rem 1rem;border-radius:0.5rem;margin-bottom:1rem;font-weight:600;font-size:0.85rem"></div>

                    <form id="profile-form" onsubmit="return saveProfile(event)">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
                            <div>
                                <label style="display:block;font-size:0.7rem;font-weight:700;color:rgba(255,255,255,.4);text-transform:uppercase;margin-bottom:0.4rem">Full Name</label>
                                <input type="text" name="name" value="<?= htmlspecialchars($profile['name'] ?? '') ?>" required
                                       style="width:100%;padding:0.7rem 0.9rem;border-radius:0.5rem;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.05);color:#fff;font-size:0.85rem;outline:none;box-sizing:border-box">
                            </div>
                            <div>
                                <label style="display:block;font-size:0.7rem;font-weight:700;color:rgba(255,255,255,.4);text-transform:uppercase;margin-bottom:0.4rem">Email</label>
                                <input type="email" value="<?= htmlspecialchars($profile['email'] ?? '') ?>" disabled
                                       style="width:100%;padding:0.7rem 0.9rem;border-radius:0.5rem;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.02);color:rgba(255,255,255,.4);font-size:0.85rem;outline:none;box-sizing:border-box">
                                <p style="font-size:0.65rem;color:rgba(255,255,255,.25);margin-top:0.25rem">Email cannot be changed</p>
                            </div>
                            <div>
                                <label style="display:block;font-size:0.7rem;font-weight:700;color:rgba(255,255,255,.4);text-transform:uppercase;margin-bottom:0.4rem">Country</label>
                                <?= countryDropdown('country', $profile['country'] ?? '', ['id' => 'country', 'class' => 'form-input', 'placeholder' => 'Select your country']) ?>
                            </div>
                            <div>
                                <label style="display:block;font-size:0.7rem;font-weight:700;color:rgba(255,255,255,.4);text-transform:uppercase;margin-bottom:0.4rem">Member Since</label>
                                <input type="text" value="<?= !empty($profile['created_at']) ? date('F Y', strtotime($profile['created_at'])) : 'Today' ?>" disabled
                                       style="width:100%;padding:0.7rem 0.9rem;border-radius:0.5rem;border:1px solid rgba(255,255,255,.08);background:rgba(255,255,255,.02);color:rgba(255,255,255,.4);font-size:0.85rem;outline:none;box-sizing:border-box">
                            </div>
                        </div>

                        <div style="margin-top:1.5rem">
                            <button type="submit" id="profile-save-btn"
                                    style="padding:0.7rem 2rem;border-radius:0.5rem;background:var(--color-gold);color:var(--color-navy);font-weight:700;font-size:0.85rem;border:none;cursor:pointer">
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ─── Tab: Notifications ────────────────────────── -->
            <div class="settings-panel" id="panel-notifications" style="display:none">
                <div style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:1rem;padding:1.5rem">
                    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem">
                        <div style="padding:0.75rem;border-radius:9999px;background:rgba(201,164,75,.2);color:var(--color-gold)">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                        </div>
                        <div>
                            <h2 style="font-size:1.25rem;font-weight:700;color:#fff;margin:0">Notification Preferences</h2>
                            <p style="font-size:0.8rem;color:rgba(255,255,255,.4);margin:0.25rem 0 0 0">Choose what updates you receive</p>
                        </div>
                    </div>

                    <div id="notif-status" style="display:none;padding:0.75rem 1rem;border-radius:0.5rem;margin-bottom:1rem;font-weight:600;font-size:0.85rem"></div>

                    <form id="notif-form" onsubmit="return saveNotifications(event)">
                        <input type="hidden" name="action" value="notifications">

                        <div style="display:flex;flex-direction:column;gap:1rem">
                            <!-- Email notifications -->
                            <label class="toggle-row" style="display:flex;align-items:center;justify-content:space-between;padding:1rem;background:rgba(255,255,255,.04);border-radius:0.75rem;border:1px solid rgba(255,255,255,.06);cursor:pointer">
                                <div>
                                    <div style="font-weight:600;color:#fff;font-size:0.9rem">Email Notifications</div>
                                    <div style="font-size:0.75rem;color:rgba(255,255,255,.4);margin-top:0.15rem">Receive email updates about your applications and account</div>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" name="email_notifications" value="1" <?= !empty($userSettings['email_notifications']) ? 'checked' : '' ?>>
                                    <span class="switch-slider"></span>
                                </label>
                            </label>

                            <!-- SMS notifications -->
                            <label class="toggle-row" style="display:flex;align-items:center;justify-content:space-between;padding:1rem;background:rgba(255,255,255,.04);border-radius:0.75rem;border:1px solid rgba(255,255,255,.06);cursor:pointer">
                                <div>
                                    <div style="font-weight:600;color:#fff;font-size:0.9rem">SMS Notifications</div>
                                    <div style="font-size:0.75rem;color:rgba(255,255,255,.4);margin-top:0.15rem">Get SMS alerts for important updates</div>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" name="sms_notifications" value="1" <?= !empty($userSettings['sms_notifications']) ? 'checked' : '' ?>>
                                    <span class="switch-slider"></span>
                                </label>
                            </label>

                            <!-- Application updates -->
                            <label class="toggle-row" style="display:flex;align-items:center;justify-content:space-between;padding:1rem;background:rgba(255,255,255,.04);border-radius:0.75rem;border:1px solid rgba(255,255,255,.06);cursor:pointer">
                                <div>
                                    <div style="font-weight:600;color:#fff;font-size:0.9rem">Application Status Changes</div>
                                    <div style="font-size:0.75rem;color:rgba(255,255,255,.4);margin-top:0.15rem">Notify me when my application status changes</div>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" name="application_updates" value="1" <?= !empty($userSettings['application_updates']) ? 'checked' : '' ?>>
                                    <span class="switch-slider"></span>
                                </label>
                            </label>

                            <!-- Marketing emails -->
                            <label class="toggle-row" style="display:flex;align-items:center;justify-content:space-between;padding:1rem;background:rgba(255,255,255,.04);border-radius:0.75rem;border:1px solid rgba(255,255,255,.06);cursor:pointer">
                                <div>
                                    <div style="font-weight:600;color:#fff;font-size:0.9rem">Marketing &amp; Newsletter</div>
                                    <div style="font-size:0.75rem;color:rgba(255,255,255,.4);margin-top:0.15rem">Receive tips, opportunities, and platform news</div>
                                </div>
                                <label class="switch">
                                    <input type="checkbox" name="marketing_emails" value="1" <?= !empty($userSettings['marketing_emails']) ? 'checked' : '' ?>>
                                    <span class="switch-slider"></span>
                                </label>
                            </label>
                        </div>

                        <div style="margin-top:1.5rem">
                            <button type="submit" id="notif-save-btn"
                                    style="padding:0.7rem 2rem;border-radius:0.5rem;background:var(--color-gold);color:var(--color-navy);font-weight:700;font-size:0.85rem;border:none;cursor:pointer">
                                Save Preferences
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ─── Tab: Security ─────────────────────────────── -->
            <div class="settings-panel" id="panel-security" style="display:none">
                <div style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:1rem;padding:1.5rem">
                    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem">
                        <div style="padding:0.75rem;border-radius:9999px;background:rgba(201,164,75,.2);color:var(--color-gold)">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        <div>
                            <h2 style="font-size:1.25rem;font-weight:700;color:#fff;margin:0">Password &amp; Security</h2>
                            <p style="font-size:0.8rem;color:rgba(255,255,255,.4);margin:0.25rem 0 0 0">Update your password and manage account security</p>
                        </div>
                    </div>

                    <div id="security-status" style="display:none;padding:0.75rem 1rem;border-radius:0.5rem;margin-bottom:1rem;font-weight:600;font-size:0.85rem"></div>

                    <form id="security-form" onsubmit="return changePassword(event)">
                        <input type="hidden" name="action" value="password">

                        <div style="display:grid;grid-template-columns:1fr;gap:1rem;max-width:400px">
                            <div>
                                <label style="display:block;font-size:0.7rem;font-weight:700;color:rgba(255,255,255,.4);text-transform:uppercase;margin-bottom:0.4rem">Current Password</label>
                                <input type="password" name="current_password" required
                                       style="width:100%;padding:0.7rem 0.9rem;border-radius:0.5rem;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.05);color:#fff;font-size:0.85rem;outline:none;box-sizing:border-box">
                            </div>
                            <div>
                                <label style="display:block;font-size:0.7rem;font-weight:700;color:rgba(255,255,255,.4);text-transform:uppercase;margin-bottom:0.4rem">New Password</label>
                                <input type="password" name="new_password" required minlength="8"
                                       style="width:100%;padding:0.7rem 0.9rem;border-radius:0.5rem;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.05);color:#fff;font-size:0.85rem;outline:none;box-sizing:border-box">
                                <p style="font-size:0.65rem;color:rgba(255,255,255,.25);margin-top:0.25rem">Minimum 8 characters</p>
                            </div>
                            <div>
                                <label style="display:block;font-size:0.7rem;font-weight:700;color:rgba(255,255,255,.4);text-transform:uppercase;margin-bottom:0.4rem">Confirm New Password</label>
                                <input type="password" name="confirm_password" required minlength="8"
                                       style="width:100%;padding:0.7rem 0.9rem;border-radius:0.5rem;border:1px solid rgba(255,255,255,.12);background:rgba(255,255,255,.05);color:#fff;font-size:0.85rem;outline:none;box-sizing:border-box">
                            </div>
                        </div>

                        <div style="margin-top:1.5rem">
                            <button type="submit" id="security-save-btn"
                                    style="padding:0.7rem 2rem;border-radius:0.5rem;background:var(--color-gold);color:var(--color-navy);font-weight:700;font-size:0.85rem;border:none;cursor:pointer">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Back to Dashboard -->
            <div style="text-align:center;margin-top:1.5rem">
                <a href="<?= BASE ?>/dashboard.php" style="display:inline-flex;align-items:center;gap:0.5rem;padding:0.75rem 2rem;border-radius:9999px;background:var(--color-gold);color:var(--color-navy);font-weight:700;font-size:0.85rem;text-decoration:none">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Back to Dashboard
                </a>
            </div>
        </div>
    </main>
</div>

<style>
.settings-tabs { overflow-x: auto; white-space: nowrap; }
.settings-tab { transition: all 0.2s ease; }
.settings-tab:hover:not(.active) { background: rgba(255,255,255,.1); color: #fff; }

/* Toggle switch */
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
  background: rgba(255,255,255,.15);
  border-radius: 24px;
  transition: .3s;
}
.switch-slider::before {
  content: "";
  position: absolute;
  width: 18px;
  height: 18px;
  left: 3px;
  bottom: 3px;
  background: #fff;
  border-radius: 50%;
  transition: .3s;
}
.switch input:checked + .switch-slider {
  background: var(--color-gold);
}
.switch input:checked + .switch-slider::before {
  transform: translateX(20px);
}
.toggle-row:hover { border-color: rgba(255,255,255,.15) !important; }
</style>

<?php include __DIR__ . '/includes/scripts.php'; ?>
<script>
const CSRF = '<?= htmlspecialchars(generateCsrfToken()) ?>';
const BASE = window.LOTOKS_CONFIG?.BASE || '';

// ── Tab Switching ──────────────────────────────────────────────
document.querySelectorAll('.settings-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.settings-tab').forEach(t => {
            t.style.background = 'rgba(255,255,255,.06)';
            t.style.color = 'rgba(255,255,255,.6)';
        });
        this.style.background = 'var(--color-gold)';
        this.style.color = 'var(--color-navy)';

        document.querySelectorAll('.settings-panel').forEach(p => p.style.display = 'none');
        document.getElementById('panel-' + this.dataset.tab).style.display = 'block';
    });
});

// ── Save Profile ───────────────────────────────────────────────
async function saveProfile(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('profile-save-btn');
    const status = document.getElementById('profile-status');

    btn.disabled = true;
    btn.textContent = 'Saving...';
    status.style.display = 'none';

    const fd = new FormData(form);
    fd.set('action', 'update');

    try {
        const res = await fetch(BASE + '/api/user/settings.php', {
            method: 'POST', body: fd, credentials: 'include'
        });
        const data = await res.json();
        if (data.success) {
            showSuccess(status, 'Profile updated successfully.');
        } else {
            showError(status, data.message || 'Update failed.');
        }
    } catch (err) {
        showError(status, 'Network error. Please try again.');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Save Changes';
    }
    return false;
}

// ── Save Notifications ─────────────────────────────────────────
async function saveNotifications(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('notif-save-btn');
    const status = document.getElementById('notif-status');

    btn.disabled = true;
    btn.textContent = 'Saving...';
    status.style.display = 'none';

    const fd = new FormData(form);

    try {
        const res = await fetch(BASE + '/api/user/settings.php', {
            method: 'POST', body: fd, credentials: 'include'
        });
        const data = await res.json();
        if (data.success) {
            showSuccess(status, 'Preferences saved successfully.');
        } else {
            showError(status, data.message || 'Save failed.');
        }
    } catch (err) {
        showError(status, 'Network error. Please try again.');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Save Preferences';
    }
    return false;
}

// ── Change Password ────────────────────────────────────────────
async function changePassword(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('security-save-btn');
    const status = document.getElementById('security-status');

    const newPass = form.querySelector('[name="new_password"]').value;
    const confirmPass = form.querySelector('[name="confirm_password"]').value;

    if (newPass !== confirmPass) {
        showError(status, 'New passwords do not match.');
        return false;
    }
    if (newPass.length < 8) {
        showError(status, 'Password must be at least 8 characters.');
        return false;
    }

    btn.disabled = true;
    btn.textContent = 'Updating...';
    status.style.display = 'none';

    const fd = new FormData(form);

    try {
        const res = await fetch(BASE + '/api/user/settings.php', {
            method: 'POST', body: fd, credentials: 'include'
        });
        const data = await res.json();
        if (data.success) {
            showSuccess(status, 'Password changed successfully.');
            form.reset();
        } else {
            showError(status, data.message || 'Password change failed.');
        }
    } catch (err) {
        showError(status, 'Network error. Please try again.');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Update Password';
    }
    return false;
}

// ── Helpers ────────────────────────────────────────────────────
function showSuccess(el, msg) {
    el.style.display = 'block';
    el.style.background = 'rgba(22,163,74,.15)';
    el.style.color = '#4ade80';
    el.style.border = '1px solid rgba(22,163,74,.25)';
    el.textContent = '\u2713 ' + msg;
    setTimeout(() => { el.style.display = 'none'; }, 5000);
}

function showError(el, msg) {
    el.style.display = 'block';
    el.style.background = 'rgba(220,38,38,.15)';
    el.style.color = '#f87171';
    el.style.border = '1px solid rgba(220,38,38,.25)';
    el.textContent = '\u2717 ' + msg;
}
</script>
</body>
</html>
