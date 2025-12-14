<?php
$pageTitle = 'Register - ' . APP_NAME;
ob_start();
?>
<div>
    <h2 class="text-3xl font-bold mb-8 tracking-tight text-gray-900 animate-fade-in-up" style="animation-delay: 100ms;">Create Account</h2>

    <form id="register-form" action="<?= BASE_URL ?>/api/register" method="POST">
        <input type="hidden" name="action" value="register">
        
        <div class="grid grid-cols-2 gap-4 animate-fade-in-up" style="animation-delay: 200ms;">
            <div class="input-group group">
                <label for="first_name" class="label-text transition-colors group-focus-within:text-zinc-900">First Name</label>
                <input type="text" id="first_name" name="first_name" class="input-field transition-all duration-300 focus:ring-2 focus:ring-zinc-900 focus:border-transparent" required>
            </div>
            <div class="input-group group">
                <label for="last_name" class="label-text transition-colors group-focus-within:text-zinc-900">Last Name</label>
                <input type="text" id="last_name" name="last_name" class="input-field transition-all duration-300 focus:ring-2 focus:ring-zinc-900 focus:border-transparent" required>
            </div>
        </div>

        <div class="input-group group animate-fade-in-up" style="animation-delay: 300ms;">
            <label for="email" class="label-text transition-colors group-focus-within:text-zinc-900">Email Address</label>
            <input type="email" id="email" name="email" class="input-field transition-all duration-300 focus:ring-2 focus:ring-zinc-900 focus:border-transparent" required>
        </div>
        
        <div class="input-group group animate-fade-in-up" style="animation-delay: 350ms;">
            <label for="whatsapp_number" class="label-text transition-colors group-focus-within:text-zinc-900">WhatsApp Number <span class="text-gray-400 font-normal">(Optional)</span></label>
            <input type="text" id="whatsapp_number" name="whatsapp_number" class="input-field transition-all duration-300 focus:ring-2 focus:ring-zinc-900 focus:border-transparent" placeholder="+1234567890">
        </div>

        <div class="input-group group animate-fade-in-up relative" style="animation-delay: 400ms; z-index: 50;">
            <label class="label-text transition-colors group-focus-within:text-zinc-900">Location</label>
            <div class="relative" id="country-dropdown-container">
                <input type="hidden" id="location" name="location" required>
                <button type="button" id="country-dropdown-btn" class="w-full text-left input-field flex items-center justify-between transition-all duration-300 focus:ring-2 focus:ring-zinc-900 focus:border-transparent bg-white">
                    <span class="text-gray-400" id="country-selected-text">Select Country</span>
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                
                <div id="country-list" class="hidden absolute top-full left-0 right-0 mt-1 max-h-60 overflow-y-auto bg-white border border-gray-200 rounded-lg shadow-lg z-50">
                    <div class="p-2 sticky top-0 bg-white border-b border-gray-100">
                        <input type="text" id="country-search" class="w-full px-2 py-1 text-sm border border-gray-200 rounded focus:outline-none focus:border-zinc-500" placeholder="Search...">
                    </div>
                    <div id="country-items">
                        <!-- Items injected by JS -->
                    </div>
                </div>
            </div>
        </div>
        
        <div class="input-group group relative animate-fade-in-up" style="animation-delay: 400ms; z-index: 10;">
            <label for="password" class="label-text transition-colors group-focus-within:text-zinc-900">Password</label>
            <input type="password" id="password" name="password" class="input-field transition-all duration-300 focus:ring-2 focus:ring-zinc-900 focus:border-transparent" required>
        </div>
        
        <button type="submit" class="animate-fade-in-up btn btn-primary transform transition-transform duration-200 hover:scale-[1.02] active:scale-[0.98]" style="animation-delay: 500ms;">Sign Up</button>
    </form>
</div>
<div class="links mt-4 animate-fade-in-up" style="animation-delay: 600ms;">
    Already have an account? <a href="<?= BASE_URL ?>/login" class="underline">Login here</a>
</div>

<script src="<?= BASE_URL ?>/assets/countries.js"></script>
<script>
// Country Dropdown Logic
const dropdownBtn = document.getElementById('country-dropdown-btn');
const dropdownList = document.getElementById('country-list');
const countryItems = document.getElementById('country-items');
const countrySearch = document.getElementById('country-search');
const locationInput = document.getElementById('location');
const selectedText = document.getElementById('country-selected-text');

// Populate Countries
function renderCountries(filter = '') {
    countryItems.innerHTML = '';
    const filtered = countries.filter(c => c.name.toLowerCase().includes(filter.toLowerCase()));
    
    filtered.forEach(country => {
        const div = document.createElement('div');
        div.className = 'px-4 py-2 hover:bg-gray-50 cursor-pointer flex items-center gap-2 text-sm';
        div.innerHTML = `<span class="text-xl">${country.flag}</span> <span>${country.name}</span>`;
        div.onclick = () => {
            selectCountry(country);
        };
        countryItems.appendChild(div);
    });
}

function selectCountry(country) {
    locationInput.value = country.name;
    selectedText.innerHTML = `<span class="text-xl mr-2">${country.flag}</span> <span class="text-gray-900">${country.name}</span>`;
    selectedText.classList.remove('text-gray-400');
    dropdownList.classList.add('hidden');
}

dropdownBtn.addEventListener('click', () => {
    dropdownList.classList.toggle('hidden');
    if (!dropdownList.classList.contains('hidden')) {
        countrySearch.focus();
    }
});

countrySearch.addEventListener('input', (e) => {
    renderCountries(e.target.value);
});

// Close on click outside
document.addEventListener('click', (e) => {
    if (!document.getElementById('country-dropdown-container').contains(e.target)) {
        dropdownList.classList.add('hidden');
    }
});

// Initial Render
renderCountries();

// Register Form Logic
document.getElementById('register-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const form = e.target;
    
    // Custom Validation for Country
    if (!locationInput.value) {
        showToast('Please select a country', 'error');
        return;
    }

    const btn = form.querySelector('button[type="submit"]');
    const originalText = btn.innerText;

    try {
        btn.innerText = 'Creating Account...';
        btn.disabled = true;

        const formData = new FormData(form);
        const actionUrl = form.getAttribute('action');
        
        const response = await fetch(actionUrl, {
            method: 'POST',
            body: formData
        });

        const text = await response.text();
        // console.log("Raw Response:", text); // Debug only

        let data;
        try {
            data = JSON.parse(text);
        } catch (e) {
            throw new Error("Server returned invalid JSON: " + text.substring(0, 50) + "...");
        }
        
        if (data.status === 'success') {
            showToast('Account created! Logging in...', 'success');
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        } else if (data.status === 'verification_required') {
            // Use global OTP modal from app.js which has the 6-digit UI
            if (typeof showOtpModal === 'function') {
                showOtpModal();
            } else {
                console.error("showOtpModal not defined");
                showToast("Verification required, but UI failed to load.", "error");
            }
        } else {
            showToast(data.message || 'Registration failed', 'error');
        }
    } catch (error) {
        console.error("Registration Error:", error);
        // Try to show more details if available
        let msg = 'An error occurred.';
        if (error.message) msg += ' ' + error.message;
        showToast(msg, 'error');
    } finally {
        btn.innerText = originalText;
        btn.disabled = false;
    }
});
</script>

<?php
$content = ob_get_clean();
$extraScripts = '<script src="' . BASE_URL . '/assets/toast.js?v=' . time() . '"></script>' .
                '<script src="' . BASE_URL . '/assets/app.js?v=' . time() . '"></script>';
require ROOT_PATH . '/resources/views/layouts/main.php';
?>
