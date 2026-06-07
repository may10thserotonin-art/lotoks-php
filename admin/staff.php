<?php
/**
 * admin/staff.php
 * Admin Staff Management — super_admin only.
 * CRUD for admin accounts: create, edit, delete, suspend admin users.
 */
$page_title = 'Staff Management';

// ── Handle POST actions BEFORE any output ───────────────────────
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once dirname(__DIR__, 2) . '/includes/auth.php';
    require_once dirname(__DIR__, 2) . '/db/connect.php';
    require_super_admin();
    $admin = get_admin();
    $db = getDb();

    if (!csrf_verify()) {
        flash('error', 'Security token mismatch — please try again.');
        redirect('/admin/staff.php');
    }
    $action   = $_POST['action'] ?? '';
    $staffId  = (int)($_POST['staff_id'] ?? 0);
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $role     = $_POST['role'] ?? 'admin';

    try {
        if ($action === 'add' || $action === 'edit') {
            if (empty($name) || empty($email)) {
                throw new Exception('Name and email are required.');
            }

            if ($action === 'add') {
                if (empty($password) || strlen($password) < 8) {
                    throw new Exception('Password must be at least 8 characters for new staff.');
                }
                // Check duplicate email
                $check = $db->prepare("SELECT COUNT(*) FROM admins WHERE email = ?");
                $check->execute([$email]);
                if ($check->fetchColumn() > 0) {
                    throw new Exception('An admin with this email already exists.');
                }

                $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
                $stmt = $db->prepare("INSERT INTO admins (name, email, password_hash, role, verified) VALUES (?, ?, ?, ?, 1)");
                $stmt->execute([$name, $email, $hash, $role]);
                log_activity(null, (int)$admin['id'], 'staff_created', "Created admin account: {$name} ({$email})");
                flash('success', "Staff account '{$name}' created successfully.");
            } else {
                // Edit existing
                if ($password) {
                    if (strlen($password) < 8) {
                        throw new Exception('Password must be at least 8 characters.');
                    }
                    $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);
                    $stmt = $db->prepare("UPDATE admins SET name = ?, email = ?, password_hash = ?, role = ? WHERE id = ?");
                    $stmt->execute([$name, $email, $hash, $role, $staffId]);
                } else {
                    $stmt = $db->prepare("UPDATE admins SET name = ?, email = ?, role = ? WHERE id = ?");
                    $stmt->execute([$name, $email, $role, $staffId]);
                }
                log_activity(null, (int)$admin['id'], 'staff_updated', "Updated admin account: {$name} (ID #{$staffId})");
                flash('success', "Staff account '{$name}' updated successfully.");
            }
            redirect('/admin/staff.php');
        }

        if ($action === 'delete') {
            if (!$staffId || $staffId === (int)$admin['id']) {
                throw new Exception('You cannot delete your own account.');
            }

            $stmt = $db->prepare("DELETE FROM admins WHERE id = ?");
            $stmt->execute([$staffId]);
            log_activity(null, (int)$admin['id'], 'staff_deleted', "Deleted admin account ID #{$staffId}");
            flash('success', 'Staff account deleted.');
            redirect('/admin/staff.php');
        }
    } catch (Exception $e) {
        $message = '<div style="background:var(--danger-bg);color:var(--danger);padding:1rem;border-radius:0.5rem;margin-bottom:1.5rem;font-weight:600;">' . htmlspecialchars($e->getMessage()) . '</div>';
    }
}

// Normal page rendering (GET or failed POST)
require_once __DIR__ . '/includes/header.php';
$db = getDb();

// ── Fetch all staff ────────────────────────────────────────────
$staffList = $db->query("SELECT id, name, email, role, verified, created_at FROM admins ORDER BY role DESC, name ASC")->fetchAll();
?>

<div class="staff-header" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:1rem;margin-bottom:1.5rem">
    <p style="color:var(--text-light);font-size:0.875rem;">
        Manage admin staff accounts. You are logged in as <strong><?= htmlspecialchars($admin['name']) ?></strong> (<?= htmlspecialchars($admin['role']) ?>).
    </p>
    <button class="btn btn-primary" onclick="openStaffModal()">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:0.25rem"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Staff
    </button>
</div>

<?= $message ?>

<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Joined</th>
                    <th style="text-align:center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($staffList)): ?>
                <tr><td colspan="7" style="text-align:center;padding:3rem;color:var(--text-light);">No staff accounts found.</td></tr>
                <?php else: foreach ($staffList as $s): ?>
                <tr>
                    <td style="color:var(--text-light);font-size:0.75rem">#<?= (int)$s['id'] ?></td>
                    <td style="font-weight:600"><?= htmlspecialchars($s['name']) ?></td>
                    <td style="color:var(--text-light);font-size:0.85rem"><?= htmlspecialchars($s['email']) ?></td>
                    <td>
                        <?php if ($s['role'] === 'super_admin'): ?>
                        <span class="badge badge-yellow" style="text-transform:uppercase;font-size:0.65rem">Super Admin</span>
                        <?php else: ?>
                        <span class="badge badge-blue" style="text-transform:uppercase;font-size:0.65rem">Admin</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($s['verified']): ?>
                        <span class="badge badge-green">Active</span>
                        <?php else: ?>
                        <span class="badge badge-red">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td style="color:var(--text-light);font-size:0.75rem;white-space:nowrap">
                        <?= date('M j, Y', strtotime($s['created_at'])) ?>
                    </td>
                    <td style="text-align:center">
                        <div class="staff-list-actions" style="display:flex;gap:0.375rem;justify-content:center">
                            <button class="btn btn-outline" style="font-size:0.7rem;padding:0.25rem 0.5rem" onclick="editStaff(<?= (int)$s['id'] ?>, '<?= htmlspecialchars(addslashes($s['name'])) ?>', '<?= htmlspecialchars(addslashes($s['email'])) ?>', '<?= htmlspecialchars($s['role']) ?>')">
                                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Edit
                            </button>
                            <?php if ((int)$s['id'] !== (int)$admin['id']): ?>
                            <form method="POST" style="display:inline" onsubmit="return confirm('Delete staff account <?= htmlspecialchars(addslashes($s['name'])) ?>? This cannot be undone.')">
                                <?= csrf_field() ?>
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="staff_id" value="<?= (int)$s['id'] ?>">
                                <button type="submit" class="btn btn-danger" style="font-size:0.7rem;padding:0.25rem 0.5rem">
                                    <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                    Delete
                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Staff Modal -->
<div class="modal-overlay" id="staff-modal">
    <div class="modal" style="max-width:480px">
        <div class="modal-header">
            <h2 class="modal-title" id="staff-modal-title">Add Staff Account</h2>
            <button class="modal-close" onclick="closeStaffModal()">&times;</button>
        </div>
        <form method="POST" class="modal-body" id="staff-form" style="padding:1.5rem">
            <?= csrf_field() ?>
            <input type="hidden" name="action" id="staff-action" value="add">
            <input type="hidden" name="staff_id" id="staff-id" value="0">

            <div style="margin-bottom:1rem">
                <label style="display:block;font-size:0.75rem;font-weight:700;color:var(--text-light);text-transform:uppercase;margin-bottom:0.375rem">Full Name</label>
                <input type="text" name="name" id="staff-name" required
                       style="width:100%;padding:0.65rem;border:1px solid var(--border);border-radius:0.5rem;font-family:inherit;font-size:0.875rem;box-sizing:border-box">
            </div>

            <div style="margin-bottom:1rem">
                <label style="display:block;font-size:0.75rem;font-weight:700;color:var(--text-light);text-transform:uppercase;margin-bottom:0.375rem">Email Address</label>
                <input type="email" name="email" id="staff-email" required
                       style="width:100%;padding:0.65rem;border:1px solid var(--border);border-radius:0.5rem;font-family:inherit;font-size:0.875rem;box-sizing:border-box">
            </div>

            <div style="margin-bottom:1rem" id="staff-password-group">
                <label style="display:block;font-size:0.75rem;font-weight:700;color:var(--text-light);text-transform:uppercase;margin-bottom:0.375rem">
                    Password <span id="password-label-hint" style="font-weight:400;text-transform:none">(min 8 characters)</span>
                </label>
                <input type="password" name="password" id="staff-password" minlength="8"
                       style="width:100%;padding:0.65rem;border:1px solid var(--border);border-radius:0.5rem;font-family:inherit;font-size:0.875rem;box-sizing:border-box">
            </div>

            <div style="margin-bottom:1.5rem">
                <label style="display:block;font-size:0.75rem;font-weight:700;color:var(--text-light);text-transform:uppercase;margin-bottom:0.375rem">Role</label>
                <select name="role" id="staff-role"
                        style="width:100%;padding:0.65rem;border:1px solid var(--border);border-radius:0.5rem;font-family:inherit;font-size:0.875rem;background:white;box-sizing:border-box">
                    <option value="admin">Admin</option>
                    <option value="super_admin">Super Admin</option>
                </select>
            </div>

            <div class="staff-form-actions" style="display:flex;gap:0.75rem;justify-content:flex-end">
                <button type="button" class="btn btn-outline" onclick="closeStaffModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="staff-submit-btn">Create Staff</button>
            </div>
        </form>
    </div>
</div>

<script>
function openStaffModal() {
    document.getElementById('staff-action').value = 'add';
    document.getElementById('staff-id').value = '0';
    document.getElementById('staff-modal-title').textContent = 'Add Staff Account';
    document.getElementById('staff-submit-btn').textContent = 'Create Staff';
    document.getElementById('staff-name').value = '';
    document.getElementById('staff-email').value = '';
    document.getElementById('staff-password').value = '';
    document.getElementById('staff-password').required = true;
    document.getElementById('password-label-hint').textContent = '(min 8 characters, required)';
    document.getElementById('staff-role').value = 'admin';
    openModal('staff-modal');
}

function editStaff(id, name, email, role) {
    document.getElementById('staff-action').value = 'edit';
    document.getElementById('staff-id').value = id;
    document.getElementById('staff-modal-title').textContent = 'Edit Staff Account';
    document.getElementById('staff-submit-btn').textContent = 'Save Changes';
    document.getElementById('staff-name').value = name;
    document.getElementById('staff-email').value = email;
    document.getElementById('staff-password').value = '';
    document.getElementById('staff-password').required = false;
    document.getElementById('password-label-hint').textContent = '(leave blank to keep current)';
    document.getElementById('staff-role').value = role;
    openModal('staff-modal');
}

function closeStaffModal() {
    closeModal('staff-modal');
}

function openModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.remove('active');
    document.body.style.overflow = '';
}

// Close on overlay click
document.addEventListener('DOMContentLoaded', function() {
    const modals = document.querySelectorAll('.modal-overlay');
    modals.forEach(m => {
        m.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });
    // ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active').forEach(m => {
                m.classList.remove('active');
                document.body.style.overflow = '';
            });
        }
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
