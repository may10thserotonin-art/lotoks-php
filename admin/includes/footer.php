</main> <!-- /.admin-main -->

<!-- Global Application Detail Modal -->
<div class="modal-overlay" id="app-modal">
  <div class="modal">
    <div class="modal-header">
      <h2 class="modal-title">Application Details</h2>
      <button class="modal-close" data-close-modal>&times;</button>
    </div>
    <div class="modal-body" id="app-modal-body">
      <!-- Injected via AJAX -->
    </div>
  </div>
</div>

<!-- Global User Detail Modal -->
<div class="modal-overlay" id="user-modal">
  <div class="modal">
    <div class="modal-header">
      <h2 class="modal-title">User Details</h2>
      <button class="modal-close" data-close-modal>&times;</button>
    </div>
    <div class="modal-body" id="user-modal-body">
      <!-- Injected via AJAX -->
    </div>
  </div>
</div>

<script>
  window.LOTOKS_CONFIG = {
    BASE: '<?= BASE ?>',
    CSRF_TOKEN: '<?= htmlspecialchars(csrf_token()) ?>'
  };
</script>
<script src="<?= BASE ?>/admin/assets/js/admin.js?v=<?= filemtime(__DIR__ . '/../assets/js/admin.js') ?>"></script>
</body>
</html>
