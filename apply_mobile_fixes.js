const fs = require('fs');
const path = require('path');

const dir = 'c:/xampp/htdocs/lotoks';

// Helper to replace content in a file
function updateFile(filename, replacements) {
  const filepath = path.join(dir, filename);
  if (!fs.existsSync(filepath)) {
    console.log(`File not found: ${filepath}`);
    return;
  }
  
  let content = fs.readFileSync(filepath, 'utf8');
  let original = content;
  
  for (const { search, replace } of replacements) {
    if (typeof search === 'string') {
      content = content.split(search).join(replace);
    } else if (search instanceof RegExp) {
      content = content.replace(search, replace);
    }
  }
  
  if (content !== original) {
    fs.writeFileSync(filepath, content, 'utf8');
    console.log(`Updated ${filename}`);
  } else {
    console.log(`No changes made to ${filename}`);
  }
}

// 1. Fix index.php
updateFile('index.php', [
  // BUG-01: Hero blobs
  { search: 'width:24rem;height:24rem;', replace: 'width:min(24rem,60vw);height:min(24rem,60vw);' },
  { search: 'width:20rem;height:20rem;', replace: 'width:min(20rem,50vw);height:min(20rem,50vw);' },
  
  // BUG-04: Service cards height:20rem
  { search: 'height:20rem;display:block;text-decoration:none;', replace: 'min-height:16rem;display:flex;flex-direction:column;text-decoration:none;' },
  
  // BUG-05: How It Works container class
  { search: 'class="how-connector"', replace: 'class="how-connector how-steps-grid"' },
  { search: 'data-stagger-step="120"', replace: 'data-stagger-step="120" class="how-steps-grid"' }, // adding class to data-stagger container
  
  // Update inline styles in index.php for how-steps-grid
  { search: 'section [data-stagger] { grid-template-columns: repeat(2,1fr) !important; }', replace: '.how-steps-grid { grid-template-columns: repeat(2,1fr); }' },
  { search: 'section [data-stagger] { grid-template-columns: repeat(4,1fr) !important; }', replace: '.how-steps-grid { grid-template-columns: repeat(4,1fr); }' }
]);

// 2. Fix about.php
updateFile('about.php', [
  // BUG-01: Partners section blobs
  { search: 'width:24rem;height:24rem;', replace: 'width:min(24rem,60vw);height:min(24rem,60vw);' },
  { search: 'width:20rem;height:20rem;', replace: 'width:min(20rem,50vw);height:min(20rem,50vw);' },
]);

// 3. Fix login.php
updateFile('login.php', [
  // BUG-10: PIN input explicit fluid sizing -> CSS already has .pin-input, we just need to update it in style.css or login.php
  { search: '.pin-input {', replace: '.pin-input {\n  width: calc((min(22rem, 85vw) - 5 * 0.5rem) / 6);\n  flex-shrink: 0;' },
  { search: 'width: 2.75rem;', replace: '' }, // remove the old fixed width
  
  // BUG-11: Footer links touch targets
  { search: 'style="color:rgba(255,255,255,0.3);font-size:0.8rem;text-decoration:none;"', replace: 'style="color:rgba(255,255,255,0.3);font-size:0.8rem;text-decoration:none;padding:0.625rem 0.5rem;min-height:44px;display:inline-flex;align-items:center;"' }
]);

// 4. Fix dashboard.php
updateFile('dashboard.php', [
  // BUG-12: Add hamburger button
  { 
    search: '<header class="portal-topbar">', 
    replace: `<header class="portal-topbar">\n            <button class="sidebar-toggle-btn" id="sidebar-toggle" aria-label="Open navigation menu">\n                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">\n                    <line x1="3" y1="7" x2="21" y2="7"/>\n                    <line x1="3" y1="12" x2="21" y2="12"/>\n                    <line x1="3" y1="17" x2="21" y2="17"/>\n                </svg>\n            </button>`
  },
  // BUG-12: Add sidebar overlay
  { search: '<?php include __DIR__ . \'/includes/sidebar.php\'; ?>', replace: '<?php include __DIR__ . \'/includes/sidebar.php\'; ?>\n    <div class="sidebar-overlay" id="sidebar-overlay"></div>' }
]);

// 5. Update global CSS
updateFile('assets/css/style.css', [
  // BUG-03: Hero CTA
  { search: '.hero-cta {\n  display: flex;\n  flex-direction: column;\n  align-items: center;\n  gap: 1rem;\n  justify-content: center;\n  margin-bottom: 4rem;\n  animation: fadeUpIn 0.6s ease 0.3s both;\n}', replace: '.hero-cta {\n  display: flex;\n  flex-direction: column;\n  align-items: stretch;\n  gap: 0.75rem;\n  justify-content: center;\n  margin-bottom: 4rem;\n  width: 100%;\n  animation: fadeUpIn 0.6s ease 0.3s both;\n}' },
  { search: '@media (min-width: 640px) { .hero-cta { flex-direction: row; } }', replace: '@media (min-width: 640px) { .hero-cta { flex-direction: row; align-items: center; width: auto; gap: 1rem; } }' },
  
  // BUG-06 & BUG-07: Timeline (about.php)
  { search: '.timeline-dot {\n  position: absolute;\n  left: 1rem;\n  top: 0.5rem;\n  transform: translateX(-50%);', replace: '.timeline-dot {\n  position: absolute;\n  left: 0.5rem;\n  top: 0.5rem;\n  transform: none;' },
  { search: '@media (min-width: 768px) {\n  .timeline-item.left-aligned .timeline-card-content {\n    width: 45%;\n    text-align: right;\n  }\n  .timeline-item.right-aligned .timeline-card-content {\n    width: 45%;\n    margin-left: auto;\n    text-align: left;\n  }\n}', replace: '@media (min-width: 768px) {\n  .timeline-dot {\n    left: 50%;\n    transform: translateX(-50%);\n  }\n  .timeline-item.left-aligned .timeline-card-content {\n    width: calc(50% - 2rem);\n    text-align: right;\n  }\n  .timeline-item.right-aligned .timeline-card-content {\n    width: calc(50% - 2rem);\n    margin-left: auto;\n    margin-right: 0;\n    text-align: left;\n  }\n}' },
  
  // BUG-08: Mission Grid (about.php)
  { search: '@media (min-width: 1024px) {\n  .mission-grid { grid-template-columns: 1.2fr 0.8fr !important; }\n}', replace: '@media (min-width: 768px) {\n  .mission-grid { grid-template-columns: 1fr 1fr; }\n}\n@media (min-width: 1024px) {\n  .mission-grid { grid-template-columns: 1.2fr 0.8fr; }\n}' },
  
  // BUG-09: Team Grid (about.php) - Fix Option B
  { search: '@media (min-width: 992px) { .team-grid { grid-template-columns: repeat(4, 1fr) !important; } }', replace: '@media (min-width: 992px) { .team-grid { grid-template-columns: repeat(4, 1fr); } }\n.team-grid > .team-member:last-child:nth-child(4n + 1) {\n  grid-column: 2 / 4;\n}' },
  { search: '@media (min-width: 576px) { .team-grid { grid-template-columns: repeat(2, 1fr) !important; } }', replace: '@media (min-width: 576px) { .team-grid { grid-template-columns: repeat(2, 1fr); } }\n@media (min-width: 576px) and (max-width: 991px) {\n  .team-grid > .team-member:last-child:nth-child(odd) {\n    grid-column: 1 / -1;\n    max-width: 280px;\n    margin-inline: auto;\n  }\n}' },

  // BUG-12: Dashboard Drawer CSS
  { search: '/* Mobile Tab Bar */', replace: `/* Sidebar Mobile Drawer */
.sidebar-toggle-btn {
  display: none;
  background: none;
  border: none;
  color: rgba(255,255,255,0.8);
  cursor: pointer;
  padding: 0.5rem;
  margin-right: 0.75rem;
  border-radius: 0.5rem;
  align-items: center;
  justify-content: center;
  min-width: 44px;
  min-height: 44px;
}
.sidebar-overlay {
  display: none;
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.55);
  z-index: 999;
  backdrop-filter: blur(2px);
}
@media (max-width: 767px) {
  .sidebar-toggle-btn { display: inline-flex; }
  .portal-sidebar {
    position: fixed; left: 0; top: 0; bottom: 0; z-index: 1000;
    transform: translateX(-100%); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow-y: auto;
  }
  .portal-sidebar.is-open { transform: translateX(0); }
  .sidebar-overlay.is-open { display: block; }
  .portal-main { margin-left: 0 !important; width: 100%; }
  .portal-topbar { padding-inline: 1rem; }
}
/* Mobile Tab Bar */` }
]);

console.log("Done applying mobile fixes.");
