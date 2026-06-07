<?php
/**
 * admin/api/listing-actions.php
 * CRUD for opportunities/listings management.
 * Actions: create, update, soft_delete, restore, permanent_delete
 */
require_once dirname(__DIR__, 2) . '/includes/auth.php';
require_once dirname(__DIR__, 2) . '/db/connect.php';

require_admin_auth();

// CSRF verification
csrf_verify_or_fail();

$admin   = get_admin();
$adminId = $admin['id'];
$isSuper = is_super_admin();
$db      = getDb();

$action = $_POST['action'] ?? '';
$id     = (int)($_POST['id'] ?? 0);

switch ($action) {

    // ── Create listing ─────────────────────────────────────────
    case 'create':
        $title            = trim($_POST['title'] ?? '');
        $employer         = trim($_POST['employer'] ?? '');
        $description      = trim($_POST['description'] ?? '');
        $country          = trim($_POST['country'] ?? '');
        $sponsorship_type = trim($_POST['sponsorship_type'] ?? '');
        $salary_range     = trim($_POST['salary_range'] ?? '');
        $type             = trim($_POST['type'] ?? 'job');
        $requirements     = trim($_POST['requirements'] ?? '');
        $active           = isset($_POST['active']) ? 1 : 0;

        if (!$title) {
            flash('error', 'Title is required.');
            redirect('/admin/listings.php');
        }

        $stmt = $db->prepare("INSERT INTO listings (title, employer, description, country, sponsorship_type, salary_range, requirements, type, active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$title, $employer, $description, $country, $sponsorship_type, $salary_range, $requirements, $type, $active]);
        $lid = $db->lastInsertId();

        log_activity(null, $adminId, 'listing_created', "Admin created listing #{$lid}: {$title}");
        flash('success', "Listing '{$title}' has been created.");
        redirect('/admin/listings.php');
        break;

    // ── Update listing ─────────────────────────────────────────
    case 'update':
        if (!$id) { flash('error', 'Invalid listing ID.'); redirect('/admin/listings.php'); }

        $title            = trim($_POST['title'] ?? '');
        $employer         = trim($_POST['employer'] ?? '');
        $description      = trim($_POST['description'] ?? '');
        $country          = trim($_POST['country'] ?? '');
        $sponsorship_type = trim($_POST['sponsorship_type'] ?? '');
        $salary_range     = trim($_POST['salary_range'] ?? '');
        $type             = trim($_POST['type'] ?? 'job');
        $requirements     = trim($_POST['requirements'] ?? '');
        $active           = isset($_POST['active']) ? 1 : 0;

        if (!$title) { flash('error', 'Title is required.'); redirect('/admin/listings.php'); }

        $stmt = $db->prepare("UPDATE listings SET title=?, employer=?, description=?, country=?, sponsorship_type=?, salary_range=?, requirements=?, type=?, active=? WHERE id=?");
        $stmt->execute([$title, $employer, $description, $country, $sponsorship_type, $salary_range, $requirements, $type, $active, $id]);

        log_activity(null, $adminId, 'listing_updated', "Admin updated listing #{$id}: {$title}");
        flash('success', "Listing #{$id} has been updated.");
        redirect('/admin/listings.php');
        break;

    // ── Soft delete (move to trash) ────────────────────────────
    case 'soft_delete':
        if (!$id) { json_error('Invalid listing ID', 400); }
        $stmt = $db->prepare("UPDATE listings SET deleted_at = NOW() WHERE id = ?");
        $stmt->execute([$id]);

        $title = $db->prepare("SELECT title FROM listings WHERE id = ?");
        $title->execute([$id]);
        $t = $title->fetchColumn();

        log_activity(null, $adminId, 'listing_deleted', "Admin moved listing #{$id} ({$t}) to trash");
        json_ok(['message' => "Listing moved to trash."]);
        break;

    // ── Restore from trash ─────────────────────────────────────
    case 'restore':
        if (!$id) { json_error('Invalid listing ID', 400); }
        if (!$isSuper) { json_error('Only super admins can restore listings.', 403); }
        $stmt = $db->prepare("UPDATE listings SET deleted_at = NULL WHERE id = ?");
        $stmt->execute([$id]);

        $title = $db->prepare("SELECT title FROM listings WHERE id = ?");
        $title->execute([$id]);
        $t = $title->fetchColumn();

        log_activity(null, $adminId, 'listing_restored', "Super admin restored listing #{$id} ({$t}) from trash");
        json_ok(['message' => "Listing restored from trash."]);
        break;

    // ── Permanent delete (super admin only) ─────────────────────
    case 'permanent_delete':
        if (!$id) { json_error('Invalid listing ID', 400); }
        if (!$isSuper) { json_error('Only super admins can permanently delete listings.', 403); }
        $stmt = $db->prepare("DELETE FROM listings WHERE id = ?");
        $stmt->execute([$id]);

        log_activity(null, $adminId, 'listing_permanent_delete', "Super admin permanently deleted listing #{$id}");
        json_ok(['message' => "Listing permanently deleted."]);
        break;

    // ── AJAX: fetch single listing for editing ─────────────────
    case 'fetch':
        if (!$id) { json_error('Invalid listing ID', 400); }
        $stmt = $db->prepare("SELECT * FROM listings WHERE id = ?");
        $stmt->execute([$id]);
        $listing = $stmt->fetch();
        if (!$listing) { json_error('Listing not found', 404); }
        json_ok(['listing' => $listing]);
        break;

    default:
        flash('error', 'Unknown action.');
        redirect('/admin/listings.php');
}
