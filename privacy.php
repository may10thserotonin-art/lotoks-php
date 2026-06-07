<?php
/**
 * Lotoks — Privacy Policy (privacy.php)
 */
$page_title       = 'Privacy Policy | Lotoks';
$page_description = 'Learn how Lotoks collects, uses, and protects your personal data in accordance with applicable privacy laws.';
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="page-hero page-hero--short">
  <div class="hero-overlay-dark"></div>
  <div class="container" style="position:relative;z-index:10;text-align:center;">
    <h1 class="page-hero__title" data-animate="fade-up">Privacy Policy</h1>
    <p class="page-hero__subtitle" data-animate="fade-up" data-delay="100">Last updated: January 2026</p>
  </div>
</section>

<section class="section-wrapper">
  <div class="container" style="max-width:48rem;">
    <div class="legal-content">

      <h2>1. Introduction</h2>
      <p>Lotoks ("we", "our", "us") is committed to protecting your privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website or use our consultancy services.</p>

      <h2>2. Information We Collect</h2>
      <h3>Personal Information</h3>
      <p>We may collect personal information that you voluntarily provide, including:</p>
      <ul>
        <li><strong>Account Information:</strong> name, email address, phone number, country of residence.</li>
        <li><strong>Application Data:</strong> information submitted as part of your consultancy application, including personal history, education, employment details, and documents.</li>
        <li><strong>Communication:</strong> messages, inquiries, and correspondence sent through our contact forms or email.</li>
      </ul>

      <h3>Automatically Collected Information</h3>
      <p>When you visit our website, we may automatically collect:</p>
      <ul>
        <li><strong>Usage Data:</strong> pages visited, time spent, referral source.</li>
        <li><strong>Device Data:</strong> IP address, browser type, operating system.</li>
        <li><strong>Cookies:</strong> small text files stored on your device to enhance functionality and analytics.</li>
      </ul>

      <h2>3. How We Use Your Information</h2>
      <p>We use the collected information for the following purposes:</p>
      <ul>
        <li>To provide and improve our consultancy services.</li>
        <li>To process and evaluate your applications.</li>
        <li>To communicate with you regarding your account and applications.</li>
        <li>To send newsletters and promotional materials (with your consent).</li>
        <li>To comply with legal obligations and regulatory requirements.</li>
        <li>To protect our platform against fraud and unauthorized access.</li>
      </ul>

      <h2>4. Legal Basis for Processing</h2>
      <p>We process your personal data based on the following legal grounds:</p>
      <ul>
        <li><strong>Consent:</strong> where you have given clear consent (e.g., newsletter subscription).</li>
        <li><strong>Contract:</strong> processing necessary for the performance of our consultancy services.</li>
        <li><strong>Legal Obligation:</strong> where we need to comply with applicable laws.</li>
        <li><strong>Legitimate Interests:</strong> for improving our services, security, and fraud prevention.</li>
      </ul>

      <h2>5. Data Sharing and Disclosure</h2>
      <p>We may share your information with:</p>
      <ul>
        <li><strong>Service Providers:</strong> third-party vendors who assist with hosting, email delivery, and analytics.</li>
        <li><strong>Legal Authorities:</strong> when required by law or to protect our rights.</li>
        <li><strong>Professional Advisors:</strong> including lawyers and auditors.</li>
      </ul>
      <p>We do not sell your personal information to third parties.</p>

      <h2>6. Data Retention</h2>
      <p>We retain your personal data for as long as necessary to fulfill the purposes described in this policy, or as required by law. Account information is retained until you request deletion. Application data is retained for the duration of the consultancy engagement plus a reasonable period thereafter.</p>

      <h2>7. Your Rights</h2>
      <p>Depending on your jurisdiction, you may have the following rights:</p>
      <ul>
        <li><strong>Access:</strong> request a copy of the personal data we hold about you.</li>
        <li><strong>Rectification:</strong> request correction of inaccurate or incomplete data.</li>
        <li><strong>Erasure:</strong> request deletion of your personal data (subject to legal obligations).</li>
        <li><strong>Restriction:</strong> request restriction of processing under certain circumstances.</li>
        <li><strong>Portability:</strong> request transfer of your data to another service provider.</li>
        <li><strong>Objection:</strong> object to processing based on legitimate interests or direct marketing.</li>
      </ul>
      <p>To exercise any of these rights, contact us at <a href="mailto:info@lotoks.co.za">info@lotoks.co.za</a>.</p>

      <h2>8. Cookies</h2>
      <p>We use cookies and similar tracking technologies to enhance your experience. You can control cookie preferences through your browser settings. Essential cookies are required for the platform to function; analytics cookies help us improve our service.</p>

      <h2>9. Data Security</h2>
      <p>We implement appropriate technical and organizational measures to protect your personal data against unauthorized access, alteration, disclosure, or destruction. This includes encryption, access controls, and regular security audits.</p>

      <h2>10. International Transfers</h2>
      <p>Your data may be transferred to and processed in countries other than your own. We ensure appropriate safeguards are in place for such transfers, including standard contractual clauses where required.</p>

      <h2>11. Changes to This Policy</h2>
      <p>We may update this Privacy Policy from time to time. Changes will be posted on this page with an updated revision date. We encourage you to review this policy periodically.</p>

      <h2>12. Contact Us</h2>
      <p>If you have questions or concerns about this Privacy Policy or our data practices, please contact us:</p>
      <ul>
        <li>Email: <a href="mailto:info@lotoks.co.za">info@lotoks.co.za</a></li>
        <li>Phone: <a href="tel:+27110518583">+27 11 051 8583</a></li>
      </ul>

    </div>
  </div>
</section>

<style>
.legal-content h2 {
  font-family: var(--font-heading);
  font-size: 1.35rem;
  font-weight: 700;
  color: var(--color-navy);
  margin-top: 2.5rem;
  margin-bottom: 0.75rem;
}
.legal-content h2:first-child {
  margin-top: 0;
}
.legal-content h3 {
  font-family: var(--font-heading);
  font-size: 1.1rem;
  font-weight: 600;
  color: var(--color-navy);
  margin-top: 1.5rem;
  margin-bottom: 0.5rem;
}
.legal-content p,
.legal-content li {
  font-size: 0.95rem;
  line-height: 1.75;
  color: var(--color-on-surface-variant);
}
.legal-content ul {
  padding-left: 1.5rem;
  margin-bottom: 1rem;
}
.legal-content ul li {
  margin-bottom: 0.35rem;
}
.legal-content a {
  color: var(--color-gold);
  font-weight: 600;
}
.legal-content a:hover {
  text-decoration: underline;
}
</style>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
