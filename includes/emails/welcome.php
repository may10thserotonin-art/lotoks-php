<?php
/**
 * Lotoks — Welcome Email Template
 *
 * @param string $userName  New user's name
 * @param string $dashboardUrl Link to the dashboard
 * @return string HTML content (without wrapping)
 */
return function (string $userName, string $dashboardUrl): string {
    $siteName = defined('SITE_NAME') ? SITE_NAME : 'Lotoks';

    return <<<HTML
<h2 style="margin-top:0;">Welcome to $siteName, $userName!</h2>
<p>Thank you for creating an account with <strong>$siteName</strong>. We're excited to help you explore global opportunities.</p>
<p>Here's what you can do next:</p>
<ul>
  <li>Browse available programs and services</li>
  <li>Submit your application for review</li>
  <li>Track your application status in real time</li>
  <li>Upload required documents securely</li>
</ul>
<p style="text-align:center; margin:30px 0;">
  <a href="$dashboardUrl" class="btn">Go to Dashboard</a>
</p>
<hr style="border:none; border-top:1px solid #eee; margin:20px 0;">
<p style="font-size:12px; color:#999;">
  If you did not create this account, please <a href="$siteName">contact support</a> immediately.
</p>
HTML;
};
