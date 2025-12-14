/**
 * Custom Toast Notification
 * @param {string} message 
 * @param {'success'|'error'|'info'} type 
 */
function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');

    // Create Toast Element
    const toast = document.createElement('div');
    toast.className = `
        pointer-events-auto
        flex items-center gap-3 
        bg-white/95 backdrop-blur-md 
        border border-gray-100 
        shadow-2xl shadow-gray-200/50 
        rounded-full px-6 py-3.5 
        text-sm font-medium text-gray-700
        transform transition-all duration-500 ease-out 
        translate-y-[-20px] opacity-0
        whitespace-nowrap w-auto min-w-[300px] justify-center
    `;

    // Icon based on type
    let icon = '';
    if (type === 'success') {
        icon = `<svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
    } else if (type === 'error') {
        icon = `<svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
    } else {
        icon = `<svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
    }

    toast.innerHTML = `${icon} <span>${message}</span>`;

    container.appendChild(toast);

    // Animate In
    requestAnimationFrame(() => {
        toast.classList.remove('translate-y-[-20px]', 'opacity-0');
    });

    // Remove after 3s
    setTimeout(() => {
        toast.classList.add('opacity-0', 'translate-y-[-20px]');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 500);
    }, 4000);
}

// Attach to window so it's global
window.showToast = showToast;
