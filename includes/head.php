<?php
/**
 * Lotoks — Public <head> include
 *
 * Usage:
 *   $page_title       = 'Page Name';          // required
 *   $page_description = 'Meta description';   // optional
 *   $page_class       = 'extra-body-class';   // optional extra body class
 *   require_once 'includes/head.php';
 */

require_once __DIR__ . '/config.php';

// Defaults
$page_title       = $page_title       ?? 'Lotoks | Global Opportunities';
$page_description = $page_description ?? 'Lotoks connects ambitious professionals with global work, education, and visa sponsorship opportunities worldwide.';
$page_class       = $page_class       ?? '';

// Build canonical URL
$protocol  = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$canonical = $protocol . '://' . ($_SERVER['HTTP_HOST'] ?? 'lotoks.co.za') . ($_SERVER['REQUEST_URI'] ?? '/');
$canonical = strtok($canonical, '?'); // strip query string
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title><?= htmlspecialchars($page_title) ?></title>

  <!-- SEO Meta -->
  <meta name="description" content="<?= htmlspecialchars($page_description) ?>" />
  <meta name="robots" content="index, follow" />
  <link rel="canonical" href="<?= htmlspecialchars($canonical) ?>" />

  <!-- Open Graph -->
  <meta property="og:type"        content="website" />
  <meta property="og:title"       content="<?= htmlspecialchars($page_title) ?>" />
  <meta property="og:description" content="<?= htmlspecialchars($page_description) ?>" />
  <meta property="og:url"         content="<?= htmlspecialchars($canonical) ?>" />
  <meta property="og:image"       content="<?= BASE ?>/public/images/logo.png" />
  <meta property="og:site_name"   content="Lotoks" />

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="<?= BASE ?>/public/logo.png" />
  <link rel="apple-touch-icon"      href="<?= BASE ?>/public/logo.png" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lexend:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet" />

  <!-- Stylesheets -->
  <link rel="stylesheet" href="<?= BASE ?>/assets/css/style.css" />
  <link rel="stylesheet" href="<?= BASE ?>/assets/css/animations.css" />
</head>
<body class="<?= htmlspecialchars($page_class) ?>">
