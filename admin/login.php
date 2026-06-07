<?php
/**
 * Lotoks — Admin Login (admin/login.php)
 * Refined UI with full responsiveness, password visibility toggle,
 * loading state, decorative background, and micro-interactions.
 */
require_once dirname(__DIR__) . '/includes/auth.php';
require_once dirname(__DIR__) . '/db/connect.php';

if (is_admin_logged_in()) {
    redirect('/admin/index.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ── CSRF Verification ──────────────────────────────────────────
    csrf_verify_or_fail();

    // ── Rate limiting check (per-IP) ───────────────────────────────
    if (!$error && isRateLimited('login_admin')) {
        $error = 'Too many login attempts from this IP address. Please try again in 15 minutes.';
    }

    if (!$error) {
        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($email && $password) {
            // ── Account lockout check (per-account) ───────────────
            $lockStatus = checkAccountLocked($email, 'login_admin');
            if ($lockStatus['locked']) {
                $error = $lockStatus['message'];
            }

            if (!$error) {
                $db = getDb();
                $stmt = $db->prepare("SELECT * FROM admins WHERE email = ?");
                $stmt->execute([$email]);
                $admin = $stmt->fetch();

                if ($admin && password_verify($password, $admin['password_hash'])) {
                    clearAccountLock($email, 'login_admin');
                    recordAttempt('login_admin', true, $email);
                    admin_login($admin);
                    log_activity(null, $admin['id'], 'admin_login', 'Admin logged in');
                    redirect('/admin/index.php');
                } else {
                    $error = 'Invalid email or password.';
                    recordAttempt('login_admin', false, $email);
                }
            }
        } else {
            $error = 'Please enter both email and password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
  <title>Admin Login | Lotoks</title>

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="<?= BASE ?>/public/favicon.svg" />
  <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE ?>/public/logo.png" />
  <link rel="apple-touch-icon" href="<?= BASE ?>/public/logo.png" />

  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="<?= BASE ?>/admin/assets/css/admin.css">
  <style>
    /* ── Reset body for login ─────────────────────── */
    body.login-page {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      background: linear-gradient(135deg, #0b1d3a 0%, #0f2a4a 30%, #132f52 60%, #0b1d3a 100%);
      padding: 1rem;
      position: relative;
      overflow-x: hidden;
    }

    /* ── Decorative background elements ──────────── */
    body.login-page::before {
      content: '';
      position: fixed;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background-image:
        radial-gradient(ellipse at 20% 50%, rgba(201,164,75,0.06) 0%, transparent 50%),
        radial-gradient(ellipse at 80% 20%, rgba(201,164,75,0.04) 0%, transparent 50%),
        radial-gradient(ellipse at 50% 80%, rgba(255,255,255,0.03) 0%, transparent 50%);
      animation: bgDrift 20s ease-in-out infinite alternate;
      pointer-events: none;
      z-index: 0;
    }

    @keyframes bgDrift {
      0%   { transform: translate(0, 0) rotate(0deg); }
      100% { transform: translate(2%, 1%) rotate(3deg); }
    }

    /* Subtle grid overlay */
    body.login-page::after {
      content: '';
      position: fixed;
      inset: 0;
      background-image:
        linear-gradient(rgba(255,255,255,0.015) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,0.015) 1px, transparent 1px);
      background-size: 60px 60px;
      pointer-events: none;
      z-index: 0;
    }

    /* ── Login wrapper ───────────────────────────── */
    .login-wrapper {
      width: 100%;
      max-width: 420px;
      position: relative;
      z-index: 1;
      animation: fadeUp 0.5s ease;
    }

    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    /* ── Login box ───────────────────────────────── */
    .login-box {
      background: white;
      padding: 2.5rem 2rem;
      border-radius: 1.25rem;
      width: 100%;
      box-shadow:
        0 25px 50px rgba(0,0,0,0.3),
        0 0 0 1px rgba(255,255,255,0.05);
      transition: padding 0.3s;
    }

    .login-logo {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      font-size: 1.6rem;
      font-weight: 800;
      color: var(--navy);
      margin-bottom: 0.25rem;
      letter-spacing: -0.02em;
    }
    .login-logo svg {
      width: 28px;
      height: 28px;
      stroke: var(--gold);
    }
    .login-logo span { color: var(--gold); }

    .login-subtitle {
      text-align: center;
      font-size: 0.85rem;
      color: #9ca3af;
      margin-bottom: 2rem;
      font-weight: 500;
    }

    /* ── Error alert ─────────────────────────────── */
    .error-alert {
      background: #fef2f2;
      color: #b91c1c;
      padding: 0.75rem 1rem;
      border-radius: 0.625rem;
      margin-bottom: 1.5rem;
      font-size: 0.85rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 0.625rem;
      border: 1px solid #fecaca;
      animation: shakeX 0.4s ease;
    }

    @keyframes shakeX {
      0%, 100% { transform: translateX(0); }
      20%      { transform: translateX(-6px); }
      40%      { transform: translateX(6px); }
      60%      { transform: translateX(-4px); }
      80%      { transform: translateX(4px); }
    }

    /* ── Form groups ─────────────────────────────── */
    .form-group {
      margin-bottom: 1.25rem;
    }
    .form-group label {
      display: block;
      font-size: 0.75rem;
      font-weight: 700;
      color: #6b7280;
      text-transform: uppercase;
      margin-bottom: 0.5rem;
      letter-spacing: 0.03em;
    }

    /* Input wrapper (for icons + toggle) */
    .input-wrap {
      position: relative;
      display: flex;
      align-items: center;
    }
    .input-wrap .input-icon {
      position: absolute;
      left: 0.875rem;
      color: #9ca3af;
      pointer-events: none;
      display: flex;
      align-items: center;
      transition: color 0.2s;
    }
    .input-wrap:focus-within .input-icon {
      color: var(--gold);
    }

    .form-group input {
      width: 100%;
      padding: 0.75rem 1rem 0.75rem 2.75rem;
      border: 1.5px solid #e5e7eb;
      border-radius: 0.625rem;
      font-family: inherit;
      font-size: 0.95rem;
      color: #1f2937;
      background: #fafafa;
      transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
      -webkit-appearance: none;
      appearance: none;
    }
    .form-group input::placeholder {
      color: #c4cbd5;
      font-size: 0.85rem;
    }
    .form-group input:focus {
      outline: none;
      border-color: var(--gold);
      box-shadow: 0 0 0 4px rgba(201,164,75,0.12);
      background: white;
    }
    .form-group input:hover:not(:focus) {
      border-color: #d1d5db;
      background: #fcfcfc;
    }

    /* ── Password toggle ─────────────────────────── */
    .password-toggle {
      position: absolute;
      right: 0.75rem;
      background: none;
      border: none;
      cursor: pointer;
      padding: 0.25rem;
      color: #9ca3af;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 0.375rem;
      transition: color 0.2s, background 0.2s;
    }
    .password-toggle:hover {
      color: #6b7280;
      background: rgba(0,0,0,0.04);
    }
    .password-toggle:focus-visible {
      outline: 2px solid var(--gold);
      outline-offset: 2px;
    }

    /* ── Forgot password link ────────────────────── */
    .forgot-row {
      display: flex;
      justify-content: flex-end;
      margin-top: -0.5rem;
      margin-bottom: 1.5rem;
    }
    .forgot-link {
      font-size: 0.78rem;
      font-weight: 600;
      color: #6b7280;
      text-decoration: none;
      transition: color 0.2s;
    }
    .forgot-link:hover {
      color: var(--gold);
    }

    /* ── Submit button ───────────────────────────── */
    .btn-login {
      width: 100%;
      justify-content: center;
      padding: 0.8rem;
      font-size: 1rem;
      font-weight: 700;
      border-radius: 0.625rem;
      position: relative;
      transition: all 0.25s;
    }
    .btn-login:active {
      transform: scale(0.98);
    }

    /* Button loading spinner */
    .btn-login .btn-text { transition: opacity 0.2s; }
    .btn-login.loading .btn-text { opacity: 0; }
    .btn-login .btn-spinner {
      position: absolute;
      inset: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: opacity 0.2s;
    }
    .btn-login.loading .btn-spinner { opacity: 1; }
    .btn-login.loading { pointer-events: none; }

    .spinner {
      width: 1.25rem;
      height: 1.25rem;
      border: 2px solid rgba(11,29,58,0.2);
      border-top-color: var(--navy);
      border-radius: 50%;
      animation: spin 0.6s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* ── Divider ────────────────────────────────── */
    .login-divider {
      text-align: center;
      margin: 1.5rem 0;
      font-size: 0.75rem;
      color: #d1d5db;
      position: relative;
      font-weight: 500;
    }
    .login-divider::before,
    .login-divider::after {
      content: '';
      position: absolute;
      top: 50%;
      width: calc(50% - 2.5rem);
      height: 1px;
      background: #e5e7eb;
    }
    .login-divider::before { left: 0; }
    .login-divider::after { right: 0; }

    /* ── Back to site ────────────────────────────── */
    .back-link {
      display: inline-flex;
      align-items: center;
      gap: 0.375rem;
      font-size: 0.8rem;
      font-weight: 600;
      color: #9ca3af;
      text-decoration: none;
      transition: color 0.2s;
    }
    .back-link:hover {
      color: var(--gold);
    }

    /* ── Footer ─────────────────────────────────── */
    .login-footer {
      text-align: center;
      margin-top: 1.5rem;
      font-size: 0.72rem;
      color: rgba(255,255,255,0.35);
      font-weight: 500;
      letter-spacing: 0.01em;
    }
    .login-footer a {
      color: var(--gold);
      text-decoration: none;
      transition: color 0.2s;
    }
    .login-footer a:hover {
      color: #d4af5a;
      text-decoration: underline;
    }

    /* ── Toast / auto-dismiss message ────────────── */
    .toast {
      background: #111827;
      color: white;
      padding: 0.75rem 1.25rem;
      border-radius: 0.75rem;
      font-size: 0.85rem;
      font-weight: 600;
      position: fixed;
      bottom: 2rem;
      left: 50%;
      transform: translateX(-50%) translateY(100px);
      opacity: 0;
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
      box-shadow: 0 10px 25px rgba(0,0,0,0.3);
      z-index: 200;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      white-space: nowrap;
    }
    .toast.show {
      transform: translateX(-50%) translateY(0);
      opacity: 1;
    }

    /* ── Responsive ──────────────────────────────── */

    /* Tablet & small laptop */
    @media (max-width: 640px) {
      .login-box {
        padding: 2rem 1.5rem;
        border-radius: 1rem;
      }
      .login-logo {
        font-size: 1.35rem;
      }
      .login-logo svg {
        width: 24px;
        height: 24px;
      }
      .login-subtitle {
        font-size: 0.8rem;
        margin-bottom: 1.5rem;
      }
      .form-group input {
        padding: 0.7rem 1rem 0.7rem 2.5rem;
        font-size: 0.9rem;
      }
      .btn-login {
        padding: 0.7rem;
        font-size: 0.95rem;
      }
      body.login-page {
        padding: 0.75rem;
      }
      .login-footer {
        font-size: 0.65rem;
      }
    }

    /* Very small screens */
    @media (max-width: 380px) {
      .login-box {
        padding: 1.5rem 1rem;
        border-radius: 0.875rem;
      }
      .login-logo {
        font-size: 1.2rem;
      }
      .login-logo svg {
        width: 20px;
        height: 20px;
      }
      .form-group {
        margin-bottom: 1rem;
      }
      .form-group input {
        padding: 0.65rem 0.75rem 0.65rem 2.25rem;
        font-size: 0.85rem;
      }
      .input-wrap .input-icon {
        left: 0.65rem;
      }
      .input-wrap .input-icon svg {
        width: 14px;
        height: 14px;
      }
      .btn-login {
        padding: 0.65rem;
        font-size: 0.875rem;
      }
    }

    /* Landscape phones */
    @media (max-height: 500px) {
      body.login-page {
        align-items: flex-start;
        padding-top: 1rem;
      }
      .login-wrapper {
        animation: none;
      }
      .login-box {
        padding: 1.25rem 1.25rem;
      }
      .login-logo {
        margin-bottom: 0;
      }
      .login-subtitle {
        margin-bottom: 1rem;
      }
      .form-group {
        margin-bottom: 0.75rem;
      }
      .forgot-row {
        margin-bottom: 1rem;
      }
      .login-divider {
        margin: 1rem 0;
      }
      .login-footer {
        margin-top: 1rem;
      }
    }
  </style>
</head>
<body class="login-page">

  <div class="login-wrapper">
    <div class="login-box">
      <!-- Logo -->
      <div class="login-logo">
        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
        </svg>
        Lotoks<span>.</span>
      </div>
      <p class="login-subtitle">Admin panel — sign in to continue</p>

      <!-- Error -->
      <?php if ($error): ?>
      <div class="error-alert" id="error-alert">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;">
          <circle cx="12" cy="12" r="10"/>
          <line x1="12" y1="8" x2="12" y2="12"/>
          <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <?= htmlspecialchars($error) ?>
      </div>
      <?php endif; ?>

      <!-- Form -->
      <form method="POST" action="" id="login-form" novalidate>
        <?= csrf_field() ?>

        <!-- Email -->
        <div class="form-group">
          <label for="email">Email Address</label>
          <div class="input-wrap">
            <span class="input-icon">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="2" y="4" width="20" height="16" rx="2"/>
                <path d="M22 4l-10 8L2 4"/>
              </svg>
            </span>
            <input type="email" id="email" name="email" required autofocus
                   placeholder="admin@lotoks.com"
                   value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                   autocomplete="email">
          </div>
        </div>

        <!-- Password -->
        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-wrap">
            <span class="input-icon">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <rect x="3" y="11" width="18" height="11" rx="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
              </svg>
            </span>
            <input type="password" id="password" name="password" required
                   placeholder="Enter your password"
                   autocomplete="current-password">
            <button type="button" class="password-toggle" id="pw-toggle"
                    aria-label="Toggle password visibility"
                    tabindex="-1">
              <!-- Eye (visible) -->
              <svg id="eye-open" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
              <!-- Eye-off (hidden) -->
              <svg id="eye-closed" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none;">
                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                <line x1="1" y1="1" x2="23" y2="23"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Forgot password -->
        <div class="forgot-row">
          <a href="<?= BASE ?>/forgot-password.php" class="forgot-link">
            Forgot password?
          </a>
        </div>

        <!-- Submit -->
        <button type="submit" class="btn btn-primary btn-login" id="login-btn">
          <span class="btn-text">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:middle;margin-right:0.25rem;">
              <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
              <polyline points="10 17 15 12 10 7"/>
              <line x1="15" y1="12" x2="3" y2="12"/>
            </svg>
            Sign In
          </span>
          <span class="btn-spinner"><span class="spinner"></span></span>
        </button>
      </form>

      <!-- Divider -->
      <div class="login-divider">admin portal</div>

      <!-- Back to site -->
      <div style="text-align:center;">
        <a href="<?= BASE ?>/" class="back-link">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <line x1="19" y1="12" x2="5" y2="12"/>
            <polyline points="12 19 5 12 12 5"/>
          </svg>
          Back to website
        </a>
      </div>
    </div>

    <div class="login-footer">
      Powered by <a href="<?= BASE ?>/">Lotoks</a> &bull; &copy; <?= date('Y') ?> Lotoks Portal
    </div>
  </div>

  <script>
  (function() {
    'use strict';

    /* ── Password toggle ───────────────────────── */
    const pwInput  = document.getElementById('password');
    const pwToggle = document.getElementById('pw-toggle');
    const eyeOpen  = document.getElementById('eye-open');
    const eyeClosed = document.getElementById('eye-closed');

    if (pwToggle && pwInput) {
      pwToggle.addEventListener('click', function() {
        const isPassword = pwInput.type === 'password';
        pwInput.type = isPassword ? 'text' : 'password';
        eyeOpen.style.display   = isPassword ? 'none' : '';
        eyeClosed.style.display = isPassword ? '' : 'none';
      });
    }

    /* ── Loading state on submit ────────────────── */
    const form     = document.getElementById('login-form');
    const btn      = document.getElementById('login-btn');

    if (form && btn) {
      form.addEventListener('submit', function() {
        // Only show loading if the form is valid
        if (form.checkValidity()) {
          btn.classList.add('loading');
          btn.disabled = true;
        }
      });
    }

  })();
  </script>

</body>
</html>
