<?php
/**
 * Lotoks — Refund Policy (refund.php)
 */
$page_title       = 'Refund Policy | Lotoks';
$page_description = 'Understand the refund policy for Lotoks consultancy services.';
require_once __DIR__ . '/includes/head.php';
require_once __DIR__ . '/includes/navbar.php';
?>

<section class="page-hero page-hero--short">
  <div class="hero-overlay-dark"></div>
  <div class="container" style="position:relative;z-index:10;text-align:center;">
    <h1 class="page-hero__title" data-animate="fade-up">Refund Policy</h1>
    <p class="page-hero__subtitle" data-animate="fade-up" data-delay="100">Last updated: January 2026</p>
  </div>
</section>

<section class="section-wrapper">
  <div class="container" style="max-width:48rem;">
    <div class="legal-content">

      <h2>1. Overview</h2>
      <p>This Refund Policy outlines the terms under which refunds may be issued for Lotoks consultancy services. We are committed to delivering high-quality consultancy, and we strive to resolve any concerns you may have.</p>

      <h2>2. Current Payment Model</h2>
      <p>As of the last update, Lotoks does not process online payments directly through the platform. Our consultancy services are billed through separate invoicing arrangements. This policy serves as a framework for future payment processing and addresses any deposits or fees collected through our platform.</p>

      <h2>3. Consultancy Fees</h2>
      <p>Fees for consultancy services are agreed upon during the engagement process. Refund eligibility depends on the stage of the engagement:</p>
      <ul>
        <li><strong>Before Service Commencement:</strong> Full refund of any fees paid, minus reasonable administrative costs.</li>
        <li><strong>During Active Engagement:</strong> Refund calculated on a pro-rata basis for services not yet rendered.</li>
        <li><strong>After Service Completion:</strong> No refund is available for completed services.</li>
      </ul>

      <h2>4. Application Fees</h2>
      <p>Any application or processing fees paid through the platform are non-refundable once the application has been reviewed and processed by our team, as this represents work already performed.</p>

      <h2>5. Exceptional Circumstances</h2>
      <p>Refunds outside the above policy may be considered at our discretion in exceptional circumstances, such as:</p>
      <ul>
        <li>Duplicate payments made in error.</li>
        <li>Technical errors resulting in incorrect charges.</li>
        <li>Cases where we are unable to deliver the agreed services.</li>
      </ul>

      <h2>6. Refund Process</h2>
      <p>To request a refund, please contact us with your details and reason for the request:</p>
      <ul>
        <li>Email: <a href="mailto:info@lotoks.co.za">info@lotoks.co.za</a></li>
        <li>Phone: <a href="tel:+27110518583">+27 11 051 8583</a></li>
      </ul>
      <p>We will review your request and respond within 14 business days. Approved refunds will be processed within 10 business days of approval.</p>

      <h2>7. Changes to This Policy</h2>
      <p>We reserve the right to update this Refund Policy at any time. Changes will be posted on this page with an updated revision date.</p>

      <h2>8. Contact Us</h2>
      <p>If you have any questions about this Refund Policy, please contact us at <a href="mailto:info@lotoks.co.za">info@lotoks.co.za</a>.</p>

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
