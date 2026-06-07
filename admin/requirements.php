<?php
/**
 * admin/requirements.php
 * Admin Requirements Manager — CRUD for document tags per service type.
 * Admin defines which documents are required for each service type.
 * Each tag has a group name, description, and required/optional setting.
 */
$page_title = 'Requirements Manager';
require_once __DIR__ . '/includes/header.php';

$db = getDb();
$serviceTypes = ['visa', 'job', 'edu', 'pr'];
$typeLabels = [
    'visa' => 'Visa Sponsorship',
    'job'  => 'Job Sponsorship',
    'edu'  => 'Education Scholarship',
    'pr'   => 'Permanent Residency',
];

// Fetch existing requirements
$rows = $db->query("SELECT * FROM requirements ORDER BY service_type")->fetchAll();
$reqData = [];
foreach ($rows as $r) {
    $cats = json_decode($r['categories'], true) ?: [];
    $reqData[$r['service_type']] = $cats;
}
?>
<style>
/* ── Service Type Tabs ──────────────────────────── */
.req-tabs {
  display: flex; gap: 0.25rem; margin-bottom: 1.5rem;
  border-bottom: 1px solid var(--border); padding-bottom: 0;
}
.req-tab {
  padding: 0.65rem 1.25rem; font-size: 0.85rem; font-weight: 600;
  color: var(--text-light); text-decoration: none;
  border-bottom: 2px solid transparent; transition: all 0.2s;
  cursor: pointer; background: none; border-top: none; border-left: none; border-right: none;
  display: inline-flex; align-items: center; gap: 0.4rem;
}
.req-tab:hover { color: var(--navy); }
.req-tab.active { color: var(--navy); border-bottom-color: var(--gold); }

/* ── Category Group Card ────────────────────────── */
.req-group {
  background: white; border: 1px solid var(--border); border-radius: 0.75rem;
  padding: 1.25rem; margin-bottom: 1rem;
}
.req-group-header {
  display: flex; justify-content: space-between; align-items: center;
  margin-bottom: 0.75rem;
}
.req-group-title {
  font-size: 0.9rem; font-weight: 700; color: var(--navy);
  display: flex; align-items: center; gap: 0.5rem;
}
.req-group-actions { display: flex; gap: 0.5rem; }

/* ── Item Row ───────────────────────────────────── */
.req-item-row {
  display: flex; align-items: center; gap: 0.75rem;
  padding: 0.6rem 0.75rem; background: #fafafa; border-radius: 0.5rem;
  border: 1px solid #f0f0f0; margin-bottom: 0.5rem;
  transition: all 0.15s;
}
.req-item-row:hover { border-color: var(--gold); background: #fefcf5; }
.req-item-name { flex: 2; font-weight: 600; font-size: 0.85rem; color: var(--navy); }
.req-item-desc { flex: 3; font-size: 0.8rem; color: var(--text-light); }
.req-item-required { flex: 0 0 60px; font-size: 0.7rem; font-weight: 700; text-align: center; }
.req-item-delete {
  flex: 0 0 2rem; background: none; border: none; cursor: pointer;
  color: var(--text-light); transition: color 0.15s;
}
.req-item-delete:hover { color: var(--danger); }

/* ── Add Item Form ──────────────────────────────── */
.add-item-form {
  display: flex; gap: 0.5rem; align-items: center;
  margin-top: 0.75rem; flex-wrap: wrap;
}
.add-item-form input, .add-item-form select {
  padding: 0.5rem 0.75rem; border: 1px solid var(--border);
  border-radius: 0.375rem; font-family: inherit; font-size: 0.8rem;
}
.add-item-form input:focus, .add-item-form select:focus {
  outline: none; border-color: var(--gold);
  box-shadow: 0 0 0 3px rgba(201,164,75,0.12);
}
.add-item-name { flex: 2; min-width: 140px; }
.add-item-desc { flex: 3; min-width: 200px; }
.add-item-required { flex: 0 0 80px; }

/* ── Empty State ────────────────────────────────── */
.req-empty {
  text-align: center; padding: 3rem 1rem; color: var(--text-light);
}
.req-empty svg { margin-bottom: 1rem; opacity: 0.3; }
.req-empty h3 { font-size: 1.1rem; font-weight: 700; color: var(--navy); margin-bottom: 0.25rem; }

/* ── Add Group Button ───────────────────────────── */
.add-group-area {
  margin-top: 1rem; padding: 1.25rem;
  border: 2px dashed var(--border); border-radius: 0.75rem;
  text-align: center; cursor: pointer; transition: all 0.2s;
}
.add-group-area:hover { border-color: var(--gold); background: #fefcf5; }
.add-group-area button {
  background: none; border: none; font-weight: 700; color: var(--navy);
  cursor: pointer; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.375rem;
}

/* ── Save bar ───────────────────────────────────── */
.req-save-bar {
  display: flex; justify-content: flex-end; gap: 0.75rem;
  padding: 1.25rem 0; border-top: 1px solid var(--border);
  margin-top: 1.5rem;
}
</style>

<div class="card">
  <div class="card-header">
    <h2>Document Requirements Manager</h2>
    <p style="font-size:0.8rem;color:var(--text-light)">
      Define which documents users must upload for each sponsorship type.
    </p>
  </div>

  <div style="padding:1.5rem">
    <!-- Service Type Tabs -->
    <div class="req-tabs" id="req-tabs">
      <?php foreach ($serviceTypes as $st): ?>
      <button class="req-tab <?= $st === 'visa' ? 'active' : '' ?>"
              data-type="<?= $st ?>"
              onclick="switchTab('<?= $st ?>')">
        <?= htmlspecialchars($typeLabels[$st] ?? ucfirst($st)) ?>
      </button>
      <?php endforeach; ?>
    </div>

    <!-- Panel per service type -->
    <?php foreach ($serviceTypes as $st): ?>
    <div class="req-panel" id="panel-<?= $st ?>" style="display:<?= $st === 'visa' ? 'block' : 'none' ?>">
      <div class="req-groups" id="groups-<?= $st ?>">
        <?php
        $cats = $reqData[$st] ?? [];
        if (empty($cats)):
        ?>
        <div class="req-empty" id="empty-<?= $st ?>">
          <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="12" y1="18" x2="12" y2="12"/>
            <line x1="9" y1="15" x2="15" y2="15"/>
          </svg>
          <h3>No requirements yet</h3>
          <p>Add a document group to get started.</p>
        </div>
        <?php else:
          foreach ($cats as $gi => $group):
            $groupName = $group['name'] ?? 'General';
            $items = $group['items'] ?? [];
        ?>
        <div class="req-group" data-group-index="<?= $gi ?>">
          <div class="req-group-header">
            <div class="req-group-title">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              <span class="group-name-label"><?= htmlspecialchars($groupName) ?></span>
            </div>
            <div class="req-group-actions">
              <button class="btn btn-outline" style="font-size:0.7rem;padding:0.25rem 0.5rem" onclick="deleteGroup('<?= $st ?>', this)">
                Remove Group
              </button>
            </div>
          </div>

          <div class="req-items">
            <?php foreach ($items as $ii => $item):
              $itemName = is_string($item) ? $item : ($item['name'] ?? $item['label'] ?? '');
              $itemDesc = is_string($item) ? '' : ($item['desc'] ?? $item['description'] ?? '');
              $itemReq  = is_string($item) ? true : (isset($item['required']) ? (bool)$item['required'] : true);
            ?>
            <div class="req-item-row" data-item-index="<?= $ii ?>">
              <span class="req-item-name"><?= htmlspecialchars($itemName) ?></span>
              <span class="req-item-desc"><?= htmlspecialchars($itemDesc) ?></span>
              <span class="req-item-required">
                <span class="badge <?= $itemReq ? 'badge-green' : 'badge-yellow' ?>"><?= $itemReq ? 'Required' : 'Optional' ?></span>
              </span>
              <button class="req-item-delete" onclick="deleteItem(this)" title="Delete item">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </button>
            </div>
            <?php endforeach; ?>
          </div>

          <!-- Add item form -->
          <div class="add-item-form">
            <input type="text" class="add-item-name" placeholder="Document name (e.g. CV / Resume)" value="">
            <input type="text" class="add-item-desc" placeholder="Description (e.g. Upload your resume in PDF)">
            <select class="add-item-required">
              <option value="1">Required</option>
              <option value="0">Optional</option>
            </select>
            <button class="btn btn-primary" style="font-size:0.75rem;padding:0.5rem 1rem" onclick="addItem('<?= $st ?>', this)">
              + Add
            </button>
          </div>
        </div>
        <?php endforeach; endif; ?>
      </div>

      <!-- Add Group -->
      <div class="add-group-area" onclick="addGroup('<?= $st ?>')">
        <button type="button">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
          Add Document Group
        </button>
      </div>

      <!-- Save Button -->
      <div class="req-save-bar">
        <span id="save-msg-<?= $st ?>" style="font-size:0.8rem;align-self:center;color:var(--text-light)"></span>
        <button class="btn btn-primary" onclick="saveRequirements('<?= $st ?>')">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
          Save Requirements
        </button>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<script>
const BASE = window.LOTOKS_CONFIG?.BASE || '';

function switchTab(type) {
  document.querySelectorAll('.req-tab').forEach(t => t.classList.toggle('active', t.dataset.type === type));
  document.querySelectorAll('.req-panel').forEach(p => p.style.display = p.id === 'panel-' + type ? 'block' : 'none');
}

function addGroup(type) {
  const container = document.getElementById('groups-' + type);
  const empty = document.getElementById('empty-' + type);
  if (empty) empty.style.display = 'none';

  const groupIndex = container.querySelectorAll('.req-group').length;
  const div = document.createElement('div');
  div.className = 'req-group';
  div.dataset.groupIndex = groupIndex;
  div.innerHTML = `
    <div class="req-group-header">
      <div class="req-group-title">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        <span class="group-name-label">New Group</span>
      </div>
      <div class="req-group-actions">
        <button class="btn btn-outline" style="font-size:0.7rem;padding:0.25rem 0.5rem" onclick="deleteGroup('${type}', this)">Remove Group</button>
      </div>
    </div>
    <div class="req-items"></div>
    <div class="add-item-form">
      <input type="text" class="add-item-name" placeholder="Document name" value="">
      <input type="text" class="add-item-desc" placeholder="Description">
      <select class="add-item-required">
        <option value="1">Required</option>
        <option value="0">Optional</option>
      </select>
      <button class="btn btn-primary" style="font-size:0.75rem;padding:0.5rem 1rem" onclick="addItem('${type}', this)">+ Add</button>
    </div>
  `;
  container.appendChild(div);
}

function addItem(type, btn) {
  const group = btn.closest('.req-group');
  const items = group.querySelector('.req-items');
  const nameInput = group.querySelector('.add-item-name');
  const descInput = group.querySelector('.add-item-desc');
  const reqSelect = group.querySelector('.add-item-required');

  const name = nameInput.value.trim();
  if (!name) { alert('Enter a document name.'); return; }

  const desc = descInput.value.trim();
  const required = reqSelect.value === '1';

  const row = document.createElement('div');
  row.className = 'req-item-row';
  row.innerHTML = `
    <span class="req-item-name">${escHtml(name)}</span>
    <span class="req-item-desc">${escHtml(desc)}</span>
    <span class="req-item-required"><span class="badge ${required ? 'badge-green' : 'badge-yellow'}">${required ? 'Required' : 'Optional'}</span></span>
    <button class="req-item-delete" onclick="deleteItem(this)" title="Delete item">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </button>
  `;
  items.appendChild(row);

  // Clear inputs
  nameInput.value = '';
  descInput.value = '';
}

function deleteItem(btn) {
  const row = btn.closest('.req-item-row');
  row.style.transition = 'opacity 0.2s, transform 0.2s';
  row.style.opacity = '0';
  row.style.transform = 'translateX(20px)';
  setTimeout(() => row.remove(), 200);
}

function deleteGroup(type, btn) {
  if (!confirm('Remove this group and all its items?')) return;
  const group = btn.closest('.req-group');
  group.style.transition = 'opacity 0.2s, transform 0.2s';
  group.style.opacity = '0';
  group.style.transform = 'translateX(20px)';
  setTimeout(() => {
    group.remove();
    // Show empty state if no groups left
    const container = document.getElementById('groups-' + type);
    if (!container.querySelector('.req-group')) {
      const empty = document.getElementById('empty-' + type);
      if (empty) empty.style.display = '';
    }
  }, 200);
}

async function saveRequirements(type) {
  const container = document.getElementById('groups-' + type);
  const groups = container.querySelectorAll('.req-group');
  const msgEl = document.getElementById('save-msg-' + type);

  const categories = [];
  groups.forEach(g => {
    const nameEl = g.querySelector('.group-name-label');
    const name = nameEl ? nameEl.textContent.trim() : 'General';
    const items = [];
    g.querySelectorAll('.req-item-row').forEach(row => {
      const name = row.querySelector('.req-item-name').textContent.trim();
      const desc = row.querySelector('.req-item-desc').textContent.trim();
      const reqBadge = row.querySelector('.badge');
      const required = reqBadge ? reqBadge.classList.contains('badge-green') : true;
      items.push({ name, desc, required });
    });
    categories.push({ name, items });
  });

  msgEl.textContent = 'Saving…';
  msgEl.style.color = 'var(--text-light)';

  try {
    const res = await fetch(`${BASE}/admin/api/manage-requirements.php`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
      body: `action=save&service_type=${encodeURIComponent(type)}&categories=${encodeURIComponent(JSON.stringify(categories))}&_csrf=${encodeURIComponent(window.LOTOKS_CONFIG?.CSRF_TOKEN || '')}`
    });
    const data = await res.json();
    if (data.success) {
      msgEl.textContent = '✓ Requirements saved!';
      msgEl.style.color = 'var(--success)';
    } else {
      msgEl.textContent = '✗ Error: ' + (data.message || 'Save failed');
      msgEl.style.color = 'var(--danger)';
    }
  } catch (e) {
    msgEl.textContent = '✗ Network error.';
    msgEl.style.color = 'var(--danger)';
  }
}

function escHtml(str) {
  const div = document.createElement('div');
  div.textContent = String(str || '');
  return div.innerHTML;
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
