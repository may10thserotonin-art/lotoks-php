<?php
/**
 * Lotoks — Forgot Password (forgot-password.php)
 * Converted from pages/ForgotPassword.tsx
 * Handles requesting a password reset link.
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/db/connect.php';

redirect_if_logged_in();

$error   = '';
$success = '';
$devLink = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_verify_or_fail();

    $email = strtolower(trim($_POST['email'] ?? ''));

    if (!$email) {
        $error = 'Email address is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        $db = getDb();
        $stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            // Don't reveal email existence for security, but say it was sent.
            $success = 'If that email exists, a password reset link has been sent.';
        } else {
            // Generate token and expiration (1 hour from now)
            $token   = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', time() + 3600);

            $update = $db->prepare('UPDATE users SET reset_token = ?, reset_token_expires = ? WHERE email = ?');
            $update->execute([$token, $expires, $email]);

            // ── Send email (or log if disabled) ─────────────────────
            $resetLink = SITE_URL . BASE . '/reset-password.php?token=' . urlencode($token);
            $subject   = 'Password Reset Request — Lotoks';
            $bodyHTML  = (require __DIR__ . '/includes/emails/password-reset.php')($resetLink);
            $emailResult = sendEmail($email, $subject, $bodyHTML);

            $success = 'If that email exists, a password reset link has been sent.';

            // For developer/local preview, expose the link so they can test it easily!
            $devLink = "/reset-password.php?token=" . urlencode($token);
        }
    }
}

$page_title       = 'Forgot Password | Lotoks';
$page_description = 'Reset your password to access your Lotoks account.';
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
</style>

<div class="login-page-bg">
  
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

    <h1 style="font-family:var(--font-heading);font-size:1.5rem;font-weight:700;color:#fff;margin-bottom:0.25rem;">Forgot Password</h1>
    <p style="color:rgba(255,255,255,0.5);font-size:0.875rem;margin-bottom:1.5rem;">Enter your email to receive a reset link</p>

    <!-- Error Alert -->
    <?php if ($error): ?>
      <div class="alert alert-error" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <!-- Success Alert -->
    <?php if ($success): ?>
      <div class="alert alert-success" role="alert" style="flex-direction:column; gap:0.5rem; text-align:left;">
        <div style="display:flex; align-items:center; gap:0.5rem;">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          <span style="font-weight:600;"><?= htmlspecialchars($success) ?></span>
        </div>
        
        <?php if ($devLink): ?>
          <div style="margin-top:0.75rem; border-top:1px solid rgba(34,197,94,0.2); padding-top:0.75rem; font-size:0.8rem;">
            <strong style="color:#fff; display:block; margin-bottom:0.25rem;">Developer Local Preview Link:</strong>
            <a href="<?= $devLink ?>" style="color:var(--color-gold); text-decoration:underline; font-weight:700; word-break:break-all;"><?= htmlspecialchars($devLink) ?></a>
          </div>
        <?php endif; ?>
      </div>
    <?php endif; ?>

    <?php if (!$success): ?>
      <form method="POST" action="" id="forgot-form" novalidate>
        <?= csrf_field() ?>

        <div class="form-group" style="margin-bottom:1.5rem;">
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
              required
            />
          </div>
        </div>

        <button type="submit" class="btn btn-primary btn-full btn-pill" id="forgot-submit">
          Send Reset Link
        </button>
      </form>
    <?php endif; ?>

    <!-- Back to Sign In -->
    <div style="text-align:center; margin-top:1.5rem; border-top:1px solid rgba(255,255,255,0.06); padding-top:1.25rem;">
      <a href="<?= BASE ?>/login.php" style="font-size:0.875rem; color:var(--color-gold); text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:0.25rem;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
        Back to Sign In
      </a>
    </div>
  </div>

  <!-- Mini footer -->
  <div style="margin-top:2rem;display:flex;align-items:center;gap:1.5rem;">
    <a href="<?= BASE ?>/privacy.php"  style="color:rgba(255,255,255,0.3);font-size:0.8rem;text-decoration:none;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.3)'">Privacy</a>
    <a href="<?= BASE ?>/terms.php"    style="color:rgba(255,255,255,0.3);font-size:0.8rem;text-decoration:none;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.3)'">Terms</a>
    <a href="<?= BASE ?>/contact.php"  style="color:rgba(255,255,255,0.3);font-size:0.8rem;text-decoration:none;" onmouseover="this.style.color='var(--color-gold)'" onmouseout="this.style.color='rgba(255,255,255,0.3)'">Contact</a>
  </div>
</div>

<script>
document.getElementById('forgot-form')?.addEventListener('submit', function () {
  const btn = document.getElementById('forgot-submit');
  setButtonLoading(btn, true);
});
</script>

<?php require_once __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
