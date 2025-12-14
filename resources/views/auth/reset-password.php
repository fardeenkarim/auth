<?php
$pageTitle = 'Set New Password - ' . APP_NAME;
ob_start();
?>
<div>
    <h2 class="text-3xl font-bold mb-8 tracking-tight text-gray-900 animate-fade-in-up" style="animation-delay: 100ms;">New Password</h2>

    <form id="reset-form" action="<?= BASE_URL ?>/api/reset-password" method="POST">
        <input type="hidden" name="token" value="<?= htmlspecialchars($_GET['token'] ?? '') ?>">
        
        <div class="input-group group relative animate-fade-in-up" style="animation-delay: 200ms;">
            <label for="password" class="label-text transition-colors group-focus-within:text-zinc-900">New Password</label>
            <input type="password" id="password" name="password" class="input-field transition-all duration-300 focus:ring-2 focus:ring-zinc-900 focus:border-transparent" required>
        </div>
        
        <button type="submit" class="animate-fade-in-up btn btn-primary transform transition-transform duration-200 hover:scale-[1.02] active:scale-[0.98]" style="animation-delay: 300ms;">Update Password</button>
    </form>
</div>

<script>
document.getElementById('reset-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button');
    const originalText = btn.innerText;

    try {
        btn.innerText = 'Updating...';
        btn.disabled = true;

        const formData = new FormData(form);
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.status === 'success') {
            showToast(data.message, 'success');
            setTimeout(() => {
                window.location.href = '<?= BASE_URL ?>/login';
            }, 1500);
        } else {
            showToast(data.message, 'error');
        }
    } catch (error) {
        console.error(error);
        showToast('An error occurred.', 'error');
    } finally {
        btn.innerText = originalText;
        btn.disabled = false;
    }
});
</script>

<?php
$content = ob_get_clean();
$extraScripts = '<script src="' . BASE_URL . '/assets/toast.js?v=' . time() . '"></script>';
require ROOT_PATH . '/resources/views/layouts/main.php';
?>
