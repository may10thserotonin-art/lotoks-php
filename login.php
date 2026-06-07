<?php
/**
 * Lotoks — Login Page (login.php)
 * Converted from pages/Login.tsx
 * Handles user login with PHP sessions.
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/db/connect.php';

// Already logged in → redirect to the right place
if (is_admin_logged_in()) {
    redirect('/admin/index.php');
}
if (is_user_logged_in()) {
    $r = $_GET['redirect'] ?? '';
    if (!$r || !str_starts_with($r, BASE)) {
        $r = '/dashboard.php';
    } else {
        $r = substr($r, strlen(BASE));
        if ($r === '' || $r === false) {
            $r = '/dashboard.php';
        }
    }
    redirect($r);
}


$error       = '';
$redirect_to = $_GET['redirect'] ?? '';
if (!$redirect_to || !str_starts_with($redirect_to, BASE)) {
    $redirect_to = '/dashboard.php';
} else {
    // Strip BASE prefix if present (eligibility.php and requireUserAuth
    // already include it). redirect() will re-add BASE, so we need the
    // root-relative form here.
    $redirect_to = substr($redirect_to, strlen(BASE));
    if ($redirect_to === '' || $redirect_to === false) {
        $redirect_to = '/dashboard.php';
    }
}
$redirect_to = htmlspecialchars($redirect_to);

// Handle POST login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ── CSRF Verification ─────────────────────────────────────────
    // (The form renders csrf_field() but it was never verified!)
    if (!csrf_verify()) {
        $error = 'Invalid session token. Please reload the page and try again.';
    }

    // ── Rate limiting check (per-IP) ──────────────────────────────
    if (!$error && isRateLimited('login_user')) {
        $error = 'Too many login attempts from this IP address. Please try again in 15 minutes.';
    }

    if (!$error) {
        $email    = strtolower(trim($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        if (!$email || !$password) {
            $error = 'Email and password are required.';
        } else {
            // ── Account lockout check (per-account) ──────────────
            $lockStatus = checkAccountLocked($email, 'login_user');
            if ($lockStatus['locked']) {
                $error = $lockStatus['message'];
            }

            if (!$error) {
                $db = getDb();

                // ── 1. Check admins table first ───────────────────────
                $stmt = $db->prepare('SELECT id, name, email, role, password_hash, login_attempts, locked_until FROM admins WHERE email = ?');
                $stmt->execute([$email]);
                $admin = $stmt->fetch();

                if ($admin && $admin['password_hash'] && verify_password($password, $admin['password_hash'])) {
                    // Admin login success → clear lockout, set admin session
                    clearAccountLock($email, 'login_admin');
                    recordAttempt('login_user', true, $email);
                    admin_login($admin);
                    redirect('/admin/index.php');
                }

                // ── 2. Fall through to regular user check ─────────────
                $stmt = $db->prepare('SELECT id, name, email, country, password_hash, created_at, suspended, login_attempts, locked_until FROM users WHERE email = ?');
                $stmt->execute([$email]);
                $user = $stmt->fetch();

                if (!$user || !$user['password_hash']) {
                    $error = 'Invalid email or password.';
                    recordAttempt('login_user', false, $email);
                } elseif (!verify_password($password, $user['password_hash'])) {
                    $error = 'Invalid email or password.';
                    recordAttempt('login_user', false, $email);
                } elseif (!empty($user['suspended'])) {
                    $error = 'Your account has been suspended. Please contact support for assistance.';
                    recordAttempt('login_user', false, $email);
                } else {
                    // Success → clear lockout, log in
                    clearAccountLock($email, 'login_user');
                    recordAttempt('login_user', true, $email);
                    user_login($user);
                    redirect($redirect_to);
                }
            }
        }
    }
}


$page_title       = 'Sign In | Lotoks';
$page_description = 'Sign in to your Lotoks account to access your dashboard, applications, and opportunities.';
require_once __DIR__ . '/includes/head.php';
?>

<style>
.login-page-bg {
  min-height: 100vh;
  background: var(--color-navy);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 1.5rem;
  position: relative;
  overflow: hidden;
}

.login-page-bg::before {
  content: '';
  position: absolute;
  top: -10rem;
  left: -10rem;
  width: min(30rem, 75vw);
  height: min(30rem, 75vw);
  background: rgba(201,164,75,0.06);
  border-radius: 50%;
  filter: blur(60px);
  pointer-events: none;
}

.login-page-bg::after {
  content: '';
  position: absolute;
  bottom: -10rem;
  right: -10rem;
  width: min(30rem, 75vw);
  height: min(30rem, 75vw);
  background: rgba(35,73,225,0.06);
  border-radius: 50%;
  filter: blur(60px);
  pointer-events: none;
}

.dev-pin-btn {
  position: absolute;
  top: 1rem;
  right: 1rem;
  width: 2rem;
  height: 2rem;
  border-radius: 50%;
  background: rgba(201,164,75,0.08);
  border: 1px solid rgba(201,164,75,0.15);
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  opacity: 0;
  transition: opacity 0.2s;
}
.login-card:hover .dev-pin-btn { opacity: 1; }

.pw-wrapper { position: relative; }
</style>

<div class="login-page-bg">
  <!-- Back to home -->
  <a href="<?= BASE ?>/" style="position:absolute;top:1.5rem;left:1.5rem;display:flex;align-items:center;gap:0.5rem;color:rgba(255,255,255,0.4);font-size:0.875rem;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
    Back to Home
  </a>

  <div class="login-card" style="width:100%;max-width:22rem;position:relative;">
    <!-- Logo -->
    <div style="text-align:center;margin-bottom:2rem;">
      <a href="<?= BASE ?>/" style="display:inline-flex;align-items:center;gap:0.5rem;text-decoration:none;">
        <div style="width:3rem;height:3rem;border-radius:0.75rem;overflow:hidden;">
          <img src="<?= BASE ?>/public/logo.png" alt="Lotoks" style="width:100%;height:100%;object-fit:contain;" />
        </div>
        <span style="font-family:var(--font-heading);font-size:1.5rem;font-weight:700;color:#fff;">
          Lotoks<span style="color:var(--color-gold);">.</span>
        </span>
      </a>
    </div>

    <h1 style="font-family:var(--font-heading);font-size:1.5rem;font-weight:700;color:#fff;margin-bottom:0.25rem;">Welcome back</h1>
    <p style="color:rgba(255,255,255,0.5);font-size:0.875rem;margin-bottom:1.5rem;">Sign in to your Lotoks account</p>

    <!-- Flash / error -->
    <?php if ($error): ?>
      <div class="alert alert-error" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <?php render_flash(); ?>

    <!-- Login form -->
    <form method="POST" action="" id="login-form" novalidate>
      <?= csrf_field() ?>
      <input type="hidden" name="redirect" value="<?= htmlspecialchars($redirect_to) ?>" />

      <div class="form-group">
        <label class="form-label" for="email">Email address</label>
        <div class="input-icon-wrapper">
          <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
          <input
            type="email"
            id="email"
            name="email"
            class="form-input"
            placeholder="you@example.com"
            value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
            autocomplete="email"
            required
          />
        </div>
      </div>

      <div class="form-group">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:0.5rem;">
          <label class="form-label" for="password" style="margin-bottom:0;">Password</label>
          <a href="<?= BASE ?>/forgot-password.php" style="font-size:0.75rem;color:var(--color-gold);text-decoration:none;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">Forgot password?</a>
        </div>
        <div class="input-icon-wrapper pw-wrapper">
          <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
          <input
            type="password"
            id="password"
            name="password"
            class="form-input"
            placeholder="••••••••"
            autocomplete="current-password"
            required
            style="padding-right:2.75rem;"
          />
          <button type="button" class="pw-toggle" data-pw-toggle="password" aria-label="Toggle password visibility">
            <svg class="eye-on" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            <svg class="eye-off" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
          </button>
        </div>
      </div>

      <button type="submit" class="btn btn-primary btn-full btn-pill" id="login-submit" style="margin-top:0.5rem;">
        Sign In
      </button>
    </form>

    <!-- Divider -->
    <div class="divider"><span>or</span></div>

    <!-- Register link -->
    <p style="text-align:center;font-size:0.875rem;color:rgba(255,255,255,0.5);">
      Don't have an account?
      <a href="<?= BASE ?>/register.php" style="color:var(--color-gold);text-decoration:none;font-weight:600;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">Create one</a>
    </p>

    <!-- Dev PIN trigger (secret — only shown on hover of card, matches original Login.tsx) -->
    <button
      class="dev-pin-btn"
      id="dev-pin-open-btn"
      aria-label="Developer access"
      title="Developer PIN"
    >
      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
    </button>
  </div>

  <!-- Mini footer -->
  <div style="margin-top:2rem;display:flex;align-items:center;gap:1.5rem;">
    <a href="<?= BASE ?>/privacy.php"  style="color:rgba(255,255,255,0.3);font-size:0.8rem;text-decoration:none;padding:0.625rem 0.5rem;min-height:44px;display:inline-flex;align-items:center;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.3)'">Privacy</a>
    <a href="<?= BASE ?>/terms.php"    style="color:rgba(255,255,255,0.3);font-size:0.8rem;text-decoration:none;padding:0.625rem 0.5rem;min-height:44px;display:inline-flex;align-items:center;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.3)'">Terms</a>
    <a href="<?= BASE ?>/contact.php"  style="color:rgba(255,255,255,0.3);font-size:0.8rem;text-decoration:none;padding:0.625rem 0.5rem;min-height:44px;display:inline-flex;align-items:center;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.3)'">Contact</a>
  </div>

  <!-- ── Developer PIN Modal (matches Login.tsx PIN=091344) ── -->
  <div id="dev-modal-overlay" class="dev-modal-overlay" style="display:none;">
    <div class="dev-modal">
      <div class="dev-modal-header">
        <div style="display:flex;align-items:center;gap:0.5rem;">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-gold)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle></svg>
          <span style="font-family:var(--font-heading);font-size:0.9rem;font-weight:600;color:#fff;">Developer Access</span>
        </div>
        <button id="dev-modal-close" style="background:none;border:none;color:rgba(255,255,255,0.4);cursor:pointer;padding:0.25rem;">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>
      </div>
      <div style="padding:1.25rem 1.5rem;">
        <p style="color:rgba(255,255,255,0.5);font-size:0.8rem;margin-bottom:1rem;text-align:center;">Enter 6-digit PIN to access dev mode</p>
        <div class="pin-inputs" id="pin-inputs" style="display:flex;gap:0.5rem;justify-content:center;margin-bottom:1rem;">
          <?php for ($i = 0; $i < 6; $i++): ?>
            <input type="tel" maxlength="1" class="pin-input" id="pin-<?= $i ?>" autocomplete="off" inputmode="numeric" pattern="[0-9]" />
          <?php endfor; ?>
        </div>
        <div id="pin-error" style="display:none;color:var(--color-red);font-size:0.75rem;text-align:center;margin-bottom:0.75rem;">Incorrect PIN. Try again.</div>
        <button id="pin-submit" class="btn btn-primary btn-full btn-sm btn-pill">Verify PIN</button>
      </div>
    </div>
  </div>
</div>

<script>
(function () {
  // ── Dev PIN modal ──
  const DEV_PIN = '091344';
  const openBtn  = document.getElementById('dev-pin-open-btn');
  const overlay  = document.getElementById('dev-modal-overlay');
  const closeBtn = document.getElementById('dev-modal-close');
  const pinInputs = document.querySelectorAll('.pin-input');
  const pinSubmit = document.getElementById('pin-submit');
  const pinError  = document.getElementById('pin-error');
  const pinWrap   = document.getElementById('pin-inputs');

  openBtn?.addEventListener('click', () => { overlay.style.display = 'flex'; pinInputs[0]?.focus(); });
  closeBtn?.addEventListener('click', () => { overlay.style.display = 'none'; clearPin(); });
  overlay?.addEventListener('click', (e) => { if (e.target === overlay) { overlay.style.display = 'none'; clearPin(); } });

  function clearPin() {
    pinInputs.forEach(i => { i.value = ''; i.classList.remove('filled','error'); });
    pinError.style.display = 'none';
    pinInputs[0]?.focus();
  }

  // Auto-advance on digit input
  pinInputs.forEach((input, idx) => {
    input.addEventListener('input', () => {
      const val = input.value.replace(/\D/g, '');
      input.value = val.slice(-1);
      if (val) {
        input.classList.add('filled');
        pinInputs[idx + 1]?.focus();
      } else {
        input.classList.remove('filled');
      }
      pinError.style.display = 'none';
    });

    input.addEventListener('keydown', (e) => {
      if (e.key === 'Backspace' && !input.value && idx > 0) {
        pinInputs[idx - 1].value = '';
        pinInputs[idx - 1].classList.remove('filled');
        pinInputs[idx - 1].focus();
      }
    });
  });

  function verifyPin() {
    const entered = Array.from(pinInputs).map(i => i.value).join('');
    if (entered === DEV_PIN) {
      // Set mock user in session via AJAX, then respect the ?redirect= parameter
      const params = new URLSearchParams(window.location.search);
      const redirectTarget = params.get('redirect')
        ? decodeURIComponent(params.get('redirect'))
        : ('<?= BASE ?>' + '/dashboard.php');
      fetch('<?= BASE ?>/api/dev-login.php', { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ pin: entered }), credentials: 'same-origin' })
        .then(r => r.json())
        .then(d => {
          if (d.success) { window.location.href = redirectTarget; }
          else { showPinError(); }
        })
        .catch(() => showPinError());
    } else {
      showPinError();
    }
  }

  function showPinError() {
    pinInputs.forEach(i => i.classList.add('error'));
    pinError.style.display = 'block';
    pinWrap.classList.add('shake');
    setTimeout(() => { pinWrap.classList.remove('shake'); }, 400);
    setTimeout(clearPin, 800);
  }

  pinSubmit?.addEventListener('click', verifyPin);
  document.addEventListener('keydown', (e) => {
    if (overlay.style.display !== 'none' && e.key === 'Enter') verifyPin();
  });
})();

// Spinner on form submit
document.getElementById('login-form')?.addEventListener('submit', function () {
  const btn = document.getElementById('login-submit');
  setButtonLoading(btn, true);
});
</script>

<?php require_once __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
