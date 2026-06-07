<?php
/**
 * Lotoks — 404 Not Found (404.php)
 */
http_response_code(404);
$page_title       = 'Page Not Found | Lotoks';
$page_description = 'The page you are looking for does not exist or has been moved.';
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="section-wrapper" style="min-height:70vh;display:flex;align-items:center;">
  <div class="container" style="text-align:center;max-width:36rem;">
    <div style="font-size:clamp(5rem,12vw,8rem);font-weight:700;color:var(--color-gold);line-height:1;font-family:var(--font-heading);margin-bottom:1rem;" data-animate="fade-up">
      404
    </div>
    <h1 style="font-family:var(--font-heading);font-size:clamp(1.5rem,3vw,2rem);font-weight:700;color:var(--color-navy);margin-bottom:1rem;" data-animate="fade-up" data-delay="100">
      Page Not Found
    </h1>
    <p style="font-size:1rem;color:var(--color-on-surface-variant);line-height:1.7;margin-bottom:2.5rem;" data-animate="fade-up" data-delay="150">
      The page you're looking for doesn't exist or has been moved. 
      Let us help you find what you need.
    </p>
    <div style="display:flex;flex-direction:column;gap:1rem;align-items:center;justify-content:center;" class="cta-row" data-animate="fade-up" data-delay="200">
      <a href="<?= BASE ?>/" class="btn btn-primary btn-lg btn-pill" style="min-width:12rem;">
        Go to Home
      </a>
      <a href="<?= BASE ?>/contact.php" class="btn btn-secondary btn-lg btn-pill" style="min-width:12rem;border-color:var(--color-gold);color:var(--color-gold);">
        Contact Support
      </a>
    </div>
  </div>
</section>

<style>
.cta-row { flex-direction: column; }
@media (min-width: 576px) {
  .cta-row { flex-direction: row !important; }
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
