<?php
/**
 * Lotoks — Generic Notification Email Template
 *
 * @param string $subject    Notification title
 * @param string $message    Plain or HTML message body
 * @param string $actionUrl  Optional CTA link
 * @param string $actionText Optional CTA button text
 * @return string HTML content (without wrapping)
 */
return function (string $subject, string $message, string $actionUrl = '', string $actionText = ''): string {
    $siteName = defined('SITE_NAME') ? SITE_NAME : 'Lotoks';
    $siteUrl  = defined('SITE_URL') ? SITE_URL : 'https://www.lotoks.co.za';

    $ctaBlock = '';
    if ($actionUrl && $actionText) {
        $ctaBlock = <<<HTML
<p style="text-align:center; margin:30px 0;">
  <a href="$actionUrl" class="btn">$actionText</a>
</p>
HTML;
    }

    return <<<HTML
<h2 style="margin-top:0;">$subject</h2>
<p>$message</p>
$ctaBlock
<hr style="border:none; border-top:1px solid #eee; margin:20px 0;">
<p style="font-size:12px; color:#999;">
  &copy; $siteName &bull; <a href="$siteUrl">$siteUrl</a>
</p>
HTML;
};
