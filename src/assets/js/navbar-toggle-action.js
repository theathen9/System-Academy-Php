//   document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function(menu) {

//     const icon = menu.querySelector(".submenu-icon");
//     const target = document.querySelector(menu.getAttribute("href"));

//     if (!icon || !target) return;

//     target.addEventListener("show.bs.collapse", function() {
//     icon.classList.remove("bi-chevron-left");
//     icon.classList.add("bi-chevron-down");
//     });

//     target.addEventListener("hide.bs.collapse", function() {
//     icon.classList.remove("bi-chevron-down");
//     icon.classList.add("bi-chevron-left");
//     });
// });

document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function (menu) {

        const icon = menu.querySelector(".submenu-icon");
        const target = document.querySelector(menu.dataset.bsTarget || menu.getAttribute("href"));

        if (!icon || !target) return;

        function updateIcon() {
            if (target.classList.contains("show")) {
                icon.classList.replace("bi-chevron-left", "bi-chevron-down");
            } else {
                icon.classList.replace("bi-chevron-down", "bi-chevron-left");
            }
        }

        updateIcon();

        target.addEventListener("shown.bs.collapse", updateIcon);
        target.addEventListener("hidden.bs.collapse", updateIcon);
    });

});