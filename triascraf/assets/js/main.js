// ============================================
// TRIASCRAF — Main JavaScript
// ============================================

// Navbar toggle (mobile)
function toggleMenu() {
    const nav = document.getElementById('navLinks');
    if (nav) nav.classList.toggle('open');
}

// Auto-hide alerts after 4 seconds
document.addEventListener('DOMContentLoaded', () => {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(el => {
        setTimeout(() => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        }, 4000);
    });

    // Animate cards on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.style.opacity = '1';
                e.target.style.transform = 'translateY(0)';
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.product-card, .category-card, .testimonial-card, .stat-card').forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        observer.observe(el);
    });
});

// Quantity controls
function changeQty(input, delta) {
    let val = parseInt(input.value) + delta;
    const min = parseInt(input.min) || 1;
    const max = parseInt(input.max) || 999;
    input.value = Math.min(max, Math.max(min, val));
}

// Image preview on file upload
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);
    if (input.files && input.files[0] && preview) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Confirm delete
function confirmDelete(msg) {
    return confirm(msg || 'Yakin ingin menghapus data ini?');
}

// Toast notification
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
    toast.style.cssText = `
        position:fixed; bottom:24px; right:24px; z-index:9999;
        background:${type === 'success' ? '#27AE60' : '#C0392B'};
        color:white; padding:14px 20px; border-radius:50px;
        font-family:'DM Sans',sans-serif; font-size:14px;
        display:flex; align-items:center; gap:10px;
        box-shadow:0 8px 32px rgba(0,0,0,0.15);
        animation: slideUp 0.3s ease;
    `;
    document.body.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s'; setTimeout(() => toast.remove(), 300); }, 3000);
}

// Style for toast animation
const style = document.createElement('style');
style.textContent = `@keyframes slideUp { from { transform: translateY(20px); opacity:0; } to { transform: translateY(0); opacity:1; } }`;
document.head.appendChild(style);
