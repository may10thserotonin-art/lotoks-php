<?php
/**
 * Lotoks — Newsletter Subscription Confirmation Email
 *
 * @param string $confirmLink The double-opt-in confirmation URL
 * @return string HTML content (without wrapping)
 */
return function (string $confirmLink): string {
    $siteName = defined('SITE_NAME') ? SITE_NAME : 'Lotoks';
    $siteUrl  = defined('SITE_URL') ? SITE_URL : 'https://www.lotoks.co.za';

    return <<<HTML
<h2 style="margin-top:0;">Confirm Your Subscription</h2>
<p>Thank you for subscribing to the <strong>$siteName</strong> newsletter!</p>
<p>Please confirm your email address by clicking the button below:</p>
<p style="text-align:center; margin:30px 0;">
  <a href="$confirmLink" class="btn">Confirm Subscription</a>
</p>
<p style="font-size:13px; color:#666;">
  If you did not sign up for this, you can ignore this email.
</p>
<hr style="border:none; border-top:1px solid #eee; margin:20px 0;">
<p style="font-size:12px; color:#999;">
  &copy; $siteName &bull; <a href="$siteUrl">$siteUrl</a>
</p>
HTML;
};
