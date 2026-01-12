// Close dropdown when clicking outside
document.querySelectorAll('[data-close-dropdown]').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('studentDropdown').classList.remove('show');
    });
});


