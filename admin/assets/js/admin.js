/**
 * admin.js — Lotoks Admin Panel
 * Sidebar toggle, modals, application viewer, and confirmation dialogs.
 */

document.addEventListener('DOMContentLoaded', () => {
    // ── Sidebar toggle (mobile) ─────────────────────────────────
    const toggleBtn = document.getElementById('sidebar-toggle');
    const sidebar   = document.querySelector('.admin-sidebar');
    const overlay   = document.getElementById('sidebar-overlay');

    if (toggleBtn && sidebar && overlay) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.add('active');
            overlay.classList.add('active');
        });
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });
    }

    // ── Modal close buttons ─────────────────────────────────────
    document.querySelectorAll('[data-close-modal]').forEach(btn => {
        btn.addEventListener('click', () => {
            const modal = btn.closest('.modal-overlay');
            if (modal) closeModal(modal.id);
        });
    });

    // ── Close modal on overlay click ────────────────────────────
    document.querySelectorAll('.modal-overlay').forEach(m => {
        m.addEventListener('click', (e) => {
            if (e.target === m) closeModal(m.id);
        });
    });

    // ── ESC key closes modal ────────────────────────────────────
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active').forEach(m => closeModal(m.id));
        }
    });
});


/* ── Modal helpers ───────────────────────────────────────────── */

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


/* ── Application detail viewer ───────────────────────────────── */

window.viewApplication = async function (id) {
    openModal('app-modal');
    const modalBody = document.getElementById('app-modal-body');
    if (!modalBody) return;

    // Show loading state
    modalBody.innerHTML = `
        <div style="text-align:center;padding:2.5rem 1rem;">
            <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--text-light)" stroke-width="1.5" style="margin-bottom:1rem;opacity:0.4">
                <circle cx="12" cy="12" r="10" stroke-dasharray="31.4 31.4" stroke-linecap="round">
                    <animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite"/>
                </circle>
            </svg>
            <p style="color:var(--text-light);font-size:0.875rem;">Loading application details…</p>
        </div>`;

    try {
        const base = window.LOTOKS_CONFIG?.BASE || '';
        const [appRes, reqRes] = await Promise.all([
            fetch(`${base}/admin/api/view-application.php?id=${id}`),
            fetch(`${base}/admin/api/get-requirements.php`)
        ]);
        const appData = await appRes.json();
        const reqData = await reqRes.json();

        if (appData.success) {
            renderAppModal(appData.application, reqData.requirements || []);
        } else {
            modalBody.innerHTML = `
                <div style="text-align:center;padding:2rem;">
                    <p style="color:var(--danger);font-weight:600;">Error: ${escHtml(appData.message)}</p>
                    <button class="btn btn-outline" style="margin-top:1rem;" onclick="closeModal('app-modal')">Close</button>
                </div>`;
        }
    } catch (err) {
        modalBody.innerHTML = `
            <div style="text-align:center;padding:2rem;">
                <p style="color:var(--danger);font-weight:600;">Network error loading application details.</p>
                <button class="btn btn-outline" style="margin-top:1rem;" onclick="closeModal('app-modal')">Close</button>
            </div>`;
    }
};

/* ── Render application detail modal ─────────────────────────── */

function renderAppModal(app, allRequirements) {
    const body = document.getElementById('app-modal-body');
    if (!body) return;

    const base    = window.LOTOKS_CONFIG?.BASE || '';
    const pi      = safeObject(app.personal_info);
    const answers = safeObject(app.answers);
    const docs    = Array.isArray(app.documents) ? app.documents : [];

    // ── Personal info rows ──────────────────────────────────────
    const piHtml = Object.keys(pi).length
        ? Object.entries(pi).map(([k, v]) =>
            `<div class="data-group"><span class="data-label">${escHtml(k)}</span><span class="data-value">${escHtml(v)}</span></div>`
          ).join('')
        : '<p style="color:var(--text-light);font-size:0.8rem;">No personal info provided.</p>';

    // ── Interview answers ───────────────────────────────────────
    const answersHtml = Object.keys(answers).length
        ? Object.entries(answers).map(([q, a]) =>
            `<div class="data-group"><span class="data-label">${escHtml(q)}</span><span class="data-value">${escHtml(a)}</span></div>`
          ).join('')
        : '<p style="color:var(--text-light);font-size:0.8rem;">No answers provided.</p>';

    // ── Status badge color ──────────────────────────────────────
    const statusColor = {
        submitted:   '#2563eb',
        under_review:'#ca8a04',
        more_info:   '#ca8a04',
        approved:    '#16a34a',
        rejected:    '#dc2626'
    }[app.status] || '#6b7280';
    const statusBg = {
        submitted:   'rgba(59,130,246,0.1)',
        under_review:'rgba(234,179,8,0.1)',
        more_info:   'rgba(234,179,8,0.1)',
        approved:    'rgba(22,163,74,0.1)',
        rejected:    'rgba(220,38,38,0.1)'
    }[app.status] || 'rgba(0,0,0,0.05)';

    // ── Documents with tagging controls ─────────────────────────
    const CATEGORIES = ['', 'Passport', 'Photo', 'CV', 'Bank Statement', 'Degree/Certificate', 'Medical Report', 'Police Clearance', 'Other'];
    const docsHtml = docs.length
        ? docs.map(d => {
            const v = parseInt(d.verified);
            const vClass = v === 1 ? 'doc-verified' : v === -1 ? 'doc-rejected' : 'doc-pending';
            const vIcon  = v === 1 ? '✅' : v === -1 ? '❌' : '⏳';
            return `<div class="doc-card ${vClass}" data-doc-id="${d.id}">
                <div class="doc-card-header">
                    <span class="doc-name">${escHtml(d.name || d.filename)}</span>
                    <span class="doc-status-badge">${vIcon}</span>
                </div>
                <div class="doc-card-controls">
                    <select class="doc-category" onchange="updateDoc(${d.id}, 'category', this.value)">
                        ${CATEGORIES.map(c => `<option value="${c}" ${String(d.category) === c ? 'selected' : ''}>${c || '— Category —'}</option>`).join('')}
                    </select>
                    <div class="doc-verify-btns">
                        <button class="verify-btn verify-yes ${v === 1 ? 'active' : ''}" onclick="updateDoc(${d.id}, 'verified', 1)" title="Verified">✓</button>
                        <button class="verify-btn verify-no ${v === -1 ? 'active' : ''}" onclick="updateDoc(${d.id}, 'verified', -1)" title="Rejected">✗</button>
                        <button class="verify-btn verify-pending ${v === 0 || isNaN(v) ? 'active' : ''}" onclick="updateDoc(${d.id}, 'verified', 0)" title="Pending">?</button>
                    </div>
                    <a href="${base}${escHtml(d.filepath)}" target="_blank" class="btn btn-outline" style="font-size:0.7rem;padding:0.2rem 0.5rem;">View</a>
                </div>
            </div>`;
          }).join('')
        : '<p style="color:var(--text-light);font-size:0.8rem;">No documents uploaded.</p>';

    // ── Requirements Checklist ──────────────────────────────────
    let savedReqs = [];
    try {
        const parsed = JSON.parse(app.requirements || '[]');
        if (Array.isArray(parsed)) savedReqs = parsed;
    } catch(e) { savedReqs = []; }

    // Filter global requirements by service_type (matches sponsorship_type)
    const globalReqs = allRequirements.filter(r => r.service_type === app.sponsorship_type);

    // Merge: saved status overrides global defaults
    const mergedReqs = globalReqs.map(g => {
        const saved = savedReqs.find(s => s.label === g.label);
        return { label: g.label, status: saved ? saved.status : 'pending' };
    });
    // Also include any saved reqs that aren't in global (custom entries)
    globalReqs.forEach(g => {
        if (!savedReqs.find(s => s.label === g.label)) {
            // already added as pending above
        }
    });
    savedReqs.forEach(s => {
        if (!globalReqs.find(g => g.label === s.label)) {
            mergedReqs.push(s);
        }
    });

    const reqStatusIcon = { met: '✅', pending: '⏳', unmet: '❌' };
    const reqHtml = mergedReqs.length
        ? `<div class="req-checklist">${mergedReqs.map((r, i) => `
            <div class="req-item" data-idx="${i}">
                <span class="req-label">${escHtml(r.label)}</span>
                <span class="req-status-group">
                    ${['met','pending','unmet'].map(s => `
                        <button class="req-status-btn ${r.status === s ? 'active' : ''}" onclick="setReqStatus(${i}, '${s}')" data-status="${s}">${reqStatusIcon[s]} ${s}</button>
                    `).join('')}
                </span>
            </div>`).join('')}</div>`
        : '<p style="color:var(--text-light);font-size:0.8rem;">No requirements configured for this sponsorship type.</p>';

    const mergedReqsJson = JSON.stringify(mergedReqs);

    // ── Build the modal content ─────────────────────────────────
    body.innerHTML = `
        <!-- Status Bar -->
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:0.5rem;">
            <span style="font-weight:700;font-size:1.1rem;color:var(--navy);display:flex;align-items:center;gap:0.75rem;">
                Application #${app.id}
                <span style="padding:0.2rem 0.75rem;border-radius:9999px;font-size:0.7rem;font-weight:700;background:${statusBg};color:${statusColor};text-transform:uppercase;">
                    ${app.status.replace(/_/g, ' ')}
                </span>
            </span>
            <span style="font-size:0.75rem;color:var(--text-light)">
                Submitted: ${app.created_at ? new Date(app.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit' }) : 'N/A'}
            </span>
        </div>

        <!-- Applicant Info -->
        <div class="data-grid">
            <div class="data-group"><span class="data-label">Full Name</span><span class="data-value">${escHtml(app.applicant_name)}</span></div>
            <div class="data-group"><span class="data-label">Email</span><span class="data-value">${escHtml(app.email)}</span></div>
            <div class="data-group"><span class="data-label">Country</span><span class="data-value">${escHtml(app.country || 'N/A')}</span></div>
            <div class="data-group"><span class="data-label">Sponsorship Type</span><span class="data-value" style="text-transform:capitalize">${escHtml(app.sponsorship_type)}</span></div>
        </div>

        ${app.service_types && app.service_types.length ? `
        <h4 class="section-title">Services Selected</h4>
        <div style="display:flex;flex-wrap:wrap;gap:0.375rem;margin-bottom:1.5rem;">
            ${app.service_types.map(s => `<span class="badge badge-blue">${escHtml(s)}</span>`).join('')}
        </div>` : ''}

        <h4 class="section-title">Personal Info</h4>
        <div class="data-grid">${piHtml}</div>

        <h4 class="section-title">Interview Answers</h4>
        <div class="data-grid" style="grid-template-columns:1fr;">${answersHtml}</div>

        <h4 class="section-title">Documents (${docs.length})</h4>
        <div class="doc-grid">${docsHtml}</div>

        <h4 class="section-title">Requirements Checklist</h4>
        <div id="req-container">${reqHtml}</div>
        <div style="display:flex;gap:0.5rem;margin-top:0.75rem;margin-bottom:1.5rem;">
            <button class="btn btn-outline" onclick="saveAppRequirements(${app.id})" style="font-size:0.8rem;">
                <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                Save Requirements
            </button>
            <span id="req-save-msg" style="font-size:0.75rem;color:var(--text-light);align-self:center;"></span>
        </div>

        ${app.reviewed_by ? `
        <div style="padding:0.75rem 1rem;background:#f9fafb;border-radius:0.5rem;margin-bottom:1.5rem;font-size:0.8rem;color:var(--text-light);display:flex;gap:1.5rem;flex-wrap:wrap;">
            <span>Reviewed By: <strong>Admin #${app.reviewed_by}</strong></span>
            <span>Reviewed At: <strong>${app.reviewed_at ? new Date(app.reviewed_at).toLocaleString() : 'N/A'}</strong></span>
        </div>` : ''}

        <!-- Action Form -->
        <form id="action-form" method="POST" action="${base}/admin/api/queue-actions.php" style="border-top:1px solid var(--border);padding-top:1.5rem;">
            <input type="hidden" name="id" value="${app.id}">
            <input type="hidden" name="_csrf" value="${escHtml(window.LOTOKS_CONFIG?.CSRF_TOKEN || '')}">
            <div style="margin-bottom:1rem;">
                <label class="data-label" style="margin-bottom:0.375rem;display:block;">Admin Notes (Internal)</label>
                <textarea name="admin_notes" rows="3" style="padding:0.65rem;border:1px solid var(--border);border-radius:0.5rem;width:100%;font-family:inherit;font-size:0.85rem;resize:vertical;">${escHtml(app.admin_notes || '')}</textarea>
            </div>
            <div class="modal-actions">
                <button type="submit" name="action" value="more_info" class="btn btn-outline">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>
                    Request Info
                </button>
                <button type="submit" name="action" value="rejected" class="btn btn-danger" onclick="return confirm('Reject this application? The applicant will be notified.')">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Reject
                </button>
                <button type="submit" name="action" value="approved" class="btn btn-primary" onclick="return confirm('Approve this application?')">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
                    Approve
                </button>
            </div>
        </form>`;

    // Store mergedReqs JSON on container for saveRequirements
    document.getElementById('req-container').dataset.reqs = mergedReqsJson;
}


/* ── Document verification helpers ──────────────────────────── */

async function updateDoc(docId, field, value) {
    const base  = window.LOTOKS_CONFIG?.BASE || '';
    const csrf  = window.LOTOKS_CONFIG?.CSRF_TOKEN || '';
    const body  = `id=${docId}&${field}=${encodeURIComponent(value)}&_csrf=${encodeURIComponent(csrf)}`;

    try {
        const res = await fetch(`${base}/admin/api/document-verify.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body
        });
        const data = await res.json();
        if (data.success) {
            // Update UI without reload
            const card = document.querySelector(`.doc-card[data-doc-id="${docId}"]`);
            if (card) {
                if (field === 'verified') {
                    card.classList.remove('doc-verified', 'doc-rejected', 'doc-pending');
                    const vClass = value == 1 ? 'doc-verified' : value == -1 ? 'doc-rejected' : 'doc-pending';
                    card.classList.add(vClass);
                    const badge = card.querySelector('.doc-status-badge');
                    badge.textContent = value == 1 ? '✅' : value == -1 ? '❌' : '⏳';
                    card.querySelectorAll('.verify-btn').forEach(b => b.classList.remove('active'));
                    const activeBtn = card.querySelector(`.verify-btn.verify-${value == 1 ? 'yes' : value == -1 ? 'no' : 'pending'}`);
                    if (activeBtn) activeBtn.classList.add('active');
                }
                if (field === 'category') {
                    const select = card.querySelector('.doc-category');
                    select.value = value;
                }
            }
        } else {
            alert(data.message || 'Error updating document.');
        }
    } catch (e) {
        alert('Network error updating document.');
    }
}


/* ── Requirements checklist helpers ─────────────────────────── */

function setReqStatus(idx, status) {
    const container = document.getElementById('req-container');
    const items = container.querySelectorAll('.req-item');
    if (!items[idx]) return;

    // Update buttons
    items[idx].querySelectorAll('.req-status-btn').forEach(b => b.classList.remove('active'));
    const activeBtn = items[idx].querySelector(`.req-status-btn[data-status="${status}"]`);
    if (activeBtn) activeBtn.classList.add('active');

    // Update stored data
    const reqs = JSON.parse(container.dataset.reqs || '[]');
    if (reqs[idx]) {
        reqs[idx].status = status;
        container.dataset.reqs = JSON.stringify(reqs);
    }
    // Clear save message
    document.getElementById('req-save-msg').textContent = '';
}

async function saveAppRequirements(appId) {
    const container = document.getElementById('req-container');
    if (!container) return;
    const reqsJson  = container.dataset.reqs || '[]';
    const base      = window.LOTOKS_CONFIG?.BASE || '';
    const msgEl     = document.getElementById('req-save-msg');

    msgEl.textContent = 'Saving…';
    msgEl.style.color = 'var(--text-light)';

    try {
        const csrf = window.LOTOKS_CONFIG?.CSRF_TOKEN || '';
        const res = await fetch(`${base}/admin/api/save-requirements.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `application_id=${appId}&requirements=${encodeURIComponent(reqsJson)}&_csrf=${encodeURIComponent(csrf)}`
        });
        const data = await res.json();
        if (data.success) {
            msgEl.textContent = '✓ Requirements saved!';
            msgEl.style.color = 'var(--success)';
        } else {
            msgEl.textContent = '✗ Error saving requirements.';
            msgEl.style.color = 'var(--danger)';
        }
    } catch (e) {
        msgEl.textContent = '✗ Network error.';
        msgEl.style.color = 'var(--danger)';
    }
}


/* ── Application deletion ────────────────────────────────────── */

window.deleteApplication = function (id) {
    if (!confirm('Are you sure you want to permanently delete this application? This action cannot be undone.')) {
        return;
    }

    const base  = window.LOTOKS_CONFIG?.BASE || '';
    const csrf  = window.LOTOKS_CONFIG?.CSRF_TOKEN || '';
    const form = document.createElement('form');
    form.method    = 'POST';
    form.action    = `${base}/admin/api/queue-actions.php`;

    const idInput = document.createElement('input');
    idInput.type  = 'hidden';
    idInput.name  = 'id';
    idInput.value = id;

    const actionInput = document.createElement('input');
    actionInput.type  = 'hidden';
    actionInput.name  = 'action';
    actionInput.value = 'delete';

    const csrfInput = document.createElement('input');
    csrfInput.type  = 'hidden';
    csrfInput.name  = '_csrf';
    csrfInput.value = csrf;

    form.appendChild(idInput);
    form.appendChild(actionInput);
    form.appendChild(csrfInput);
    document.body.appendChild(form);
    form.submit();
};


/* ── User detail viewer ──────────────────────────────────────── */

window.viewUser = async function (id) {
    openModal('user-modal');
    const body = document.getElementById('user-modal-body');
    if (!body) return;

    body.innerHTML = `<div style="text-align:center;padding:2rem;"><p style="color:var(--text-light);">Loading user details…</p></div>`;

    try {
        const base = window.LOTOKS_CONFIG?.BASE || '';
        const res  = await fetch(`${base}/admin/api/view-user.php?id=${id}`);
        const data = await res.json();

        if (data.success) {
            renderUserModal(data);
        } else {
            body.innerHTML = `<div style="text-align:center;padding:2rem;"><p style="color:var(--danger);font-weight:600;">Error: ${escHtml(data.message)}</p></div>`;
        }
    } catch (err) {
        body.innerHTML = `<div style="text-align:center;padding:2rem;"><p style="color:var(--danger);font-weight:600;">Network error loading user details.</p></div>`;
    }
};

function renderUserModal(data) {
    const body  = document.getElementById('user-modal-body');
    const u     = data.user;
    const apps  = data.applications || [];
    const docs  = data.documents || [];
    const logs  = data.activity || [];
    const base  = window.LOTOKS_CONFIG?.BASE || '';

    // ── Recent activity ────────────────────────────────────────
    const activityHtml = logs.length
        ? logs.map(l => `
            <div class="activity-item">
                <span><span class="act-action">${escHtml(l.action)}</span> — ${escHtml(l.description)}</span>
                <span class="act-time">${l.created_at ? new Date(l.created_at).toLocaleDateString() : ''}</span>
            </div>`).join('')
        : '<p style="color:var(--text-light);font-size:0.8rem;">No recent activity.</p>';

    // ── Applications ───────────────────────────────────────────
    const appsHtml = apps.length
        ? `<table class="mini-table">
            <thead><tr><th>#</th><th>Type</th><th>Status</th><th>Submitted</th></tr></thead>
            <tbody>${apps.map(a => `
                <tr>
                    <td style="color:var(--text-light)">#${a.id}</td>
                    <td style="text-transform:capitalize">${escHtml(a.sponsorship_type)}</td>
                    <td><span class="badge badge-${['approved','green','rejected','red','more_info','yellow','under_review','yellow'][['approved','rejected','more_info','under_review'].indexOf(a.status)] || 'blue'}">${a.status.replace(/_/g, ' ')}</span></td>
                    <td style="font-size:0.75rem;color:var(--text-light)">${new Date(a.created_at).toLocaleDateString()}</td>
                </tr>`).join('')}
            </tbody></table>`
        : '<p style="color:var(--text-light);font-size:0.8rem;">No applications yet.</p>';

    // ── Documents ──────────────────────────────────────────────
    const docsHtml = docs.length
        ? docs.map(d => `
            <div style="display:flex;justify-content:space-between;align-items:center;padding:0.4rem 0;border-bottom:1px solid #f0f0f0;font-size:0.85rem;">
                <span>${escHtml(d.name)} <span style="color:var(--text-light);font-size:0.7rem;">(${d.category})</span></span>
                <a href="${base}${d.filepath}" target="_blank" class="btn btn-outline" style="font-size:0.7rem;padding:0.2rem 0.5rem;">View</a>
            </div>`).join('')
        : '<p style="color:var(--text-light);font-size:0.8rem;">No documents uploaded.</p>';

    // Determine suspend state
    const isSuspended = u.suspended;
    const suspendBtnHtml = `<button class="btn ${isSuspended ? 'btn-primary' : 'btn-danger'}" onclick="toggleSuspend(${u.id}, ${isSuspended})" style="font-size:0.75rem;">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:0.25rem">
            ${isSuspended
                ? '<circle cx="12" cy="12" r="10"/><polyline points="8 12 12 16 16 12"/><line x1="12" y1="8" x2="12.01" y2="8"/>'
                : '<circle cx="12" cy="12" r="10"/><line x1="8" y1="12" x2="16" y2="12"/>'}
        </svg>
        ${isSuspended ? 'Reactivate' : 'Suspend'}
    </button>`;

    body.innerHTML = `
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
            <h3 style="font-size:1.15rem;font-weight:700;color:var(--navy);display:flex;align-items:center;gap:0.75rem;">
                <span style="width:2.5rem;height:2.5rem;background:var(--gold);color:var(--navy);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-weight:800;font-size:1rem;">
                    ${escHtml(u.name.charAt(0).toUpperCase())}
                </span>
                ${escHtml(u.name)}
                ${u.verified ? '<span class="badge badge-green" style="font-size:0.6rem;">Verified</span>' : '<span class="badge badge-yellow" style="font-size:0.6rem;">Unverified</span>'}
                ${isSuspended ? '<span class="badge badge-red" style="font-size:0.6rem;">Suspended</span>' : ''}
            </h3>
            <div>${suspendBtnHtml}</div>
        </div>

        <div class="user-modal-grid">
            <div class="user-info-card">
                <h4>Account Info</h4>
                <div class="user-info-item"><span class="label">Email</span><span class="value">${escHtml(u.email)}</span></div>
                <div class="user-info-item"><span class="label">Country</span><span class="value">${escHtml(u.country || '—')}</span></div>
                <div class="user-info-item"><span class="label">User ID</span><span class="value">#${u.id}</span></div>
                <div class="user-info-item"><span class="label">Joined</span><span class="value">${new Date(u.created_at).toLocaleDateString()}</span></div>
            </div>
            <div class="user-info-card">
                <h4>Stats</h4>
                <div class="user-info-item"><span class="label">Applications</span><span class="value">${apps.length}</span></div>
                <div class="user-info-item"><span class="label">Documents</span><span class="value">${docs.length}</span></div>
                <div class="user-info-item"><span class="label">Recent Activity</span><span class="value">${logs.length} entries</span></div>
            </div>
        </div>

        <h4 class="section-title">Applications (${apps.length})</h4>
        <div style="margin-bottom:1.5rem;">${appsHtml}</div>

        <h4 class="section-title">Documents (${docs.length})</h4>
        <div style="margin-bottom:1.5rem;">${docsHtml}</div>

        <h4 class="section-title">Recent Activity</h4>
        <div class="activity-feed">${activityHtml}</div>`;
}


/* ── Suspend / Unsuspend user ─────────────────────────────────── */

window.toggleSuspend = async function (userId, isSuspended) {
    const action = isSuspended ? 'unsuspend' : 'suspend';
    const verb   = isSuspended ? 'reactivate' : 'suspend';
    const msg    = `Are you sure you want to ${verb} this user?`;

    if (!confirm(msg)) return;

    const base = window.LOTOKS_CONFIG?.BASE || '';
    const csrf = window.LOTOKS_CONFIG?.CSRF_TOKEN || '';
    const body = `user_id=${userId}&action=${action}&_csrf=${encodeURIComponent(csrf)}`;

    try {
        const res = await fetch(`${base}/admin/api/toggle-suspend.php`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: body
        });
        const data = await res.json();
        if (data.success) {
            // Close and re-open modal to reflect changes
            closeModal('user-modal');
            setTimeout(() => viewUser(userId), 300);
        } else {
            alert(data.message || 'Error updating user status.');
        }
    } catch (e) {
        alert('Network error updating user status.');
    }
};


/* ── Utility helpers ─────────────────────────────────────────── */

function escHtml(str) {
    if (str === null || str === undefined) return '';
    const div = document.createElement('div');
    div.textContent = String(str);
    return div.innerHTML;
}

function safeObject(obj) {
    return (obj && typeof obj === 'object' && !Array.isArray(obj)) ? obj : {};
}
