<?php
/**
 * Lotoks — Password Reset Email Template
 *
 * Returns the HTML body for password reset emails.
 *
 * @param string $resetLink The full reset URL
 * @return string HTML content (without wrapping)
 */
return function (string $resetLink): string {
    $siteName = defined('SITE_NAME') ? SITE_NAME : 'Lotoks';
    $siteUrl  = defined('SITE_URL') ? SITE_URL : 'https://www.lotoks.co.za';

    return <<<HTML
<h2 style="margin-top:0;">Password Reset Request</h2>
<p>We received a request to reset the password for your <strong>$siteName</strong> account.</p>
<p style="text-align:center; margin:30px 0;">
  <a href="$resetLink" class="btn">Reset Password</a>
</p>
<p>Or copy this link into your browser:</p>
<p style="font-size:13px; color:#666; word-break:break-all;">$resetLink</p>
<p style="margin-top:24px; font-size:13px; color:#666;">
  This link expires in <strong>1 hour</strong>. If you did not request this, you can safely ignore this email.
</p>
<hr style="border:none; border-top:1px solid #eee; margin:20px 0;">
<p style="font-size:12px; color:#999;">
  &copy; $siteName &bull; <a href="$siteUrl">$siteUrl</a>
</p>
HTML;
};
