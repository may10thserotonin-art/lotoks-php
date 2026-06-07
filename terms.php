<?php
/**
 * Lotoks — Terms and Conditions (terms.php)
 */
$page_title       = 'Terms and Conditions | Lotoks';
$page_description = 'Read the Terms and Conditions governing the use of Lotoks consultancy services and website.';
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="page-hero page-hero--short">
  <div class="hero-overlay-dark"></div>
  <div class="container" style="position:relative;z-index:10;text-align:center;">
    <h1 class="page-hero__title" data-animate="fade-up">Terms &amp; Conditions</h1>
    <p class="page-hero__subtitle" data-animate="fade-up" data-delay="100">Last updated: January 2026</p>
  </div>
</section>

<section class="section-wrapper">
  <div class="container" style="max-width:48rem;">
    <div class="legal-content">

      <h2>1. Introduction</h2>
      <p>Welcome to Lotoks ("we", "our", "us"). These Terms and Conditions govern your use of our website and consultancy services. By accessing or using our platform, you agree to be bound by these terms. If you do not agree, please do not use our services.</p>

      <h2>2. Definitions</h2>
      <ul>
        <li><strong>"Platform"</strong> — the Lotoks website and all associated services.</li>
        <li><strong>"User"</strong> — any individual or entity accessing or using the Platform.</li>
        <li><strong>"Services"</strong> — visa sponsorship, education scholarship, job placement, and permanent residence consultancy services offered by Lotoks.</li>
        <li><strong>"Application"</strong> — a user's submitted request for consultancy services through the Platform.</li>
      </ul>

      <h2>3. User Responsibilities</h2>
      <p>You agree to:</p>
      <ul>
        <li>Provide accurate, current, and complete information when registering and using our services.</li>
        <li>Maintain the confidentiality of your account credentials.</li>
        <li>Notify us immediately of any unauthorized use of your account.</li>
        <li>Use the Platform in compliance with all applicable laws and regulations.</li>
      </ul>

      <h2>4. Services Description</h2>
      <p>Lotoks provides consultancy services to assist users with visa applications, education scholarships, job placement, and permanent residence applications. We do not guarantee specific outcomes. Our consultants provide guidance based on their expertise, but final decisions rest with the relevant authorities.</p>

      <h2>5. Intellectual Property</h2>
      <p>All content on the Platform, including text, graphics, logos, and software, is the property of Lotoks and is protected by applicable intellectual property laws. You may not reproduce, distribute, or create derivative works without our express written consent.</p>

      <h2>6. Limitation of Liability</h2>
      <p>Lotoks shall not be liable for any indirect, incidental, special, consequential, or punitive damages arising from your use of the Platform or Services. Our total liability shall not exceed the amount paid by you for the specific service giving rise to the claim.</p>

      <h2>7. Indemnification</h2>
      <p>You agree to indemnify and hold Lotoks harmless from any claims, losses, damages, liabilities, and expenses arising from your use of the Platform, violation of these terms, or infringement of any third-party rights.</p>

      <h2>8. Termination</h2>
      <p>We reserve the right to suspend or terminate your access to the Platform at any time, without prior notice, for conduct that we believe violates these Terms or is harmful to other users, third parties, or us.</p>

      <h2>9. Changes to Terms</h2>
      <p>We may update these Terms from time to time. Changes will be posted on this page with an updated revision date. Continued use of the Platform after changes constitutes acceptance of the new terms.</p>

      <h2>10. Governing Law</h2>
      <p>These Terms shall be governed by and construed in accordance with the laws of South Africa. Any disputes shall be subject to the exclusive jurisdiction of the courts of South Africa.</p>

      <h2>11. Contact Us</h2>
      <p>If you have any questions about these Terms, please contact us at <a href="mailto:info@lotoks.co.za">info@lotoks.co.za</a>.</p>

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
