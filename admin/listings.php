<?php
/**
 * Lotoks — Listings / Opportunities Management (admin/listings.php)
 * Active tab for all admins, Trash tab visible only to super_admin.
 */
$page_title = 'Listings';
require_once __DIR__ . '/includes/header.php';

$db      = getDb();
$isSuper = is_super_admin();
$tab     = $_GET['tab'] ?? 'active';

// ── Fetch listings ─────────────────────────────────────────────
if ($tab === 'trash' && $isSuper) {
    $listings = $db->query("SELECT * FROM listings WHERE deleted_at IS NOT NULL ORDER BY deleted_at DESC")->fetchAll();
} else {
    $listings = $db->query("SELECT * FROM listings WHERE deleted_at IS NULL ORDER BY created_at DESC")->fetchAll();
    $tab = 'active';
}
$trashCount = $db->query("SELECT COUNT(*) FROM listings WHERE deleted_at IS NOT NULL")->fetchColumn();
?>

<style>
/* ── Page Toolbar (header + button) ─────────────── */
.listing-toolbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1.75rem;
  padding: 1.25rem 1.5rem;
  background: white;
  border-radius: 1rem;
  box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.02);
}
.listing-toolbar h2 {
  font-size: 1rem;
  font-weight: 700;
  color: var(--navy);
  letter-spacing: -0.01em;
}
.listing-toolbar p {
  font-size: 0.8rem;
  color: var(--text-light);
  margin-top: 0.1rem;
}
.toolbar-actions { display: flex; gap: 0.5rem; flex-shrink: 0; }

/* ── Tabs ────────────────────────────────────────── */
.listing-tabs {
  display: flex;
  gap: 0.125rem;
  margin-bottom: 1.75rem;
  padding: 0.25rem;
  background: white;
  border-radius: 0.75rem;
  box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.02);
  width: fit-content;
}
.listing-tab {
  padding: 0.5rem 1.125rem;
  font-size: 0.8rem;
  font-weight: 600;
  color: var(--text-light);
  text-decoration: none;
  border-radius: 0.5rem;
  transition: all 0.2s ease;
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
}
.listing-tab:hover { color: var(--navy); }
.listing-tab.active {
  color: var(--navy);
  background: rgba(11,29,58,0.06);
  box-shadow: inset 0 1px 2px rgba(0,0,0,0.04);
}
.tab-badge {
  display: inline-flex; align-items: center; justify-content: center;
  min-width: 1.25rem; height: 1.25rem;
  padding: 0 0.35rem; border-radius: 9999px;
  font-size: 0.6rem; font-weight: 700;
  transition: all 0.2s;
}
.listing-tab.active .tab-badge { background: var(--gold); color: var(--navy); }
.listing-tab:not(.active) .tab-badge { background: var(--bg-color); color: var(--text-light); }
.listing-tab svg { opacity: 0.7; }
.listing-tab.active svg { opacity: 1; }

/* ── Table Card ──────────────────────────────────── */
.listing-card {
  background: white;
  border-radius: 1rem;
  box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.02);
  overflow: hidden;
  margin-bottom: 2rem;
}
.listing-card-header {
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid #f0f2f5;
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.listing-card-header h3 {
  font-size: 0.95rem;
  font-weight: 700;
  color: var(--navy);
  letter-spacing: -0.01em;
}
.listing-card-header span {
  font-size: 0.75rem;
  color: var(--text-light);
}

/* ── Table ───────────────────────────────────────── */
.listing-table { width: 100%; border-collapse: collapse; min-width: 700px; }
.listing-table thead th {
  padding: 0.75rem 1.25rem;
  font-size: 0.65rem;
  font-weight: 700;
  color: var(--text-light);
  text-transform: uppercase;
  letter-spacing: 0.04em;
  background: #f8f9fc;
  border-bottom: 1px solid #eef0f4;
  white-space: nowrap;
}
.listing-table tbody td {
  padding: 0.875rem 1.25rem;
  font-size: 0.825rem;
  border-bottom: 1px solid #f0f2f5;
  vertical-align: middle;
}
.listing-table tbody tr:last-child td { border-bottom: none; }
.listing-table tbody tr {
  transition: background 0.15s ease;
}
.listing-table tbody tr:hover { background: rgba(11,29,58,0.015); }

/* ── Title cell with description ─────────────────── */
.listing-title-cell {
  font-weight: 600;
  color: var(--navy);
  font-size: 0.875rem;
}
.listing-meta {
  display: block;
  font-size: 0.7rem;
  color: var(--text-light);
  margin-top: 0.2rem;
  line-height: 1.3;
  max-width: 20rem;
}

/* ── Type badges ─────────────────────────────────── */
.type-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.3rem;
  padding: 0.2rem 0.55rem;
  border-radius: 0.375rem;
  font-size: 0.68rem;
  font-weight: 600;
  text-transform: capitalize;
}
.type-badge.type-job {
  background: rgba(59,130,246,0.08);
  color: #2563eb;
}
.type-badge.type-edu {
  background: rgba(139,92,246,0.08);
  color: #7c3aed;
}

/* ── Status badges ───────────────────────────────── */
.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  padding: 0.15rem 0.65rem 0.15rem 0.5rem;
  border-radius: 9999px;
  font-size: 0.68rem;
  font-weight: 700;
  cursor: pointer;
  user-select: none;
  transition: all 0.2s;
  border: none;
  background: transparent;
  font-family: inherit;
}
.status-dot {
  width: 0.45rem;
  height: 0.45rem;
  border-radius: 50%;
  flex-shrink: 0;
}
.status-badge.status-active {
  background: rgba(22,163,74,0.08);
  color: #16a34a;
}
.status-badge.status-active .status-dot { background: #16a34a; }
.status-badge.status-inactive {
  background: rgba(234,179,8,0.08);
  color: #ca8a04;
}
.status-badge.status-inactive .status-dot { background: #ca8a04; }
.status-badge.status-deleted {
  background: rgba(220,38,38,0.08);
  color: #dc2626;
  cursor: default;
}
.status-badge.status-deleted .status-dot { background: #dc2626; }
.status-badge:hover:not(.status-deleted) {
  filter: brightness(0.95);
}
.status-badge:active:not(.status-deleted) {
  transform: scale(0.95);
}

/* ── Date cell ───────────────────────────────────── */
.date-cell {
  font-size: 0.75rem;
  color: var(--text-light);
  white-space: nowrap;
}
.date-cell .date-sub {
  display: block;
  font-size: 0.65rem;
  opacity: 0.75;
}

/* ── Action buttons ──────────────────────────────── */
.action-group {
  display: flex;
  gap: 0.25rem;
  justify-content: flex-end;
}
.icon-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 2rem;
  height: 2rem;
  border: none;
  border-radius: 0.5rem;
  cursor: pointer;
  transition: all 0.15s ease;
  background: transparent;
  color: var(--text-light);
}
.icon-btn:hover { background: rgba(11,29,58,0.05); color: var(--navy); }
.icon-btn.icon-edit:hover { background: rgba(59,130,246,0.1); color: #2563eb; }
.icon-btn.icon-delete:hover { background: rgba(220,38,38,0.1); color: #dc2626; }
.icon-btn.icon-restore:hover { background: rgba(22,163,74,0.1); color: #16a34a; }
.icon-btn.icon-permanent:hover { background: rgba(220,38,38,0.1); color: #dc2626; }
.icon-btn svg { width: 1rem; height: 1rem; }
.icon-btn + .icon-btn { margin-left: 0; }
/* Labeled btn for trash */
.trash-action-btn {
  display: inline-flex;
  align-items: center;
  gap: 0.35rem;
  padding: 0.35rem 0.65rem;
  border-radius: 0.5rem;
  font-size: 0.7rem;
  font-weight: 600;
  border: none;
  cursor: pointer;
  transition: all 0.15s;
  background: transparent;
  color: var(--text-light);
}
.trash-action-btn:hover { background: rgba(11,29,58,0.04); }
.trash-action-btn.btn-restore { color: #2563eb; }
.trash-action-btn.btn-restore:hover { background: rgba(59,130,246,0.1); }
.trash-action-btn.btn-permanent { color: #dc2626; }
.trash-action-btn.btn-permanent:hover { background: rgba(220,38,38,0.1); }
.trash-action-btn svg { width: 0.85rem; height: 0.85rem; }

/* ── Empty state ─────────────────────────────────── */
.empty-state {
  text-align: center;
  padding: 3.5rem 2rem;
}
.empty-state-illustration {
  width: 4.5rem;
  height: 4.5rem;
  margin: 0 auto 1.25rem;
  background: #f8f9fc;
  border-radius: 1rem;
  display: flex;
  align-items: center;
  justify-content: center;
}
.empty-state-illustration svg {
  width: 2rem;
  height: 2rem;
  color: var(--text-light);
  opacity: 0.4;
}
.empty-state h4 {
  font-size: 1rem;
  font-weight: 700;
  color: var(--navy);
  margin-bottom: 0.25rem;
}
.empty-state p {
  font-size: 0.8rem;
  color: var(--text-light);
  max-width: 18rem;
  margin: 0 auto 0.25rem;
}
.empty-state .btn-empty { margin-top: 1rem; }

/* ── Trash row ───────────────────────────────────── */
tr.trash-row td { opacity: 0.55; }

/* ── Modal form refinements ─────────────────────── */
.modal-form-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
  margin-bottom: 1rem;
}
@media (max-width: 500px) { .modal-form-row { grid-template-columns: 1fr; } }
.modal-field {
  display: flex;
  flex-direction: column;
  gap: 0.3rem;
}
.modal-field label {
  font-size: 0.7rem;
  font-weight: 700;
  color: var(--text-light);
  text-transform: uppercase;
  letter-spacing: 0.03em;
}
.modal-field input,
.modal-field select,
.modal-field textarea {
  padding: 0.6rem 0.75rem;
  border: 1.5px solid #e5e7eb;
  border-radius: 0.5rem;
  font-family: inherit;
  font-size: 0.85rem;
  transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
  background: #fafafa;
}
.modal-field input:focus,
.modal-field select:focus,
.modal-field textarea:focus {
  outline: none;
  border-color: var(--gold);
  box-shadow: 0 0 0 3px rgba(201,164,75,0.12);
  background: white;
}
.modal-field textarea { min-height: 5rem; resize: vertical; }
.modal-field select {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
  background-repeat: no-repeat;
  background-position: right 0.75rem center;
  background-size: 1.25rem;
  padding-right: 2.5rem;
  appearance: none;
  -webkit-appearance: none;
  -moz-appearance: none;
  cursor: pointer;
}
.modal-checkbox {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding-top: 1rem;
}
.modal-checkbox input[type="checkbox"] {
  width: 1.1rem;
  height: 1.1rem;
  border-radius: 0.25rem;
  border: 1.5px solid #d1d5db;
  cursor: pointer;
  accent-color: var(--gold);
}

/* ── Responsive ───────────────────────────────── */
@media (max-width: 768px) {
  .listing-toolbar { flex-direction: column; align-items: flex-start; }
  .toolbar-actions { align-self: stretch; }
  .toolbar-actions .btn { flex: 1; justify-content: center; }
  .listing-tabs { width: 100%; overflow-x: auto; flex-wrap: nowrap; -webkit-overflow-scrolling: touch; }
  .listing-tab { white-space: nowrap; flex-shrink: 0; }
}
</style>

<!-- ── Tabs ───────────────────────────────────── -->
<div class="listing-tabs">
  <a href="?tab=active" class="listing-tab <?= $tab === 'active' ? 'active' : '' ?>">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    Active Listings
    <span class="tab-badge"><?= count($listings) ?></span>
  </a>
  <?php if ($isSuper): ?>
  <a href="?tab=trash" class="listing-tab <?= $tab === 'trash' ? 'active' : '' ?>">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
    Trash
    <span class="tab-badge"><?= $trashCount ?></span>
  </a>
  <?php endif; ?>
</div>

<!-- ── Toolbar ───────────────────────────────────── -->
<?php if ($tab === 'active'): ?>
  <div class="listing-toolbar">
    <div>
      <h2>Manage Opportunities</h2>
      <p>Create and manage job and education sponsorship listings.</p>
    </div>
    <div class="toolbar-actions">
      <button class="btn btn-primary" onclick="openListingModal()">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Add Listing
      </button>
    </div>
  </div>
<?php endif; ?>

<!-- ── Table Card ─────────────────────────────── -->
<div class="listing-card">
  <div class="listing-card-header">
    <h3><?= $tab === 'trash' ? 'Deleted Listings' : 'All Opportunities' ?></h3>
    <span><?= count($listings) ?> listing<?= count($listings) !== 1 ? 's' : '' ?></span>
  </div>
  <div class="table-responsive" style="margin:0">
    <table class="listing-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Title</th>
          <th>Employer</th>
          <th>Country</th>
          <th>Type</th>
          <th>Status</th>
          <th><?= $tab === 'trash' ? 'Deleted' : 'Created' ?></th>
          <th style="text-align:right">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($listings)): ?>
          <tr>
            <td colspan="8">
              <div class="empty-state">
                <div class="empty-state-illustration">
                  <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/>
                  </svg>
                </div>
                <h4><?= $tab === 'trash' ? 'Trash is empty' : 'No listings yet' ?></h4>
                <p><?= $tab === 'trash' ? 'Deleted listings will appear here.' : 'Click "Add Listing" above to create your first opportunity.' ?></p>
                <?php if ($tab === 'active'): ?>
                  <button class="btn btn-primary btn-empty" onclick="openListingModal()">Create Listing</button>
                <?php endif; ?>
              </div>
            </td>
          </tr>
        <?php else: foreach ($listings as $l): ?>
          <tr class="<?= $tab === 'trash' ? 'trash-row' : '' ?>">
            <td style="color:var(--text-light);font-size:0.7rem">#<?= $l['id'] ?></td>
            <td>
              <div class="listing-title-cell"><?= htmlspecialchars($l['title']) ?></div>
              <span class="listing-meta"><?= htmlspecialchars(mb_substr($l['description'], 0, 80)) ?><?= strlen($l['description']) > 80 ? '…' : '' ?></span>
            </td>
            <td style="font-weight:500"><?= htmlspecialchars($l['employer'] ?: '—') ?></td>
            <td style="color:var(--text-light)"><?= htmlspecialchars($l['country'] ?: '—') ?></td>
            <td>
              <span class="type-badge type-<?= htmlspecialchars($l['type']) ?>">
                <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                  <?php if ($l['type'] === 'job'): ?>
                    <path d="M20 7h-4V4a1 1 0 0 0-1-1H9a1 1 0 0 0-1 1v3H4a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V8a1 1 0 0 0-1-1z"/>
                  <?php else: ?>
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                  <?php endif; ?>
                </svg>
                <?= htmlspecialchars(ucfirst($l['type'])) ?>
              </span>
            </td>
            <td>
              <?php if ($tab === 'trash'): ?>
                <span class="status-badge status-deleted">
                  <span class="status-dot"></span>
                  Deleted
                </span>
              <?php else: ?>
                <button class="status-badge <?= $l['active'] ? 'status-active' : 'status-inactive' ?> toggle-active"
                        onclick="toggleActive(<?= $l['id'] ?>, this)"
                        title="Click to toggle">
                  <span class="status-dot"></span>
                  <?= $l['active'] ? 'Active' : 'Inactive' ?>
                </button>
              <?php endif; ?>
            </td>
            <td>
              <span class="date-cell">
                <?= date('M j, Y', strtotime($l[$tab === 'trash' ? 'deleted_at' : 'created_at'])) ?>
                <span class="date-sub"><?= date('g:i A', strtotime($l[$tab === 'trash' ? 'deleted_at' : 'created_at'])) ?></span>
              </span>
            </td>
            <td>
              <?php if ($tab === 'trash'): ?>
                <div class="action-group">
                  <button class="trash-action-btn btn-restore" onclick="restoreListing(<?= $l['id'] ?>)">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/></svg>
                    Restore
                  </button>
                  <button class="trash-action-btn btn-permanent" onclick="permanentDelete(<?= $l['id'] ?>)">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    Delete Forever
                  </button>
                </div>
              <?php else: ?>
                <div class="action-group">
                  <button class="icon-btn icon-edit" onclick="editListing(<?= $l['id'] ?>)" title="Edit listing">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                  </button>
                  <button class="icon-btn icon-delete" onclick="softDeleteListing(<?= $l['id'] ?>)" title="Move to trash">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                  </button>
                </div>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; endif; ?>
      </tbody>
    </table>
  </div>
</div>

<!-- ── Create/Edit Modal (shared template) ─────── -->
<div class="modal-overlay" id="listing-modal">
  <div class="modal" style="max-width:650px;">
    <div class="modal-header">
      <h2 class="modal-title" id="listing-modal-title">Add Listing</h2>
      <button class="modal-close" onclick="closeModal('listing-modal')">&times;</button>
    </div>
    <form method="POST" action="api/listing-actions.php" id="listing-form">
      <?= csrf_field() ?>
      <input type="hidden" name="action" id="listing-form-action" value="create">
      <input type="hidden" name="id" id="listing-form-id" value="0">
      <div class="modal-body">
        <div class="modal-form-row">
          <div class="modal-field">
            <label>Title *</label>
            <input type="text" name="title" id="f-title" required placeholder="e.g. Software Developer">
          </div>
          <div class="modal-field">
            <label>Employer</label>
            <input type="text" name="employer" id="f-employer" placeholder="e.g. Tech Corp Ltd">
          </div>
        </div>
        <div class="modal-field" style="margin-bottom:1rem;">
          <label>Description</label>
          <textarea name="description" id="f-description" placeholder="Full description of the opportunity…"></textarea>
        </div>
        <div class="modal-form-row">
          <div class="modal-field">
            <label>Country</label>
            <?= countryDropdown('country', '', ['id' => 'f-country', 'class' => 'form-input', 'placeholder' => 'Select a country']) ?>
          </div>
          <div class="modal-field">
            <label>Sponsorship Type</label>
            <select name="sponsorship_type" id="f-sponsorship_type">
              <option value="visa">Visa Sponsorship</option>
              <option value="job">Job Sponsorship</option>
              <option value="edu">Education Sponsorship</option>
              <option value="pr">Permanent Residency</option>
            </select>
          </div>
        </div>
        <div class="modal-form-row">
          <div class="modal-field">
            <label>Salary Range</label>
            <input type="text" name="salary_range" id="f-salary_range" placeholder="e.g. $50k – $80k">
          </div>
          <div class="modal-field">
            <label>Type</label>
            <select name="type" id="f-type">
              <option value="job">Job</option>
              <option value="edu">Education</option>
            </select>
          </div>
        </div>
        <div class="modal-field" style="margin-bottom:0.5rem;">
          <label>Requirements (one per line)</label>
          <textarea name="requirements" id="f-requirements" rows="4" placeholder="Bachelor's degree&#10;3+ years experience&#10;English proficiency"></textarea>
        </div>
        <div class="modal-checkbox">
          <input type="checkbox" name="active" id="f-active" checked>
          <label for="f-active" style="font-size:0.85rem;font-weight:600;cursor:pointer;">Active (visible to users)</label>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline" onclick="closeModal('listing-modal')">Cancel</button>
        <button type="submit" class="btn btn-primary" id="listing-submit-btn">Create Listing</button>
      </div>
    </form>
  </div>
</div>

<script>
/* ── Open modal for creating ──────────────────── */
function openListingModal() {
  document.getElementById('listing-form-action').value = 'create';
  document.getElementById('listing-form-id').value = '0';
  document.getElementById('listing-modal-title').textContent = 'Add Listing';
  document.getElementById('listing-submit-btn').textContent = 'Create Listing';
  document.getElementById('listing-form').reset();
  document.getElementById('f-active').checked = true;
  openModal('listing-modal');
}

/* ── Open modal for editing (AJAX fetch) ──────── */
async function editListing(id) {
  const base = window.LOTOKS_CONFIG?.BASE || '';
  const csrf = window.LOTOKS_CONFIG?.CSRF_TOKEN || '';
  try {
    const res = await fetch(`${base}/admin/api/listing-actions.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `action=fetch&id=${id}&_csrf=${encodeURIComponent(csrf)}`
    });
    const data = await res.json();
    if (!data.success) { alert(data.message); return; }

    const l = data.listing;
    document.getElementById('listing-form-action').value = 'update';
    document.getElementById('listing-form-id').value = l.id;
    document.getElementById('listing-modal-title').textContent = 'Edit Listing';
    document.getElementById('listing-submit-btn').textContent = 'Save Changes';
    document.getElementById('f-title').value = l.title || '';
    document.getElementById('f-employer').value = l.employer || '';
    document.getElementById('f-description').value = l.description || '';
    document.getElementById('f-country').value = l.country || '';
    document.getElementById('f-sponsorship_type').value = l.sponsorship_type || 'visa';
    document.getElementById('f-salary_range').value = l.salary_range || '';
    document.getElementById('f-type').value = l.type || 'job';
    document.getElementById('f-requirements').value = l.requirements || '';
    document.getElementById('f-active').checked = !!parseInt(l.active);
    openModal('listing-modal');
  } catch (e) {
    alert('Error loading listing details.');
  }
}

/* ── Soft delete (to trash) ───────────────────── */
async function softDeleteListing(id) {
  if (!confirm('Move this listing to trash?')) return;
  const base = window.LOTOKS_CONFIG?.BASE || '';
  const csrf = window.LOTOKS_CONFIG?.CSRF_TOKEN || '';
  const res = await fetch(`${base}/admin/api/listing-actions.php`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `action=soft_delete&id=${id}&_csrf=${encodeURIComponent(csrf)}`
  });
  const data = await res.json();
  if (data.success) { location.reload(); }
  else { alert(data.message); }
}

/* ── Restore from trash ───────────────────────── */
async function restoreListing(id) {
  if (!confirm('Restore this listing from trash?')) return;
  const base = window.LOTOKS_CONFIG?.BASE || '';
  const csrf = window.LOTOKS_CONFIG?.CSRF_TOKEN || '';
  const res = await fetch(`${base}/admin/api/listing-actions.php`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `action=restore&id=${id}&_csrf=${encodeURIComponent(csrf)}`
  });
  const data = await res.json();
  if (data.success) { location.reload(); }
  else { alert(data.message); }
}

/* ── Permanent delete ─────────────────────────── */
async function permanentDelete(id) {
  if (!confirm('Permanently delete this listing? This CANNOT be undone.')) return;
  const base = window.LOTOKS_CONFIG?.BASE || '';
  const csrf = window.LOTOKS_CONFIG?.CSRF_TOKEN || '';
  const res = await fetch(`${base}/admin/api/listing-actions.php`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `action=permanent_delete&id=${id}&_csrf=${encodeURIComponent(csrf)}`
  });
  const data = await res.json();
  if (data.success) { location.reload(); }
  else { alert(data.message); }
}

/* ── Toggle active status ─────────────────────── */
async function toggleActive(id, badgeEl) {
  const base = window.LOTOKS_CONFIG?.BASE || '';
  const csrf = window.LOTOKS_CONFIG?.CSRF_TOKEN || '';
  // First fetch current
  const res = await fetch(`${base}/admin/api/listing-actions.php`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `action=fetch&id=${id}&_csrf=${encodeURIComponent(csrf)}`
  });
  const data = await res.json();
  if (!data.success) return;

  const l = data.listing;
  const newActive = l.active ? 0 : 1;

  // Use the update endpoint
  const updateRes = await fetch(`${base}/admin/api/listing-actions.php`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `action=update&id=${id}&title=${encodeURIComponent(l.title)}&employer=${encodeURIComponent(l.employer)}&description=${encodeURIComponent(l.description)}&country=${encodeURIComponent(l.country)}&sponsorship_type=${encodeURIComponent(l.sponsorship_type)}&salary_range=${encodeURIComponent(l.salary_range)}&type=${encodeURIComponent(l.type)}&requirements=${encodeURIComponent(l.requirements)}&active=${newActive}&_csrf=${encodeURIComponent(csrf)}`
  });
  location.reload();
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
