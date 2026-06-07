<?php
/**
 * profile.php
 * User Profile page — view and edit account details, change password.
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/db/connect.php';
requireUserAuth('/login.php');

$current_page = 'profile';
$user = getCurrentUser();
$userId = (int)($user['id'] ?? 0);

$db = getDb();
$stmt = $db->prepare("SELECT id, name, email, country, created_at FROM users WHERE id = ?");
$stmt->execute([$userId]);
$profile = $stmt->fetch();

if (!$profile) {
    header('Location: ' . BASE . '/dashboard.php');
    exit;
}

$page_title       = 'My Profile — Lotoks';
$page_description = 'Manage your Lotoks account profile and password.';
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
            <div><h1 style="font-size:1rem;color:#fff;font-weight:700;margin:0;">My Profile</h1></div>
            <div></div>
        </header>

        <div class="portal-content" style="padding-top:1.5rem;padding-bottom:6rem">
            <!-- Breadcrumb -->
            <div style="margin-bottom:1.5rem;font-size:0.8rem">
                <a href="<?= BASE ?>/dashboard.php" style="color:rgba(255,255,255,.4);text-decoration:none">Dashboard</a>
                <span style="color:rgba(255,255,255,.2);margin:0 0.5rem">→</span>
                <span style="color:var(--color-gold);font-weight:600">My Profile</span>
            </div>

            <!-- Tab Navigation -->
            <div class="profile-tabs" style="display:flex;gap:0.5rem;margin-bottom:1.5rem;border-bottom:1px solid rgba(255,255,255,.1);padding-bottom:0.75rem">
                <button class="profile-tab active" data-tab="info" style="padding:0.5rem 1.25rem;border-radius:0.5rem;border:none;background:var(--color-gold);color:var(--color-navy);font-weight:700;font-size:0.8rem;cursor:pointer">
                    Profile Information
                </button>
                <button class="profile-tab" data-tab="password" style="padding:0.5rem 1.25rem;border-radius:0.5rem;border:none;background:rgba(255,255,255,.06);color:rgba(255,255,255,.6);font-weight:600;font-size:0.8rem;cursor:pointer;transition:all .2s">
                    Change Password
                </button>
            </div>

            <!-- Tab: Profile Information -->
            <div class="profile-panel active" id="panel-info">
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

                    <div id="profile-form-status" style="display:none;padding:0.75rem 1rem;border-radius:0.5rem;margin-bottom:1rem;font-weight:600;font-size:0.85rem"></div>

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

            <!-- Tab: Change Password -->
            <div class="profile-panel" id="panel-password" style="display:none">
                <div style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:1rem;padding:1.5rem">
                    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.5rem">
                        <div style="padding:0.75rem;border-radius:9999px;background:rgba(201,164,75,.2);color:var(--color-gold)">
                            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        </div>
                        <div>
                            <h2 style="font-size:1.25rem;font-weight:700;color:#fff;margin:0">Change Password</h2>
                            <p style="font-size:0.8rem;color:rgba(255,255,255,.4);margin:0.25rem 0 0 0">Update your account password</p>
                        </div>
                    </div>

                    <div id="password-form-status" style="display:none;padding:0.75rem 1rem;border-radius:0.5rem;margin-bottom:1rem;font-weight:600;font-size:0.85rem"></div>

                    <form id="password-form" onsubmit="return changePassword(event)">
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
                            <button type="submit" id="password-save-btn"
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
.profile-tabs { overflow-x: auto; white-space: nowrap; }
.profile-tab { transition: all 0.2s ease; }
.profile-tab:hover:not(.active) { background: rgba(255,255,255,.1); color: #fff; }
</style>

<?php include __DIR__ . '/includes/scripts.php'; ?>
<script>
const CSRF = '<?= htmlspecialchars(generateCsrfToken()) ?>';
const BASE = window.LOTOKS_CONFIG?.BASE || '';

// ── Tab Switching ──────────────────────────────────────────────
document.querySelectorAll('.profile-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.profile-tab').forEach(t => {
            t.style.background = 'rgba(255,255,255,.06)';
            t.style.color = 'rgba(255,255,255,.6)';
        });
        this.style.background = 'var(--color-gold)';
        this.style.color = 'var(--color-navy)';

        document.querySelectorAll('.profile-panel').forEach(p => p.style.display = 'none');
        document.getElementById('panel-' + this.dataset.tab).style.display = 'block';
    });
});

// ── Save Profile ───────────────────────────────────────────────
async function saveProfile(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('profile-save-btn');
    const status = document.getElementById('profile-form-status');

    btn.disabled = true;
    btn.textContent = 'Saving...';
    status.style.display = 'none';

    const fd = new FormData(form);
    fd.set('action', 'update');

    try {
        const res = await fetch(BASE + '/api/user/profile.php', {
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

// ── Change Password ────────────────────────────────────────────
async function changePassword(e) {
    e.preventDefault();
    const form = e.target;
    const btn = document.getElementById('password-save-btn');
    const status = document.getElementById('password-form-status');

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
    fd.set('action', 'password');

    try {
        const res = await fetch(BASE + '/api/user/profile.php', {
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
