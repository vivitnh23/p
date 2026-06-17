        </div><!-- end admin-content -->
    </div><!-- end admin-main -->
</div><!-- end admin-layout -->

<script src="<?= BASE_URL ?>/assets/js/main.js"></script>
<script>
function toggleAdminSidebar() {
    const sidebar  = document.getElementById('adminSidebar');
    const overlay  = document.getElementById('sidebarOverlay');
    const isOpen   = sidebar.classList.contains('open');

    if (isOpen) {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
    } else {
        sidebar.classList.add('open');
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden'; // cegah scroll background
    }
}

// Tutup sidebar kalau layar diperbesar ke desktop
window.addEventListener('resize', () => {
    if (window.innerWidth > 1024) {
        document.getElementById('adminSidebar').classList.remove('open');
        document.getElementById('sidebarOverlay').classList.remove('show');
        document.body.style.overflow = '';
    }
});
</script>

<?= isset($extraScript) ? $extraScript : '' ?>
</body>
</html>
