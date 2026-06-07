<?php
/**
 * application-detail.php
 * User Application Detail page — view full submission, status timeline,
 * uploaded documents (with tags), admin notes, and upload additional docs if requested.
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/db/connect.php';
requireUserAuth('/login.php');

$current_page = 'dashboard';
$user = getCurrentUser();
$userId = (int)($user['id'] ?? 0);

$appId = (int)($_GET['id'] ?? 0);
if (!$appId) {
    header('Location: ' . BASE . '/dashboard.php');
    exit;
}

$db = getDb();

// Fetch application — ensure it belongs to current user
$stmt = $db->prepare("SELECT * FROM applications WHERE id = ? AND user_id = ?");
$stmt->execute([$appId, $userId]);
$app = $stmt->fetch();

if (!$app) {
    header('Location: ' . BASE . '/dashboard.php');
    exit;
}

// Decode JSON fields
$serviceTypes = json_decode($app['service_types'], true) ?: [];
$personalInfo = json_decode($app['personal_info'], true) ?: [];
$answers      = json_decode($app['answers'], true) ?: [];
$appDocs      = json_decode($app['documents'], true) ?: [];
$appReqs      = json_decode($app['requirements'], true) ?: [];

// Fetch user's documents that are linked to this application (by user_id)
// and any docs with matching category tags
$docStmt = $db->prepare("SELECT * FROM user_documents WHERE user_id = ? ORDER BY created_at DESC");
$docStmt->execute([$userId]);
$userDocs = $docStmt->fetchAll();

// Fetch activity log for this application
$logStmt = $db->prepare("
    SELECT a.*, ad.name as admin_name
    FROM activity_log a
    LEFT JOIN admins ad ON a.admin_id = ad.id
    WHERE a.description LIKE ?
    ORDER BY a.created_at ASC
");
$logStmt->execute(['%#' . $appId . '%']);
$appLogs = $logStmt->fetchAll();

// Build status timeline
$statusTimeline = [
    ['status' => 'submitted',  'label' => 'Application Submitted',  'icon' => 'file',       'color' => '#2563eb'],
    ['status' => 'under_review','label' => 'Under Review',           'icon' => 'search',     'color' => '#ca8a04'],
    ['status' => 'more_info',  'label' => 'Additional Info Needed', 'icon' => 'info',       'color' => '#ca8a04'],
    ['status' => 'approved',   'label' => 'Approved',               'icon' => 'check',      'color' => '#16a34a'],
    ['status' => 'rejected',   'label' => 'Not Approved',           'icon' => 'x',          'color' => '#dc2626'],
];
$currentStatus = $app['status'];

$page_title = 'Application #' . $appId . ' — Lotoks';
$page_description = 'View your application details, status, and documents.';
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
            <div><h1 style="font-size:1rem;color:#fff;font-weight:700;margin:0;">Application #<?= $appId ?></h1></div>
            <div></div>
        </header>

        <div class="portal-content" style="padding-top:1.5rem;padding-bottom:6rem">
            <!-- Breadcrumb -->
            <div style="margin-bottom:1.5rem;font-size:0.8rem">
                <a href="<?= BASE ?>/dashboard.php" style="color:rgba(255,255,255,.4);text-decoration:none">Dashboard</a>
                <span style="color:rgba(255,255,255,.2);margin:0 0.5rem">→</span>
                <span style="color:var(--color-gold);font-weight:600">Application #<?= $appId ?></span>
            </div>

            <!-- Header Card -->
            <div class="app-detail-card" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:1rem;padding:1.5rem;margin-bottom:1.5rem">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:1rem">
                    <div>
                        <h2 style="font-size:1.5rem;font-weight:700;color:#fff;margin-bottom:0.5rem">
                            <?= htmlspecialchars(ucwords(str_replace('_', ' ', $app['sponsorship_type'] ?? ''))) ?> Application
                        </h2>
                        <p style="color:rgba(255,255,255,.5);font-size:0.85rem">
                            Submitted <?= date('F j, Y \a\t g:i A', strtotime($app['created_at'])) ?>
                        </p>
                    </div>
                    <div>
                        <?php
                        $sc = match($app['status']) {
                            'submitted'   => ['#2563eb', 'rgba(59,130,246,0.2)'],
                            'under_review'=> ['#ca8a04', 'rgba(234,179,8,0.2)'],
                            'more_info'   => ['#ca8a04', 'rgba(234,179,8,0.2)'],
                            'approved'    => ['#16a34a', 'rgba(22,163,74,0.2)'],
                            'rejected'    => ['#dc2626', 'rgba(220,38,38,0.2)'],
                            default       => ['#6b7280', 'rgba(107,114,128,0.2)'],
                        };
                        ?>
                        <span style="display:inline-block;padding:0.35rem 1rem;border-radius:9999px;font-size:0.75rem;font-weight:700;background:<?= $sc[1] ?>;color:<?= $sc[0] ?>;text-transform:uppercase">
                            <?= str_replace('_', ' ', $app['status']) ?>
                        </span>
                    </div>
                </div>
                <?php if (!empty($app['admin_notes'])): ?>
                <div style="margin-top:1rem;padding:1rem;background:rgba(201,164,75,.1);border-radius:0.75rem;border:1px solid rgba(201,164,75,.2)">
                    <p style="font-size:0.7rem;font-weight:700;color:var(--color-gold);text-transform:uppercase;margin-bottom:0.25rem">Admin Note</p>
                    <p style="font-size:0.85rem;color:rgba(255,255,255,.8)"><?= nl2br(htmlspecialchars($app['admin_notes'])) ?></p>
                </div>
                <?php endif; ?>
            </div>

            <!-- Status Timeline -->
            <div class="app-detail-card" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:1rem;padding:1.5rem;margin-bottom:1.5rem">
                <h3 style="font-size:1rem;font-weight:700;color:#fff;margin-bottom:1rem">Status Timeline</h3>
                <div class="timeline">
                    <?php
                    $foundCurrent = false;
                    $passedCurrent = false;
                    foreach ($statusTimeline as $tl):
                        $isCurrent = $tl['status'] === $currentStatus;
                        $isPast = false;
                        // Determine if past: iterate statuses in order
                        $order = ['submitted', 'under_review', 'more_info', 'approved', 'rejected'];
                        $currentIdx = array_search($currentStatus, $order);
                        $tlIdx = array_search($tl['status'], $order);
                        $isPast = $tlIdx < $currentIdx;
                        // Rejected or approved are terminal — if current is not one of those, hide future states
                        $isFuture = $tlIdx > $currentIdx && !in_array($currentStatus, ['approved', 'rejected']);
                        $isSkipped = $currentStatus === 'approved' && ($tl['status'] === 'rejected');
                        $isSkipped = $isSkipped || ($currentStatus === 'rejected' && ($tl['status'] === 'approved' || $tl['status'] === 'more_info'));

                        if ($isCurrent || $isPast) {
                            $foundCurrent = true;
                        }
                        if ($tl['status'] === 'more_info' && $currentStatus !== 'more_info' && $currentStatus !== 'rejected') {
                            // Hide more_info if not the current status and not rejected
                            if ($currentStatus !== 'more_info' && $currentStatus !== 'approved' && $currentStatus !== 'rejected') continue;
                        }
                        if ($isSkipped) continue;
                        if ($isFuture && $currentStatus !== 'approved' && $currentStatus !== 'rejected') continue;
                    ?>
                    <div class="timeline-item <?= $isCurrent ? 'current' : ($isPast ? 'past' : '') ?>">
                        <div class="timeline-dot" style="background:<?= $isPast || $isCurrent ? $tl['color'] : 'rgba(255,255,255,.15)' ?>">
                            <?php if ($isPast): ?>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                            <?php elseif ($isCurrent): ?>
                            <span style="color:#fff;font-weight:800;font-size:0.7rem">●</span>
                            <?php else: ?>
                            <span style="color:rgba(255,255,255,.3);font-weight:800;font-size:0.7rem">○</span>
                            <?php endif; ?>
                        </div>
                        <div class="timeline-content">
                            <p class="timeline-label" style="color:<?= $isPast || $isCurrent ? '#fff' : 'rgba(255,255,255,.3)' ?>">
                                <?= $tl['label'] ?>
                            </p>
                            <?php if ($isCurrent || $isPast): ?>
                            <p class="timeline-date" style="color:rgba(255,255,255,.4);font-size:0.75rem">
                                <?php
                                // Find matching log entry
                                $logDate = '';
                                foreach ($appLogs as $log) {
                                    if (str_contains(strtolower($log['description']), strtolower(str_replace('_', ' ', $tl['status']))) ||
                                        str_contains(strtolower($log['action']), strtolower($tl['status'])) ||
                                        (str_contains(strtolower($log['action']), 'submitted') && $tl['status'] === 'submitted')) {
                                        $logDate = date('M j, Y', strtotime($log['created_at']));
                                        break;
                                    }
                                }
                                // Fallback: use created_at for submitted timeline, use log or reviewed_at for milestones
                                if ($tl['status'] === 'submitted') {
                                    echo date('M j, Y', strtotime($app['created_at']));
                                } elseif ($logDate) {
                                    echo $logDate;
                                } elseif ($isCurrent && $app['reviewed_at']) {
                                    echo date('M j, Y', strtotime($app['reviewed_at']));
                                } else {
                                    echo '—';
                                }
                                ?>
                            </p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Personal Info -->
            <div class="app-detail-card" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:1rem;padding:1.5rem;margin-bottom:1.5rem">
                <h3 style="font-size:1rem;font-weight:700;color:#fff;margin-bottom:1rem">Personal Information</h3>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:1rem">
                    <?php foreach ($personalInfo as $key => $val): ?>
                    <div>
                        <p style="font-size:0.65rem;font-weight:700;color:rgba(255,255,255,.4);text-transform:uppercase;margin-bottom:0.25rem">
                            <?= htmlspecialchars(ucwords(preg_replace('/([a-z])([A-Z])/', '$1 $2', $key))) ?>
                        </p>
                        <p style="font-size:0.875rem;color:#fff;font-weight:500"><?= htmlspecialchars($val ?: '—') ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Interview Answers -->
            <?php if (!empty($answers)): ?>
            <div class="app-detail-card" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:1rem;padding:1.5rem;margin-bottom:1.5rem">
                <h3 style="font-size:1rem;font-weight:700;color:#fff;margin-bottom:1rem">Questionnaire Answers</h3>
                <div style="display:flex;flex-direction:column;gap:0.75rem">
                    <?php foreach ($answers as $q => $a): ?>
                    <div style="padding:0.75rem;background:rgba(255,255,255,.03);border-radius:0.5rem">
                        <p style="font-size:0.7rem;font-weight:600;color:rgba(255,255,255,.4);margin-bottom:0.25rem"><?= htmlspecialchars($q) ?></p>
                        <p style="font-size:0.875rem;color:#fff"><?= htmlspecialchars($a ?: '—') ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Uploaded Documents (with tags) -->
            <div class="app-detail-card" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:1rem;padding:1.5rem;margin-bottom:1.5rem">
                <h3 style="font-size:1rem;font-weight:700;color:#fff;margin-bottom:1rem">
                    Uploaded Documents
                    <span style="font-size:0.75rem;font-weight:400;color:rgba(255,255,255,.4)">(<?= count($userDocs) ?>)</span>
                </h3>

                <?php if (empty($userDocs)): ?>
                <div style="text-align:center;padding:2rem;color:rgba(255,255,255,.4)">
                    <p>No documents uploaded yet.</p>
                </div>
                <?php else: ?>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:0.75rem">
                    <?php foreach ($userDocs as $doc): ?>
                    <div class="user-doc-card" style="background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.1);border-radius:0.75rem;padding:1rem">
                        <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.5rem">
                            <div style="padding:0.5rem;border-radius:0.5rem;background:rgba(201,164,75,.15);color:var(--color-gold);flex-shrink:0">
                                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            </div>
                            <div style="flex:1;min-width:0">
                                <p style="font-size:0.85rem;font-weight:600;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                    <?= htmlspecialchars($doc['name'] ?: $doc['filename']) ?>
                                </p>
                                <p style="font-size:0.7rem;color:rgba(255,255,255,.4)">
                                    <?= !empty($doc['filesize']) ? round($doc['filesize'] / 1024, 1) . ' KB' : '' ?>
                                    <?php if (!empty($doc['created_at'])): ?>
                                    • <?= date('M j, Y', strtotime($doc['created_at'])) ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                        <!-- Document tag/category badge -->
                        <div style="display:flex;align-items:center;gap:0.5rem;flex-wrap:wrap">
                            <?php if (!empty($doc['category'])): ?>
                            <span style="padding:0.15rem 0.5rem;border-radius:9999px;font-size:0.65rem;font-weight:600;background:rgba(59,130,246,.15);color:#60a5fa">
                                <?= htmlspecialchars($doc['category']) ?>
                            </span>
                            <?php else: ?>
                            <span style="padding:0.15rem 0.5rem;border-radius:9999px;font-size:0.65rem;font-weight:600;background:rgba(255,255,255,.05);color:rgba(255,255,255,.3)">
                                Uncategorized
                            </span>
                            <?php endif; ?>

                            <?php
                            $v = (int)($doc['verified'] ?? 0);
                            $vLabel = match($v) { 1 => 'Verified', -1 => 'Rejected', default => 'Pending' };
                            $vColor = match($v) { 1 => '#16a34a', -1 => '#dc2626', default => '#ca8a04' };
                            $vBg   = match($v) { 1 => 'rgba(22,163,74,.15)', -1 => 'rgba(220,38,38,.15)', default => 'rgba(234,179,8,.15)' };
                            ?>
                            <span style="padding:0.15rem 0.5rem;border-radius:9999px;font-size:0.65rem;font-weight:600;background:<?= $vBg ?>;color:<?= $vColor ?>">
                                <?= $vLabel ?>
                            </span>
                        </div>
                        <div style="margin-top:0.5rem">
                            <a href="<?= BASE ?>/<?= htmlspecialchars($doc['filepath']) ?>" target="_blank"
                               style="display:inline-flex;align-items:center;gap:0.375rem;padding:0.35rem 0.75rem;border-radius:0.5rem;font-size:0.7rem;font-weight:600;background:rgba(255,255,255,.08);color:#fff;text-decoration:none">
                                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                View Document
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Upload Additional Documents (shown when status is more_info) -->
            <?php if ($app['status'] === 'more_info'): ?>
            <div class="app-detail-card" style="background:rgba(201,164,75,.08);border:1px solid rgba(201,164,75,.2);border-radius:1rem;padding:1.5rem;margin-bottom:1.5rem">
                <h3 style="font-size:1rem;font-weight:700;color:var(--color-gold);margin-bottom:0.5rem">Additional Documents Requested</h3>
                <p style="font-size:0.85rem;color:rgba(255,255,255,.6);margin-bottom:1rem">
                    The admin has requested more information. Please upload the requested documents below.
                </p>
                <div id="additional-upload-area">
                    <div class="upload-additional-zone" style="padding:1.5rem;border:2px dashed rgba(201,164,75,.3);border-radius:0.75rem;text-align:center">
                        <p style="font-size:0.85rem;color:rgba(255,255,255,.5);margin-bottom:0.75rem">
                            Select a file to upload for this application
                        </p>
                        <input type="file" id="additional-file-input" style="display:none" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" onchange="uploadAdditionalDoc(<?= $appId ?>, this)">
                        <button onclick="document.getElementById('additional-file-input').click()"
                                style="padding:0.6rem 1.5rem;border-radius:0.5rem;background:var(--color-gold);color:var(--color-navy);font-weight:700;font-size:0.8rem;border:none;cursor:pointer">
                            Choose File
                        </button>
                        <p style="font-size:0.7rem;color:rgba(255,255,255,.3);margin-top:0.5rem">PDF, JPG, PNG, DOC — Max 10MB</p>
                    </div>
                    <div id="additional-progress" style="margin-top:1rem;display:none"></div>
                    <div id="additional-success-msg" style="margin-top:0.75rem;display:none;padding:0.75rem 1rem;background:rgba(22,163,74,.15);color:#4ade80;border-radius:0.5rem;font-weight:600;font-size:0.85rem">
                        ✓ File uploaded successfully. The admin will review it shortly.
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Activity Log for this Application -->
            <?php if (!empty($appLogs)): ?>
            <div class="app-detail-card" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:1rem;padding:1.5rem;margin-bottom:1.5rem">
                <h3 style="font-size:1rem;font-weight:700;color:#fff;margin-bottom:1rem">Application Activity</h3>
                <div style="display:flex;flex-direction:column;gap:0.75rem">
                    <?php foreach ($appLogs as $log): ?>
                    <div style="display:flex;gap:0.75rem;align-items:flex-start">
                        <div style="width:8px;height:8px;border-radius:50%;background:var(--color-gold);margin-top:0.35rem;flex-shrink:0"></div>
                        <div>
                            <p style="font-size:0.8rem;color:rgba(255,255,255,.7)"><?= htmlspecialchars($log['description']) ?></p>
                            <p style="font-size:0.65rem;color:rgba(255,255,255,.3)">
                                <?= date('M j, Y \a\t g:i A', strtotime($log['created_at'])) ?>
                                <?php if (!empty($log['admin_name'])): ?>
                                • by <?= htmlspecialchars($log['admin_name']) ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

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
.timeline {
  position: relative; padding-left: 1.5rem;
}
.timeline::before {
  content: ''; position: absolute; left: 0.5rem; top: 0.25rem; bottom: 0.25rem;
  width: 2px; background: rgba(255,255,255,.1);
}
.timeline-item {
  display: flex; gap: 0.75rem; padding-bottom: 1.25rem; position: relative;
}
.timeline-item:last-child { padding-bottom: 0; }
.timeline-dot {
  width: 1.25rem; height: 1.25rem; border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; margin-left: -1.5rem; z-index: 1;
  transition: all 0.3s;
}
.timeline-item.current .timeline-dot {
  box-shadow: 0 0 0 4px rgba(201,164,75,.3);
}
.timeline-content { padding-top: 0.1rem; }
.timeline-label { font-size: 0.875rem; font-weight: 600; margin-bottom: 0.1rem; }
.timeline-date { font-size: 0.75rem; }

.app-detail-card {
  animation: fadeUp 0.4s ease forwards;
  opacity: 0; transform: translateY(10px);
}
.app-detail-card:nth-child(1) { animation-delay: 0s; }
.app-detail-card:nth-child(2) { animation-delay: 0.1s; }
.app-detail-card:nth-child(3) { animation-delay: 0.15s; }

@keyframes fadeUp {
  to { opacity: 1; transform: translateY(0); }
}
</style>

<?php include __DIR__ . '/includes/scripts.php'; ?>
<script>
const CSRF = '<?= htmlspecialchars(generateCsrfToken()) ?>';
const BASE = window.LOTOKS_CONFIG?.BASE || '';

async function uploadAdditionalDoc(appId, input) {
    const file = input.files[0];
    if (!file) return;

    const progress = document.getElementById('additional-progress');
    const successMsg = document.getElementById('additional-success-msg');
    successMsg.style.display = 'none';

    // Check size (10MB)
    if (file.size > 10 * 1024 * 1024) {
        alert('File is too large. Maximum size is 10MB.');
        return;
    }

    progress.style.display = 'block';
    progress.innerHTML = `
        <div class="docs-list-item" style="opacity:.7;background:rgba(255,255,255,.03);border:1px solid rgba(255,255,255,.1);border-radius:0.5rem;padding:0.75rem">
            <div style="display:flex;align-items:center;gap:0.75rem">
                <div class="doc-icon" style="color:var(--color-gold)">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                </div>
                <div style="flex:1">
                    <p style="font-size:0.85rem;color:#fff;font-weight:500">${file.name}</p>
                    <div style="height:4px;background:rgba(255,255,255,.1);border-radius:2px;margin-top:0.5rem;overflow:hidden">
                        <div class="progress-fill" style="height:100%;width:100%;background:var(--color-gold);border-radius:2px;animation:progressPulse 1s ease infinite"></div>
                    </div>
                </div>
            </div>
        </div>`;

    const fd = new FormData();
    fd.append('file', file);
    fd.append('category', 'additional_request');
    fd.append('csrf_token', CSRF);
    fd.append('application_id', appId);

    try {
        const res = await fetch(`${BASE}/api/user/documents/upload.php`, {
            method: 'POST', body: fd, credentials: 'include'
        });
        const data = await res.json();
        progress.style.display = 'none';
        if (res.ok) {
            successMsg.style.display = 'block';
            input.value = '';
        } else {
            alert(data.message || 'Upload failed. Please try again.');
        }
    } catch(e) {
        progress.style.display = 'none';
        alert('Upload failed. Please check your connection and try again.');
    }
}

// Inject the progress pulse animation
const style = document.createElement('style');
style.textContent = '@keyframes progressPulse { 0%,100% { opacity:1 } 50% { opacity:0.5 } }';
document.head.appendChild(style);
</script>
</body>
</html>
