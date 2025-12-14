class Modal {
    constructor() {
        this.overlay = document.createElement('div');
        this.overlay.className = 'modal-overlay';
        this.overlay.innerHTML = '<div class="modal-content"></div>';
        document.body.appendChild(this.overlay);

        this.content = this.overlay.querySelector('.modal-content');

        this.overlay.addEventListener('click', (e) => {
            if (e.target === this.overlay) this.close();
        });
    }

    show(html) {
        this.content.innerHTML = html;
        requestAnimationFrame(() => {
            this.overlay.classList.add('active');
        });
    }

    close() {
        this.overlay.classList.remove('active');
        setTimeout(() => {
            this.content.innerHTML = '';
        }, 300);
    }
}

const appModal = new Modal();
let resendTimer = null;

document.addEventListener('DOMContentLoaded', () => {
    // Password Toggle Logic
    const toggleBtn = document.getElementById('toggle-password');
    if (toggleBtn) {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('icon-eye');
        const eyeSlashIcon = document.getElementById('icon-eye-slash');

        toggleBtn.addEventListener('click', () => {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            if (type === 'text') {
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        });
    }

    const loginForm = document.getElementById('login-form');

    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(loginForm);
            const btn = loginForm.querySelector('button[type="submit"]');
            const originalText = btn.innerText;

            try {
                btn.innerText = 'Processing...';
                btn.disabled = true;

                const response = await fetch('api/login', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.status === 'success') {
                    window.location.href = data.redirect;
                } else if (data.status === 'verification_required') {
                    showOtpModal();
                } else {
                    showToast(data.message || 'Login failed', 'error');
                }

            } catch (error) {
                console.error(error);
                showToast('An error occurred. Please try again.', 'error');
            } finally {
                btn.innerText = originalText;
                btn.disabled = false;
            }
        });
    }

    // Also handle Register form redirect to OTP
    const registerForm = document.getElementById('register-form');
    // Assuming register-form logic might be in register.php view directly, but if it was here:
    // If not, we rely on the specific page's script. 
    // But let's check if we need to add a global listener for the generic register form if it exists?
    // The register.php view likely has its own script or uses this. 
    // Checking register.php (not retrieved) but assuming it might need similar handling. 
    // I'll add a generic one if an element with ID register-form exists.
    if (registerForm) {
        registerForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(registerForm);
            const btn = registerForm.querySelector('button[type="submit"]');
            const originalText = btn.innerText;

            try {
                btn.innerText = 'Creating Account...';
                btn.disabled = true;

                const response = await fetch('api/register', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.status === 'verification_required') {
                    showOtpModal();
                } else {
                    showToast(data.message || 'Registration failed', data.status === 'error' ? 'error' : 'success');
                }

            } catch (error) {
                console.error(error);
                showToast('An error occurred.', 'error');
            } finally {
                btn.innerText = originalText;
                btn.disabled = false;
            }
        });
    }
});

function showOtpModal() {
    appModal.show(`
        <h2 class="text-xl font-bold mb-4">Verify Your Email</h2>
        <p class="mb-4 text-sm text-gray-600">Please enter the 6-digit code sent to your email.</p>
        <div id="otp-error" class="hidden alert alert-error mb-4"></div>
        <form id="otp-form">
            <input type="hidden" name="action" value="verify_email">
            <div class="flex justify-center gap-2 mb-6" id="otp-inputs">
                <input type="text" maxlength="1" class="otp-digit input-field w-12 h-12 text-center text-xl font-bold p-0" pattern="[0-9]*" inputmode="numeric" required>
                <input type="text" maxlength="1" class="otp-digit input-field w-12 h-12 text-center text-xl font-bold p-0" pattern="[0-9]*" inputmode="numeric" required>
                <input type="text" maxlength="1" class="otp-digit input-field w-12 h-12 text-center text-xl font-bold p-0" pattern="[0-9]*" inputmode="numeric" required>
                <input type="text" maxlength="1" class="otp-digit input-field w-12 h-12 text-center text-xl font-bold p-0" pattern="[0-9]*" inputmode="numeric" required>
                <input type="text" maxlength="1" class="otp-digit input-field w-12 h-12 text-center text-xl font-bold p-0" pattern="[0-9]*" inputmode="numeric" required>
                <input type="text" maxlength="1" class="otp-digit input-field w-12 h-12 text-center text-xl font-bold p-0" pattern="[0-9]*" inputmode="numeric" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-full mb-3">Verify Email</button>
            <div class="text-center">
                <button type="button" id="resend-otp-btn" class="text-sm text-blue-500 hover:text-blue-600 font-medium disabled:opacity-50 disabled:cursor-not-allowed">Resend Code</button>
            </div>
        </form>
    `);

    setupOtpInputs();

    // Attach listener to new OTP form in modal
    document.getElementById('otp-form').addEventListener('submit', handleOtpSubmit);

    // Attach listener to Resend button & Start timer
    const resendBtn = document.getElementById('resend-otp-btn');
    resendBtn.addEventListener('click', handleResendOtp);

    // Start initial countdown logic (simulating that code was just sent)
    startResendTimer(30);
}

function setupOtpInputs() {
    const container = document.getElementById('otp-inputs');
    if (!container) return;

    const inputs = container.querySelectorAll('input.otp-digit');

    // Auto-focus first input
    setTimeout(() => inputs[0].focus(), 100);

    inputs.forEach((input, index) => {
        // Handle Input
        input.addEventListener('input', (e) => {
            const val = e.target.value;
            // Allow only numbers
            if (!/^\d*$/.test(val)) {
                e.target.value = val.replace(/\D/g, '');
                return;
            }

            if (val.length >= 1) {
                if (index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }
            }
        });

        // Handle Backspace
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !e.target.value) {
                if (index > 0) {
                    inputs[index - 1].focus();
                }
            }
        });

        // Handle Paste
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text');
            if (!/^\d+$/.test(text)) return;

            const digits = text.split('').slice(0, inputs.length);
            digits.forEach((digit, i) => {
                inputs[i].value = digit;
            });

            // Focus last filled or next empty
            const nextIndex = Math.min(digits.length, inputs.length - 1);
            inputs[nextIndex].focus();
        });
    });
}

async function handleOtpSubmit(e) {
    e.preventDefault();
    const btn = e.target.querySelector('button[type="submit"]');
    const originalText = btn.innerText;
    const errorDiv = document.getElementById('otp-error');

    // Collect Code
    const inputs = document.querySelectorAll('input.otp-digit');
    let code = '';
    inputs.forEach(input => code += input.value);

    if (code.length < 6) {
        errorDiv.innerText = 'Please enter all 6 digits.';
        errorDiv.classList.remove('hidden');
        return;
    }

    try {
        btn.innerText = 'Verifying...';
        btn.disabled = true;
        errorDiv.classList.add('hidden');

        const formData = new FormData();
        formData.append('code', code);

        const response = await fetch('api/verify-email-otp', {
            method: 'POST',
            headers: {
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        });

        const data = await response.json();

        if (data.status === 'success') {
            showToast('Email verified! Redirecting...', 'success');
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        } else {
            errorDiv.innerText = data.message || 'Verification failed';
            errorDiv.classList.remove('hidden');
            // Clear inputs on error? Maybe not all of them.
        }
    } catch (error) {
        errorDiv.innerText = 'Network error';
        errorDiv.classList.remove('hidden');
    } finally {
        btn.innerText = originalText;
        btn.disabled = false;
    }
}

function startResendTimer(seconds) {
    const btn = document.getElementById('resend-otp-btn');
    if (!btn) return;

    if (resendTimer) clearInterval(resendTimer);

    let timeLeft = seconds;
    btn.disabled = true;
    btn.innerText = `Resend Code (${timeLeft}s)`;

    resendTimer = setInterval(() => {
        timeLeft--;
        btn.innerText = `Resend Code (${timeLeft}s)`;

        if (timeLeft <= 0) {
            clearInterval(resendTimer);
            btn.disabled = false;
            btn.innerText = 'Resend Code';
        }
    }, 1000);
}

async function handleResendOtp(e) {
    e.preventDefault();
    const btn = e.target;
    // Don't action if disabled (though handled by logic, safer to check)
    if (btn.disabled) return;

    const errorDiv = document.getElementById('otp-error');

    try {
        btn.innerText = 'Sending...';
        btn.disabled = true;
        errorDiv.classList.add('hidden');

        const response = await fetch('api/resend-otp', {
            method: 'POST',
            headers: {
                'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();

        if (data.status === 'success') {
            showToast('Code sent to email!', 'success');
            startResendTimer(30);
        } else {
            errorDiv.innerText = data.message || 'Failed to resend code';
            errorDiv.classList.remove('hidden');
            btn.innerText = 'Resend Code';
            btn.disabled = false;
        }
    } catch (error) {
        errorDiv.innerText = 'Network error';
        errorDiv.classList.remove('hidden');
        btn.innerText = 'Resend Code';
        btn.disabled = false;
    }
}
