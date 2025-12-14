<?php
$pageTitle = 'Forgot Password - ' . APP_NAME;
ob_start();
?>
<div>
    <h2 class="text-3xl font-bold mb-2 tracking-tight text-gray-900 animate-fade-in-up" style="animation-delay: 100ms;">Reset Password</h2>
    <p class="mb-8 text-gray-500 animate-fade-in-up" style="animation-delay: 150ms;">Enter your email to receive instructions.</p>

    <form id="forgot-form" action="<?= BASE_URL ?>/api/forgot-password" method="POST">
        <div class="input-group group animate-fade-in-up" style="animation-delay: 200ms;">
            <label for="email" class="label-text transition-colors group-focus-within:text-zinc-900">Email Address</label>
            <input type="email" id="email" name="email" class="input-field transition-all duration-300 focus:ring-2 focus:ring-zinc-900 focus:border-transparent" required>
        </div>
        
        <button type="submit" class="animate-fade-in-up btn btn-primary transform transition-transform duration-200 hover:scale-[1.02] active:scale-[0.98]" style="animation-delay: 300ms;">Send Reset Link</button>
    </form>
    
    <div class="mt-6 text-center animate-fade-in-up" style="animation-delay: 400ms;">
        <a href="<?= BASE_URL ?>/login" class="text-sm font-medium text-gray-600 hover:text-zinc-900 transition-colors">Back to Login</a>
    </div>
</div>

<script>
document.getElementById('forgot-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    const btn = form.querySelector('button');
    const originalText = btn.innerText;

    try {
        btn.innerText = 'Sending...';
        btn.disabled = true;

        const formData = new FormData(form);
        const response = await fetch(form.action, {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.status === 'success') {
            showToast(data.message, 'success');
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
