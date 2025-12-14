<?php
$pageTitle = 'Login - ' . APP_NAME;
ob_start();
?>
<div>
    <h2 class="text-3xl font-bold mb-8 tracking-tight text-gray-900 animate-fade-in-up" style="animation-delay: 100ms;">Welcome Back</h2>

    <form id="login-form">
        <input type="hidden" name="action" value="login">
        
        <div class="input-group group animate-fade-in-up" style="animation-delay: 200ms;">
            <label for="email" class="label-text transition-colors group-focus-within:text-zinc-900">Email Address</label>
            <input type="email" id="email" name="email" class="input-field transition-all duration-300 focus:ring-2 focus:ring-zinc-900 focus:border-transparent" required>
        </div>
        
        <div class="input-group group relative animate-fade-in-up" style="animation-delay: 300ms;">
            <label for="password" class="label-text transition-colors group-focus-within:text-zinc-900">Password</label>
            <div class="relative">
                <input type="password" id="password" name="password" class="input-field pr-10 transition-all duration-300 focus:ring-2 focus:ring-zinc-900 focus:border-transparent" required>
                <button type="button" id="toggle-password" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600 transition-colors focus:outline-none">
                    <!-- Eye Icon -->
                    <svg id="icon-eye" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <!-- Eye Slash Icon (Hidden by default) -->
                    <svg id="icon-eye-slash" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
        </div>
        
        <div class="flex items-center justify-between mb-6 animate-fade-in-up" style="animation-delay: 350ms;">
            <label class="flex items-center cursor-pointer group select-none">
                <div class="relative">
                    <input type="checkbox" name="remember_me" class="peer sr-only">
                    <div class="w-5 h-5 border-2 border-gray-300 rounded transition duration-200 peer-checked:bg-zinc-900 peer-checked:border-zinc-900 bg-white"></div>
                    <svg class="absolute top-1 left-1 w-3 h-3 text-white pointer-events-none opacity-0 peer-checked:opacity-100 transition duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <span class="ml-2.5 text-sm font-medium text-gray-600 group-hover:text-zinc-900 transition-colors">Remember me</span>
            </label>
            <a href="<?= BASE_URL ?>/forgot-password" class="text-sm font-medium text-zinc-500 hover:text-zinc-900 hover:underline transition-colors">Forgot Password?</a>
        </div>
        
        <button type="submit" class="animate-fade-in-up btn btn-primary transform transition-transform duration-200 hover:scale-[1.02] active:scale-[0.98]" style="animation-delay: 400ms;">Login</button>
    </form>
</div>
<div class="links mt-4 animate-fade-in-up" style="animation-delay: 500ms;">
    Don't have an account? <a href="<?= BASE_URL ?>/register" class="underline">Register here</a>
</div>

<?php
$content = ob_get_clean();
$extraScripts = '<script src="' . BASE_URL . '/assets/toast.js?v=' . time() . '"></script>' .
                '<script src="' . BASE_URL . '/assets/app.js?v=' . time() . '"></script>';
require ROOT_PATH . '/resources/views/layouts/main.php';
?>
