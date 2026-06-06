<?php
require_once __DIR__ . '/includes/auth.php';
requireUserAuth('/login.php');

$current_page = 'documents';
$user = getCurrentUser();

// Fetch documents from API
$docs = [];
$apiError = '';
try {
    $ch = curl_init(rtrim(getenv('API_BASE_URL') ?: 'http://localhost:3001/api', '/') . '/user/documents');
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => ['Cookie: ' . http_build_cookie()],
        CURLOPT_TIMEOUT        => 5,
    ]);
    $body = curl_exec($ch);
    if (!curl_errno($ch)) {
        $data = json_decode($body, true);
        $docs = $data['documents'] ?? [];
    }
    curl_close($ch);
} catch (Throwable $e) { $apiError = 'Could not load documents.'; }

function formatFileSize(int $bytes): string {
    if ($bytes === 0) return '0 B';
    $units = ['B','KB','MB','GB'];
    $i = (int)floor(log($bytes, 1024));
    return round($bytes / (1024 ** $i), 1) . ' ' . $units[$i];
}

$page_title       = 'My Documents — Lotoks';
$page_description = 'Upload and manage your sponsorship files and certificates on Lotoks.';
?>
<!DOCTYPE html>
<html lang="en">
<?php include __DIR__ . '/includes/head.php'; ?>
<body class="page-loaded" style="background-color:#0B1D3A">

<div class="portal-wrap">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <main class="portal-main">
        <div class="portal-content" style="padding-top:2rem">

            <!-- Header -->
            <header style="display:flex;flex-direction:column;gap:1.5rem;margin-bottom:2rem" id="docs-header">
                <div style="display:flex;flex-direction:column;gap:0.5rem">
                    <h1 style="font-size:1.875rem;font-weight:700;color:#fff;margin:0">My Documents</h1>
                    <p style="font-size:.875rem;color:rgba(255,255,255,.5);font-weight:500;margin:0">Access and manage your sponsorship files and certificates.</p>
                </div>
                <div>
                    <input type="file" id="file-input" class="hidden" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" onchange="handleFileUpload(this)">
                    <button onclick="document.getElementById('file-input').click()" id="upload-btn"
                        style="display:inline-flex;align-items:center;gap:.5rem;padding:.75rem 1.5rem;border-radius:.75rem;background:var(--color-gold);color:var(--color-navy);font-weight:700;font-size:.75rem;border:none;cursor:pointer;box-shadow:0 4px 16px rgba(201,164,75,.3);">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                        <span id="upload-btn-text">Upload New File</span>
                    </button>
                </div>
            </header>

            <!-- Error alert -->
            <?php if ($apiError): ?>
            <div class="alert alert-error"><?= htmlspecialchars($apiError) ?></div>
            <?php endif; ?>

            <!-- Upload Error (JS-rendered) -->
            <div id="upload-error" class="alert alert-error" style="display:none;margin-bottom:1rem"></div>

            <!-- Document List -->
            <div id="docs-list">
                <?php if (empty($docs)): ?>
                <div class="dash-empty" id="docs-empty">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    <p>No documents yet</p>
                    <small>Upload your first document to get started</small>
                </div>
                <?php else: ?>
                <div class="space-y-3" id="docs-items">
                    <?php foreach ($docs as $doc): ?>
                    <div class="docs-list-item fade-up" id="doc-<?= (int)$doc['id'] ?>">
                        <div style="display:flex;align-items:center;gap:1rem;flex:1;min-width:0">
                            <div class="doc-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                            </div>
                            <div style="min-width:0">
                                <p class="doc-name"><?= htmlspecialchars($doc['name'] ?? 'Untitled') ?></p>
                                <div class="doc-meta">
                                    <span class="doc-size"><?= formatFileSize((int)($doc['filesize'] ?? 0)) ?></span>
                                    <span style="width:4px;height:4px;border-radius:50%;background:rgba(255,255,255,.2)"></span>
                                    <span class="doc-date"><?= !empty($doc['created_at']) ? date('M j, Y', strtotime($doc['created_at'])) : '' ?></span>
                                    <?php if (!empty($doc['verified'])): ?>
                                    <span style="width:4px;height:4px;border-radius:50%;background:rgba(255,255,255,.2)"></span>
                                    <span class="doc-badge" style="display:flex;align-items:center;gap:.25rem">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        Verified
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <button class="delete-btn" onclick="deleteDoc(<?= (int)$doc['id'] ?>)" title="Delete">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Upload progress zone (JS-rendered) -->
            <div id="upload-progress-zone" style="margin-top:1rem"></div>

            <!-- Info Card -->
            <div style="padding:1.5rem;border-radius:.75rem;background:rgba(255,255,255,.05);border:1px solid rgba(255,255,255,.1);margin-top:2rem">
                <h6 style="font-weight:700;color:#fff;font-size:.875rem;margin-bottom:.25rem">Authenticated Documents</h6>
                <p style="font-size:.75rem;color:rgba(255,255,255,.4);line-height:1.6">
                    All documents generated on the Lotoks platform are cryptographically signed and can be verified by
                    scanning the QR code on the physical printout or using the unique verification ID.
                </p>
            </div>
        </div>
    </main>
</div>

<?php include __DIR__ . '/includes/scripts.php'; ?>
<script>
const CSRF_TOKEN = '<?= htmlspecialchars(generateCsrfToken()) ?>';

async function handleFileUpload(input) {
    const file = input.files[0];
    if (!file) return;

    // Show uploading state
    const btn = document.getElementById('upload-btn');
    const btnText = document.getElementById('upload-btn-text');
    btn.disabled = true;
    btnText.textContent = 'Uploading…';

    const errBox = document.getElementById('upload-error');
    errBox.style.display = 'none';

    // Show progress placeholder
    const progressZone = document.getElementById('upload-progress-zone');
    const progressId = 'prog_' + Date.now();
    progressZone.innerHTML = `<div id="${progressId}" class="docs-list-item" style="opacity:.7">
        <div style="display:flex;align-items:center;gap:1rem;flex:1">
            <div class="doc-icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg></div>
            <div style="flex:1">
                <p class="doc-name">${file.name}</p>
                <div class="upload-progress-bar"><div class="upload-progress-bar-fill"></div></div>
            </div>
        </div>
    </div>`;

    try {
        const fd = new FormData();
        fd.append('file', file);
        fd.append('category', '');
        fd.append('csrf_token', CSRF_TOKEN);

        const res = await fetch((window.LOTOKS_CONFIG?.API_BASE || '/api') + '/user/documents/upload', { method: 'POST', body: fd, credentials: 'include' });
        const data = await res.json();

        if (res.ok) {
            showToast('Document uploaded successfully', 'success');
            // Reload docs list
            window.location.reload();
        } else {
            throw new Error(data.message || 'Upload failed');
        }
    } catch(e) {
        errBox.textContent = e.message || 'Upload failed';
        errBox.style.display = 'flex';
        progressZone.innerHTML = '';
    } finally {
        btn.disabled = false;
        btnText.textContent = 'Upload New File';
        input.value = '';
    }
}

async function deleteDoc(id) {
    if (!confirm('Delete this document?')) return;
    try {
        const res = await fetch((window.LOTOKS_CONFIG?.API_BASE || '/api') + `/user/documents/${id}`, {
            method: 'DELETE',
            credentials: 'include',
            headers: { 'X-CSRF-Token': CSRF_TOKEN }
        });
        if (res.ok) {
            const el = document.getElementById('doc-' + id);
            if (el) {
                el.style.transition = 'opacity .3s, transform .3s';
                el.style.opacity = '0';
                el.style.transform = 'translateX(20px)';
                setTimeout(() => el.remove(), 300);
            }
            showToast('Document deleted', 'info');
        } else {
            const d = await res.json();
            showToast(d.message || 'Delete failed', 'error');
        }
    } catch(e) {
        showToast('Delete failed', 'error');
    }
}
</script>
</body>
</html>
