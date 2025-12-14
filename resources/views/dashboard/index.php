<?php
$pageTitle = 'Dashboard - ' . APP_NAME;
$containerStyle = 'max-width: 600px;';
ob_start();
?>

<div style="margin-top: 30px;">
    <a href="<?= BASE_URL ?>/logout" class="btn btn-primary" style="background: #ff6b6b; border-color: #ff6b6b;">Logout</a>
</div>

<?php
$content = ob_get_clean();
require ROOT_PATH . '/resources/views/layouts/main.php';
?>
