<?php
/**
 * Lotoks — Dynamic XML Sitemap
 *
 * Generates a sitemap.xml-compatible output listing all public pages.
 * Includes key pages with priorities and change frequencies.
 *
 * Access at:  https://www.lotoks.co.za/sitemap.php
 * Submit to:  Google Search Console
 */

// ── Bootstrap ───────────────────────────────────────────────────────
require_once __DIR__ . '/includes/config.php';

$siteUrl = defined('SITE_URL') ? SITE_URL : 'https://www.lotoks.co.za';
$base    = defined('BASE') ? BASE : '';

// ── Page definitions ────────────────────────────────────────────────
// Each entry: [path, priority, changefreq]
$pages = [
    ['/',                             '1.0', 'weekly'],
    ['/about.php',                    '0.8', 'monthly'],
    ['/services.php',                 '0.8', 'monthly'],
    ['/testimonials.php',             '0.7', 'monthly'],
    ['/contact.php',                  '0.7', 'monthly'],
    ['/eligibility.php',              '0.7', 'monthly'],
    ['/requirements.php',             '0.7', 'monthly'],
    ['/login.php',                    '0.5', 'monthly'],
    ['/register.php',                 '0.5', 'monthly'],
    ['/forgot-password.php',          '0.3', 'monthly'],
    ['/privacy.php',                  '0.4', 'yearly'],
    ['/refund.php',                   '0.4', 'yearly'],
    ['/terms.php',                    '0.4', 'yearly'],
    ['/faq.php',                      '0.6', 'monthly'],
    ['/blog.php',                     '0.6', 'weekly'],
];

// ── Find blog/news sub-pages (if any) ──────────────────────────────
// Blog posts can be added here dynamically from a `blog_posts` table
// when the blog module is implemented.

// ── Output XML ─────────────────────────────────────────────────────
header('Content-Type: application/xml; charset=utf-8');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

foreach ($pages as [$path, $priority, $changefreq]) {
    $url = $siteUrl . $base . $path;
    printf(
        "  <url>\n    <loc>%s</loc>\n    <priority>%s</priority>\n    <changefreq>%s</changefreq>\n  </url>\n",
        htmlspecialchars($url, ENT_XML1 | ENT_QUOTES, 'UTF-8'),
        $priority,
        $changefreq
    );
}

echo '</urlset>' . "\n";
