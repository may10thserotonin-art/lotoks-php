<?php
/**
 * Lotoks — Reset Password (reset-password.php)
 * Converted from pages/ResetPassword.tsx
 * Handles resetting a password using a validation token.
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/db/connect.php';

redirect_if_logged_in();

$token = trim($_GET['token'] ?? $_POST['token'] ?? '');

$error   = '';
$success = '';
$token_valid = false;
$user_id = null;

if (!$token) {
    $error = 'Missing reset token. Please request a new password reset link.';
} else {
    // Check if token exists and is not expired
    $db = getDb();
    $stmt = $db->prepare('SELECT id FROM users WHERE reset_token = ? AND reset_token_expires > NOW()');
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if (!$user) {
        $error = 'Invalid or expired password reset link. Please request a new one.';
    } else {
        $token_valid = true;
        $user_id     = $user['id'];
    }
}

// Handle POST password reset submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $token_valid) {
    csrf_verify_or_fail();

    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    // Check strength server-side
    $hasUppercase = preg_match('@[A-Z]@', $password);
    $hasLowercase = preg_match('@[a-z]@', $password);
    $hasNumber    = preg_match('@[0-9]@', $password);
    $isLengthOk   = strlen($password) >= 8;

    if (!$password || !$confirm) {
        $error = 'Please fill out all fields.';
    } elseif (!$hasUppercase || !$hasLowercase || !$hasNumber || !$isLengthOk) {
        $error = 'Password does not meet all strength requirements.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        // Update user password and clear token fields
        $hashPassword = hash_password($password);
        $update = $db->prepare('UPDATE users SET password_hash = ?, reset_token = NULL, reset_token_expires = NULL WHERE id = ?');
        $update->execute([$hashPassword, $user_id]);

        $success = 'Password updated successfully!';
        
        // Disable form on success
        $token_valid = false;
    }
}

$page_title       = 'Reset Password | Lotoks';
$page_description = 'Enter your new password to regain access to your Lotoks account.';
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

/* Checklist items indicator */
.checklist-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  color: rgba(255,255,255,0.45);
  font-size: 0.75rem;
  margin-bottom: 0.35rem;
}
.checklist-item.valid {
  color: var(--color-teal);
}
.checklist-item.valid svg {
  color: var(--color-teal);
  stroke-width: 3;
}
.pw-wrapper { position: relative; }
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

    <h1 style="font-family:var(--font-heading);font-size:1.5rem;font-weight:700;color:#fff;margin-bottom:0.25rem;">Reset Password</h1>
    <p style="color:rgba(255,255,255,0.5);font-size:0.875rem;margin-bottom:1.5rem;">Choose a new password for your account</p>

    <!-- Error Alert -->
    <?php if ($error): ?>
      <div class="alert alert-error" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <!-- Success Alert -->
    <?php if ($success): ?>
      <div class="alert alert-success" role="alert" style="flex-direction:column; text-align:center; gap:0.5rem;">
        <div style="display:flex; flex-direction:column; align-items:center; width:100%;">
          <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:var(--color-teal); margin-bottom:0.5rem;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          <span style="font-weight:700; font-size:1.05rem;"><?= htmlspecialchars($success) ?></span>
          <span style="color:rgba(255,255,255,0.5); font-size:0.8rem; margin-top:0.25rem;">Redirecting to sign in...</span>
        </div>
      </div>
      <script>
        setTimeout(() => {
          window.location.href = '<?= BASE ?>/login.php';
        }, 2000);
      </script>
    <?php endif; ?>

    <?php if ($token_valid): ?>
      <form method="POST" action="" id="reset-form" novalidate>
        <?= csrf_field() ?>
        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>" />

        <!-- New Password -->
        <div class="form-group" style="margin-bottom:1.25rem;">
          <label class="form-label" for="password">New Password</label>
          <div class="input-icon-wrapper pw-wrapper">
            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            <input
              type="password"
              id="password"
              name="password"
              class="form-input"
              placeholder="Enter new password"
              required
              style="padding-right:2.75rem;"
            />
            <button type="button" class="pw-toggle" data-pw-toggle="password" aria-label="Toggle password visibility">
              <svg class="eye-on" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
              <svg class="eye-off" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
            </button>
          </div>
          
          <!-- Requirements Checklist -->
          <div style="margin-top:0.75rem; background:rgba(255,255,255,0.03); border:1px solid rgba(255,255,255,0.06); padding:0.75rem; border-radius:0.5rem; display:none;" id="checklist-box">
            <span style="font-weight:600; color:rgba(255,255,255,0.6); font-size:0.75rem; display:block; margin-bottom:0.35rem;">Password requirements:</span>
            
            <div class="checklist-item" id="req-length">
              <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              <span>At least 8 characters</span>
            </div>
            <div class="checklist-item" id="req-upper">
              <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              <span>At least one uppercase letter (A-Z)</span>
            </div>
            <div class="checklist-item" id="req-lower">
              <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              <span>At least one lowercase letter (a-z)</span>
            </div>
            <div class="checklist-item" id="req-number">
              <svg class="check-icon" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              <span>At least one number (0-9)</span>
            </div>
          </div>
        </div>

        <!-- Confirm Password -->
        <div class="form-group" style="margin-bottom:1.5rem;">
          <label class="form-label" for="confirm_password">Confirm New Password</label>
          <div class="input-icon-wrapper pw-wrapper">
            <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
            <input
              type="password"
              id="confirm_password"
              name="confirm_password"
              class="form-input"
              placeholder="Repeat new password"
              required
              style="padding-right:2.75rem;"
            />
            <button type="button" class="pw-toggle" data-pw-toggle="confirm_password" aria-label="Toggle password visibility">
              <svg class="eye-on" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
              <svg class="eye-off" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
            </button>
          </div>
          <span id="match-error" style="color:var(--color-red); font-size:0.75rem; margin-top:0.35rem; display:none;">Passwords do not match</span>
        </div>

        <button type="submit" class="btn btn-primary btn-full btn-pill" id="reset-submit" disabled>
          Reset Password
        </button>
      </form>
    <?php endif; ?>

    <!-- Back to Sign In -->
    <div style="text-align:center; margin-top:1.5rem; border-top:1px solid rgba(255,255,255,0.06); padding-top:1.25rem;">
      <a href="<?= BASE ?>/login.php" style="font-size:0.875rem; color:var(--color-gold); text-decoration:none; font-weight:600; display:inline-flex; align-items:center; gap:0.25rem;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">
        ← Back to Sign In
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
document.addEventListener('DOMContentLoaded', () => {
  const passwordInput = document.getElementById('password');
  const confirmInput  = document.getElementById('confirm_password');
  const matchError    = document.getElementById('match-error');
  const submitBtn     = document.getElementById('reset-submit');
  const checklistBox  = document.getElementById('checklist-box');
  const resetForm     = document.getElementById('reset-form');

  if (passwordInput && confirmInput && submitBtn) {
    
    function validateInputs() {
      const val = passwordInput.value;
      const confirmVal = confirmInput.value;

      if (val.length > 0) {
        checklistBox.style.display = 'block';
      } else {
        checklistBox.style.display = 'none';
      }

      // Password rules checking
      const lengthOk = val.length >= 8;
      const upperOk  = /[A-Z]/.test(val);
      const lowerOk  = /[a-z]/.test(val);
      const numberOk = /[0-9]/.test(val);

      toggleRule('req-length', lengthOk);
      toggleRule('req-upper', upperOk);
      toggleRule('req-lower', lowerOk);
      toggleRule('req-number', numberOk);

      const allRulesOk = lengthOk && upperOk && lowerOk && numberOk;
      
      // Match checking
      let matchOk = false;
      if (confirmVal.length > 0) {
        if (val === confirmVal) {
          matchError.style.display = 'none';
          confirmInput.classList.remove('error');
          matchOk = true;
        } else {
          matchError.style.display = 'block';
          confirmInput.classList.add('error');
          matchOk = false;
        }
      } else {
        matchError.style.display = 'none';
        confirmInput.classList.remove('error');
      }

      // Enable submit btn
      if (allRulesOk && matchOk && val.length > 0 && confirmVal.length > 0) {
        submitBtn.removeAttribute('disabled');
      } else {
        submitBtn.setAttribute('disabled', 'true');
      }
    }

    passwordInput.addEventListener('input', validateInputs);
    confirmInput.addEventListener('input', validateInputs);

    function toggleRule(reqId, isValid) {
      const el = document.getElementById(reqId);
      if (el) {
        if (isValid) el.classList.add('valid');
        else el.classList.remove('valid');
      }
    }

    resetForm?.addEventListener('submit', () => {
      setButtonLoading(submitBtn, true);
    });
  }
});
</script>

<?php require_once __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
