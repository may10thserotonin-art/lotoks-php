<?php
/**
 * Lotoks — Register Page (register.php)
 * Converted from pages/Signup.tsx / Register.tsx
 */
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/db/connect.php';

if (is_user_logged_in()) redirect('/dashboard.php');

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']     ?? '');
    $email    = strtolower(trim($_POST['email']    ?? ''));
    $password = $_POST['password']  ?? '';
    $confirm  = $_POST['confirm']   ?? '';

    if (!$name || !$email || !$password) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $db   = getDb();
        $chk  = $db->prepare('SELECT id FROM users WHERE email = ?');
        $chk->execute([$email]);
        if ($chk->fetch()) {
            $error = 'An account with this email already exists.';
        } else {
            $hash = hash_password($password);
            $stmt = $db->prepare('INSERT INTO users (name, email, password_hash, verified) VALUES (?, ?, ?, 1)');
            $stmt->execute([$name, $email, $hash]);
            $userId = (int)$db->lastInsertId();

            $user = ['id' => $userId, 'name' => $name, 'email' => $email, 'country' => ''];
            user_login($user);
            redirect('/dashboard.php');
        }
    }
}

$page_title       = 'Create Account | Lotoks';
$page_description = 'Create your free Lotoks account and start your journey to global opportunities.';
require_once __DIR__ . '/includes/head.php';
?>
<div class="login-page-bg" style="min-height:100vh;background:var(--color-navy);display:flex;flex-direction:column;align-items:center;justify-content:center;padding:2rem 1.5rem;position:relative;overflow:hidden;">
  <div style="position:absolute;top:-10rem;right:-10rem;width:30rem;height:30rem;background:rgba(201,164,75,0.06);border-radius:50%;filter:blur(60px);pointer-events:none;"></div>
  <div style="position:absolute;bottom:-10rem;left:-10rem;width:30rem;height:30rem;background:rgba(35,73,225,0.06);border-radius:50%;filter:blur(60px);pointer-events:none;"></div>

  <a href="/" style="position:absolute;top:1.5rem;left:1.5rem;display:flex;align-items:center;gap:0.5rem;color:rgba(255,255,255,0.4);font-size:0.875rem;text-decoration:none;transition:color 0.2s;" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.4)'">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
    Back to Home
  </a>

  <div class="login-card" style="width:100%;max-width:24rem;">
    <div style="text-align:center;margin-bottom:1.75rem;">
      <a href="/" style="display:inline-flex;align-items:center;gap:0.5rem;text-decoration:none;margin-bottom:1rem;">
        <div style="width:3rem;height:3rem;border-radius:0.75rem;overflow:hidden;"><img src="<?= BASE ?>/public/logo.png" alt="Lotoks" style="width:100%;height:100%;object-fit:contain;" /></div>
        <span style="font-family:var(--font-heading);font-size:1.5rem;font-weight:700;color:#fff;">Lotoks<span style="color:var(--color-gold);">.</span></span>
      </a>
      <h1 style="font-family:var(--font-heading);font-size:1.5rem;font-weight:700;color:#fff;margin-bottom:0.25rem;">Create your account</h1>
      <p style="color:rgba(255,255,255,0.5);font-size:0.875rem;">Start your global journey today</p>
    </div>

    <?php if ($error): ?>
      <div class="alert alert-error">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="flex-shrink:0;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form method="POST" id="register-form" novalidate>
      <?= csrf_field() ?>

      <div class="form-group">
        <label class="form-label" for="name">Full name</label>
        <div class="input-icon-wrapper">
          <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
          <input type="text" id="name" name="name" class="form-input" placeholder="John Doe" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" autocomplete="name" required />
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="email">Email address</label>
        <div class="input-icon-wrapper">
          <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
          <input type="email" id="email" name="email" class="form-input" placeholder="you@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" autocomplete="email" required />
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="password">Password <span style="font-size:0.7rem;color:rgba(255,255,255,0.3);">(min 8 characters)</span></label>
        <div class="input-icon-wrapper" style="position:relative;">
          <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
          <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" autocomplete="new-password" required style="padding-right:2.75rem;" />
          <button type="button" class="pw-toggle" data-pw-toggle="password" aria-label="Toggle password visibility">
            <svg class="eye-on" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            <svg class="eye-off" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
          </button>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label" for="confirm">Confirm password</label>
        <div class="input-icon-wrapper" style="position:relative;">
          <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
          <input type="password" id="confirm" name="confirm" class="form-input" placeholder="••••••••" autocomplete="new-password" required style="padding-right:2.75rem;" />
          <button type="button" class="pw-toggle" data-pw-toggle="confirm" aria-label="Toggle confirm password visibility">
            <svg class="eye-on" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            <svg class="eye-off" xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
          </button>
        </div>
      </div>

      <button type="submit" class="btn btn-primary btn-full btn-pill" id="register-submit" style="margin-top:0.5rem;">
        Create Account
      </button>
    </form>

    <div class="divider"><span>or</span></div>

    <p style="text-align:center;font-size:0.875rem;color:rgba(255,255,255,0.5);">
      Already have an account?
      <a href="<?= BASE ?>/login.php" style="color:var(--color-gold);text-decoration:none;font-weight:600;" onmouseover="this.style.opacity='0.7'" onmouseout="this.style.opacity='1'">Sign in</a>
    </p>
  </div>
</div>

<script>
document.getElementById('register-form')?.addEventListener('submit', function () {
  setButtonLoading(document.getElementById('register-submit'), true);
});
</script>

<?php require_once __DIR__ . '/includes/scripts.php'; ?>
</body>
</html>
