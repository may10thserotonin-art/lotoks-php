<?php
/**
 * Lotoks — Public <head> include
 *
 * Usage:
 *   $page_title       = 'Page Name';          // required
 *   $page_description = 'Meta description';   // optional
 *   $page_class       = 'extra-body-class';   // optional extra body class
 *   $page_image       = '/public/images/og-default.png'; // optional OG image
 *   require_once 'includes/head.php';
 */

require_once __DIR__ . '/config.php';

// Defaults
$page_title       = $page_title       ?? 'Lotoks | Global Opportunities';
$page_description = $page_description ?? 'Lotoks connects ambitious professionals with global work, education, and visa sponsorship opportunities worldwide.';
$page_class       = $page_class       ?? '';
$page_image       = $page_image       ?? BASE . '/public/images/logo.png';

// Build canonical URL using SITE_URL for consistency
$canonical = strtok(SITE_URL . ($_SERVER['REQUEST_URI'] ?? '/'), '?'); // strip query string

// JSON-LD structured data (Organization + WebSite schema)
$jsonLd = [
    '@context'        => 'https://schema.org',
    '@graph'          => [
        [
            '@type'            => 'Organization',
            '@id'              => SITE_URL . '/#organization',
            'name'             => SITE_NAME,
            'url'              => SITE_URL,
            'logo'             => SITE_URL . '/public/images/logo.png',
            'description'      => 'Lotoks connects ambitious professionals with global work, education, and visa sponsorship opportunities worldwide.',
            'foundingDate'     => '2024',
            'address'          => [
                '@type' => 'PostalAddress',
                'addressCountry' => 'ZA',
            ],
        ],
        [
            '@type'            => 'WebSite',
            '@id'              => SITE_URL . '/#website',
            'url'              => SITE_URL,
            'name'             => SITE_NAME,
            'description'      => $page_description,
            'publisher'        => ['@id' => SITE_URL . '/#organization'],
        ],
    ],
];

// Add BreadcrumbList on deeper pages
if (isset($page_breadcrumbs) && is_array($page_breadcrumbs)) {
    $items = [];
    $pos = 1;
    foreach ($page_breadcrumbs as $crumb) {
        $items[] = [
            '@type'    => 'ListItem',
            'position' => $pos++,
            'name'     => $crumb['name'],
            'item'     => $crumb['url'] ?? SITE_URL,
        ];
    }
    $jsonLd['@graph'][] = [
        '@type'           => 'BreadcrumbList',
        '@id'             => SITE_URL . '/#breadcrumb',
        'itemListElement' => $items,
    ];
}

// Add WebPage schema on content pages
$jsonLd['@graph'][] = [
    '@type'       => 'WebPage',
    '@id'         => $canonical . '#webpage',
    'url'         => $canonical,
    'name'        => $page_title,
    'description' => $page_description,
    'isPartOf'    => ['@id' => SITE_URL . '/#website'],
];
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
  <meta property="og:image"       content="<?= htmlspecialchars($page_image) ?>" />
  <meta property="og:image:width"  content="1200" />
  <meta property="og:image:height" content="630" />
  <meta property="og:site_name"   content="<?= SITE_NAME ?>" />
  <meta property="og:locale"      content="en_ZA" />

  <!-- Twitter Card -->
  <meta name="twitter:card"        content="summary_large_image" />
  <meta name="twitter:title"       content="<?= htmlspecialchars($page_title) ?>" />
  <meta name="twitter:description" content="<?= htmlspecialchars($page_description) ?>" />
  <meta name="twitter:image"       content="<?= htmlspecialchars($page_image) ?>" />

  <!-- JSON-LD Structured Data -->
  <script type="application/ld+json">
  <?= json_encode($jsonLd, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) ?>
  </script>

  <!-- CSRF Token (readable by JS for AJAX requests) -->
  <meta name="csrf-token" content="<?= htmlspecialchars(csrf_token()) ?>" />

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml" href="<?= BASE ?>/public/favicon.svg" />
  <link rel="icon" type="image/png" sizes="32x32" href="<?= BASE ?>/public/logo.png" />
  <link rel="apple-touch-icon" href="<?= BASE ?>/public/logo.png" />
  <link rel="sitemap" type="application/xml" href="<?= BASE ?>/sitemap.php" />

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lexend:wght@400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet" />

  <!-- Stylesheets -->
  <link rel="stylesheet" href="<?= BASE ?>/assets/css/style.css?v=<?= filemtime(__DIR__ . '/../assets/css/style.css') ?>" />
  <link rel="stylesheet" href="<?= BASE ?>/assets/css/animations.css?v=<?= filemtime(__DIR__ . '/../assets/css/animations.css') ?>" />
</head>
<body class="<?= htmlspecialchars($page_class) ?>">
