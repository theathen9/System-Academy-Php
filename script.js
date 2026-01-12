<<<<<<< HEAD
// Close dropdown when clicking outside
document.querySelectorAll('[data-close-dropdown]').forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('studentDropdown').classList.remove('show');
    });
});


=======
// const addBtn = document.getElementById('addBtn');
// const cancelBtn = document.getElementById('cancelBtn');
// const form = document.getElementById('studentForm');

// const dropdown = bootstrap.Dropdown.getOrCreateInstance(addBtn);
// document.addEventListener('DOMContentLoaded', () => {
//     const addBtn = document.getElementById('addBtn');

//     if (addBtn) {
//         const dropdown = bootstrap.Dropdown.getOrCreateInstance(addBtn);
//         dropdown.hide(); // force close on reload
//     }
// });

// // Close on Cancel
// cancelBtn.addEventListener('click', () => {
//     dropdown.hide();
// });

// // Close on Save
// form.addEventListener('submit', (e) => {
//     e.preventDefault();
//     // TODO: save data here
//     dropdown.hide();
// });

const modalEl = document.getElementById('studentModal');
const modal = new bootstrap.Modal(modalEl);

document.getElementById('studentForm').addEventListener('submit', (e) => {
    e.preventDefault();

    // TODO: save data (AJAX / fetch / PHP)

    modal.hide(); // close modal safely
});
>>>>>>> 512c3b3 (first commit)
