<?php
/**
 * Lotoks — 500 Internal Server Error Page
 *
 * Shown when a production error occurs.
 */
require_once __DIR__ . '/includes/config.php';

http_response_code(500);

$page_title = 'Server Error | Lotoks';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($page_title) ?></title>

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="<?= BASE ?>/public/favicon.svg" />
  <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE ?>/public/logo.png" />
  <link rel="apple-touch-icon" href="<?= BASE ?>/public/logo.png" />

  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    body {
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      background: #0B1D3A;
      color: #fff;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem;
    }
    .container { text-align: center; max-width: 500px; }
    .code { font-size: 6rem; font-weight: 800; color: #C9A44B; line-height: 1; }
    .title { font-size: 1.5rem; font-weight: 600; margin: 1rem 0 0.5rem; }
    .desc { color: rgba(255,255,255,0.5); font-size: 0.9rem; line-height: 1.6; margin-bottom: 2rem; }
    .btn {
      display: inline-block;
      padding: 0.75rem 2rem;
      background: #C9A44B;
      color: #0B1D3A;
      text-decoration: none;
      border-radius: 999px;
      font-weight: 600;
      transition: opacity 0.2s;
    }
    .btn:hover { opacity: 0.85; }
  </style>
</head>
<body>
  <div class="container">
    <div class="code">500</div>
    <h1 class="title">Something went wrong</h1>
    <p class="desc">We've encountered an unexpected error. Our team has been notified. Please try again in a few moments.</p>
    <a href="<?= BASE ?>/" class="btn">Back to Home</a>
  </div>
</body>
</html>
