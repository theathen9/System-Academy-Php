<?php
//./admin/register.php

// require_once( __DIR__ . "/../../config/db.php");
include_once __DIR__ . '/../config/db.php';
include_once __DIR__ . '/../data/dbShemaData.php';
include_once __DIR__ . '/../data/functionData.php';
include_once __DIR__ . '/../data/register_staff.php';
include_once __DIR__ . '/../data/register_student.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    include "register_process.php";
}

$cambodaiData = json_decode(file_get_contents(__DIR__ . '/../data/addressCambodia.json'), true);

include "../data/dataShema.php";
// include_once "/config/db.php";
$staticShemaData[0]["active"] = false;
$staticShemaData[6]["active"] = true;
// $staticShemaData[1]['submenu'][3]['active'] = true;

// Get Employees
// $getEmployees;
// $result = getEmployees($conn);



$type = $_GET['type'] ?? 'student';
$queryString = "?type={$type}";



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Empowerment Education English One</title>
    <link rel="icon" type="image/png" href="<?php echo $infoShemaData[4]["image"] ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css"
        integrity="sha512-t7Few9xlddEmgd3oKZQahkNI4dS6l80+eGEzFQiqtyVYdvcSG2D3Iub77R20BdotfRPA9caaRkg1tyaJiPmO0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../src/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</head>

<body class="container-fluid p-0 overflow-x-hidden">
    <div class="row g-3">
        <nav class="navBar col-12 col-md-3 col-sm-3 col-lg-2 p-3 vh-100 position-sticky top-0">
            <div class="d-flex gap-1 mb-4 align-items-center align-self-center">
                <img src="<?php echo $infoShemaData[4]["image"] ?>" width="60" height="60" alt="logo" class="rounded-circle">
                <div class="title">
                    <p class="m-auto">Empowerment <br>Education English One</p>
                </div>
            </div>
            <ul class="nav flex-column">
                <ul class="nav flex-column">
                    <?php foreach ($staticShemaData as $item): ?>
                        <?php if (isset($item['submenu'])): ?>
                            <li class="nav-item mb-1">
                                <a class="nav-link rounded d-flex justify-content-between align-items-center
            <?= !empty($item['active']) ? 'text-dark' : ' text-dark'; ?>" data-bs-toggle="collapse"
                                    href="#<?= $item['submenu_id']; ?>">
                                    <?= $item['title']; ?>
                                    <span class="bi bi-chevron-down"></span>
                                </a>
                                <ul class="nav collapse flex-column ms-3
            <?= !empty($item['active']) ? 'show' : ''; ?>" id="<?= $item['submenu_id']; ?>">

                                    <?php foreach ($item['submenu'] as $sub): ?>
                                        <li class="nav-item mb-1 w-100">
                                            <a href="<?= $sub['link']; ?>" class="nav-link rounded
                        <?= !empty($sub['active']) ? 'bg-primary text-white' : 'text-dark'; ?>">
                                                <?= $sub['title']; ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>

                                </ul>
                            </li>

                        <?php else: ?>
                            <li class="nav-item mb-1 w-100">
                                <a href="<?= $item['link']; ?>" class="nav-link rounded
            <?= !empty($item['active']) ? 'bg-primary text-white' : 'text-dark'; ?>">
                                    <?php if (!empty($item['icon'])): ?>
                                        <i class="<?= $item['icon']; ?> me-1"></i>
                                    <?php endif; ?>
                                    <?= $item['title']; ?>
                                </a>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </ul>
        </nav>

        <!-- Main area -->
        <main class="col-lg-10 bg-light">
            <div
                class="d-flex justify-content-between align-items-center px-2 py-2 bg-white py-md-1 position-sticky top-0 ">
                <div class="title">Welcome to <?php echo $infoShemaData[0]["name"] ?></div>

                <div class="dropdown">
                    <button class="d-flex align-items-center border-0 bg-white gap-2" data-bs-toggle="dropdown">
                        <img src="../src/assets/logo.jpg" width="60" height="60" style="border-radius:50%">
                        <div>Username</div>
                    </button>

                    <ul class="dropdown-menu bg-white">
                        <a href="../auth/signout.php" class="text-decoration-none">
                            <li><button class="dropdown-item">Sign Out</button></li>
                            <li><button class="dropdown-item">Account</button></li>
                        </a>
                    </ul>
                </div>
            </div>

            <!-- form -->

            <div class="container my-3">
                <div class="row">
                    <div class="w-100 ">
                        <div>
                            <button style="width: 90px;" type="button" id="studentBtn" class="btn btn-primary">Student</button>
                            <button style="width: 90px;" type="button" id="staffBtn" class="btn btn-secondary">Staff</button>
                        </div>

                        <form method="post" enctype="multipart/form-data" autocomplete="off">
                            <!-- Student -->
                            <?php register_student($conn); ?>

                            <!-- Staff -->
                            <?php register_staff($conn); ?>

                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        const input = document.getElementById('profileInput');
        const preview = document.getElementById('preview');

        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result; // Set preview image
                    preview.style.display = 'block'; // Show the image
                }
                reader.readAsDataURL(file);
            }
        });
    </script>

    <script>
        const addressData = <?php echo json_encode($cambodaiData, JSON_UNESCAPED_UNICODE); ?>;
        console.log(addressData);

        // function setupAddress(prefix) {
        //     const province = document.getElementById(prefix + "_province");
        //     const district = document.getElementById(prefix + "_district");
        //     const commune = document.getElementById(prefix + "_commune");
        //     const village = document.getElementById(prefix + "_village");
        //     // Get the selects and "other" input fields by Staff input other fiel
        //     const villageSelect = document.getElementById("guardian_curr_addr_village");
        //     const otherVillageInput = document.getElementById("otherGuardianVillage");

        //     const communeSelect = document.getElementById("guardian_curr_addr_commune");
        //     const otherCommuneInput = document.getElementById("otherGuardianCommune");

        //     const districtSelect = document.getElementById("guardian_curr_addr_district");
        //     const otherDistrictInput = document.getElementById("otherGuardianDistrict");

        //     // CLEAR province before loading
        //     // province.innerHTML = "<option value=''>-- Province --</option>";



        //     Object.keys(addressData).forEach(p => {
        //         province.add(new Option(p, p));
        //     });


        //     province.onchange = () => {

        //         // Reset dropdowns
        //         district.innerHTML = "<option value=''>-- District --</option><option value='other'>-- other --</option>";
        //         commune.innerHTML = "<option value=''>-- Commune --</option><option value='other'>-- other --</option>";
        //         village.innerHTML = "<option value=''>-- Village --</option><option value='other'>-- other --</option>";

        //         // Disable all
        //         district.disabled = true;
        //         commune.disabled = true;
        //         village.disabled = true;

        //         // Reset "other village" input
        //         districtSelect.style.display = "block";
        //         communeSelect.style.display = "block";
        //         villageSelect.style.display = "block";

        //         otherDistrictInput.style.display = "none";
        //         otherDistrictInput.name = "";
        //         otherCommuneInput.style.display = "none";
        //         otherCommuneInput.name = "";
        //         otherVillageInput.style.display = "none";
        //         otherVillageInput.name = "";

        //         if (!province.value) return;

        //         // Enable district
        //         district.disabled = false;

        //         // Load districts
        //         Object.keys(addressData[province.value].districts).forEach(d => {
        //             district.add(new Option(d, d));
        //         });
        //     };
        //     district.onchange = () => {

        //         commune.innerHTML = "<option value=''>-- Commune --</option><option value='other'>-- other --</option>";
        //         village.innerHTML = "<option value=''>-- Village --</option><option value='other'>-- other --</option>";

        //         commune.disabled = true;
        //         village.disabled = true;

        //         if (district.value === "other") {
        //             districtSelect.style.display = "none";
        //             otherDistrictInput.style.display = "block";
        //             otherDistrictInput.name = "guardian_curr_addr_district";
        //             return;
        //         }

        //         otherDistrictInput.style.display = "none";
        //         otherDistrictInput.name = "";

        //         if (!district.value) return;

        //         commune.disabled = false;

        //         Object.keys(addressData[province.value].districts[district.value].communes).forEach(c => {
        //             commune.add(new Option(c, c));
        //         });
        //     };
        //     commune.onchange = () => {

        //         village.innerHTML = "<option value=''>-- Village --</option><option value='other'>-- other --</option>";
        //         village.disabled = true;

        //         if (!commune.value) return;

        //         village.disabled = false;

        //         const villages =
        //             addressData[province.value]
        //             .districts[district.value]
        //             .communes[commune.value]
        //             .villages;

        //         if (Array.isArray(villages)) {

        //             // If villages is an array
        //             villages.forEach(v => {
        //                 village.add(new Option(v, v));
        //             });

        //         } else {

        //             // If villages is an object
        //             Object.keys(villages).forEach(v => {
        //                 village.add(new Option(v, v));
        //             });

        //         }
        //     };
        //     village.onchange = () => {

        //         if (village.value === "other") {

        //             villageSelect.style.display = "none";
        //             otherVillageInput.style.display = "block";
        //             otherVillageInput.name = "guardian_curr_addr_village";

        //             return;
        //         }

        //         otherVillageInput.style.display = "none";
        //         otherVillageInput.name = "";
        //     };
        // };

        function setupAddress(prefix) {

            const province = document.getElementById(prefix + "_province");
            const district = document.getElementById(prefix + "_district");
            const commune = document.getElementById(prefix + "_commune");
            const village = document.getElementById(prefix + "_village");

            const otherDistrictInput = document.getElementById("other_" + prefix + "_district");
            const otherCommuneInput = document.getElementById("other_" + prefix + "_commune");
            const otherVillageInput = document.getElementById("other_" + prefix + "_village");

            // Clear provinces
            province.innerHTML = "<option value=''>-- Province --</option>";

            // Load provinces
            Object.keys(addressData).forEach(p => {
                province.add(new Option(p, p));
            });

            province.onchange = () => {

                // Reset all selects
                district.innerHTML = "<option value=''>-- District --</option><option value='other'>-- Other --</option>";
                commune.innerHTML = "<option value=''>-- Commune --</option><option value='other'>-- Other --</option>";
                village.innerHTML = "<option value=''>-- Village --</option><option value='other'>-- Other --</option>";

                // Hide "other" inputs
                otherDistrictInput.style.display = "none";
                otherDistrictInput.name = "";
                otherCommuneInput.style.display = "none";
                otherCommuneInput.name = "";
                otherVillageInput.style.display = "none";
                otherVillageInput.name = "";

                // Disable all selects first
                district.disabled = true;
                commune.disabled = true;
                village.disabled = true;

                // Show selects
                district.style.display = "block";
                commune.style.display = "block";
                village.style.display = "block";

                if (!province.value) return;

                // Populate districts first
                Object.keys(addressData[province.value].districts).forEach(d => {
                    district.add(new Option(d, d));
                });

                // Then enable district
                district.disabled = false;
            };

            district.onchange = () => {

                commune.innerHTML = "<option value=''>-- Commune --</option><option value='other'>-- Other --</option>";
                village.innerHTML = "<option value=''>-- Village --</option><option value='other'>-- Other --</option>";
                // Hide "other" inputs
                otherDistrictInput.style.display = "none";
                otherDistrictInput.name = "";
                otherCommuneInput.style.display = "none";
                otherCommuneInput.name = "";
                otherVillageInput.style.display = "none";
                otherVillageInput.name = "";

                commune.disabled = true;
                village.disabled = true;

                if (district.value === "other") {

                    district.style.display = "none";
                    otherDistrictInput.style.display = "block";
                    otherDistrictInput.name = prefix + "_district";

                    return;
                }

                otherDistrictInput.style.display = "none";
                otherDistrictInput.name = "";

                if (!district.value) return;

                commune.disabled = false;

                Object.keys(addressData[province.value].districts[district.value].communes).forEach(c => {
                    commune.add(new Option(c, c));
                });
            };

            commune.onchange = () => {

                village.innerHTML = "<option value=''>-- Village --</option><option value='other'>-- Other --</option>";
                village.disabled = true;

                // Hide "other" inputs
                otherDistrictInput.style.display = "none";
                otherDistrictInput.name = "";
                otherCommuneInput.style.display = "none";
                otherCommuneInput.name = "";
                otherVillageInput.style.display = "none";
                otherVillageInput.name = "";

                if (commune.value === "other") {

                    commune.style.display = "none";
                    otherCommuneInput.style.display = "block";
                    otherCommuneInput.name = prefix + "_commune";

                    return;
                }

                otherCommuneInput.style.display = "none";
                otherCommuneInput.name = "";

                if (!commune.value) return;

                village.disabled = false;

                // Hide "other" inputs
                otherDistrictInput.style.display = "none";
                otherDistrictInput.name = "";
                otherCommuneInput.style.display = "none";
                otherCommuneInput.name = "";
                otherVillageInput.style.display = "none";
                otherVillageInput.name = "";

                const villages =
                    addressData[province.value]
                    .districts[district.value]
                    .communes[commune.value]
                    .villages;

                if (Array.isArray(villages)) {
                    villages.forEach(v => village.add(new Option(v, v)));
                } else {
                    Object.keys(villages).forEach(v => village.add(new Option(v, v)));
                }
            };

            village.onchange = () => {

                if (village.value === "other") {

                    village.style.display = "none";
                    otherVillageInput.style.display = "block";
                    otherVillageInput.name = prefix + "_village";

                    return;
                }

                otherVillageInput.style.display = "none";
                otherVillageInput.name = "";
            };

        }
        // setupAddress("guardian_curr_addr");
        // setupAddress("student_curr_addr");
        // setupAddress("student_dob_addr");

        document.addEventListener("DOMContentLoaded", function() {

            setupAddress("student_dob_addr");
            setupAddress("student_curr_addr");
            setupAddress("student_guardian_curr_addr");

            setupAddress("dob_addr");
            setupAddress("curr_addr");
            setupAddress("guardian_curr_addr");

        });
    </script>

    <script>
        document.getElementById("saveCourseBtn").addEventListener("click", function() {

            const course = document.querySelector("[name='course']").value;
            const level = document.querySelector("[name='level']").value;
            const language = document.querySelector("[name='language']").value;
            const startDate = document.querySelector("[name='start_date']").value;
            const comments = document.querySelector("[name='comments']").value;

            if (!course) return;

            let courseCounter = 0;
            const enrolDiv = document.getElementById("enrollment");

            function updateBadge() {
                document.getElementById("courseCount").innerText =
                    enrolDiv.children.length;
            }

            const row = document.createElement("div");
            row.className = "border p-2 mb-2 bg-light rounded";

            row.innerHTML = `
        ${course} | ${level} | ${language}

        <button type="button"
        class="btn btn-sm btn-danger float-end"
        onclick="this.parentElement.remove();updateBadge();">
        Remove
        </button>

        <input type="hidden" name="courses[${index}][course]" value="${course}">
        <input type="hidden" name="courses[${index}][level]" value="${level}">
        <input type="hidden" name="courses[${index}][language]" value="${language}">
        <input type="hidden" name="courses[${index}][start_date]" value="${startDate}">
        <input type="hidden" name="courses[${index}][comments]" value="${comments}">
    `;

            enrolDiv.appendChild(row);

            updateBadge();

            bootstrap.Modal.getInstance(document.getElementById('courseModal')).hide();
        });
    </script>

    <script>
        const type = <?= json_encode($type) ?>;

        console.log(type);

        const studentBtn = document.getElementById("studentBtn");
        const staffBtn = document.getElementById("staffBtn");

        const studentSection = document.getElementById("studentSection");
        const staffSection = document.getElementById("staffSection");

        // Unified function to show student section
        function showStudent() {
            studentSection.style.display = "block";
            staffSection.style.display = "none";

            studentBtn.classList.add("btn-primary");
            studentBtn.classList.remove("btn-secondary");

            staffBtn.classList.remove("btn-primary");
            staffBtn.classList.add("btn-secondary");


        }

        // Unified function to show staff section
        function showStaff() {
            studentSection.style.display = "none";
            staffSection.style.display = "block";

            staffBtn.classList.add("btn-primary");
            staffBtn.classList.remove("btn-secondary");

            studentBtn.classList.remove("btn-primary");
            studentBtn.classList.add("btn-secondary");


        }

        document.addEventListener("DOMContentLoaded", function() {

            setupAddress("student_dob_addr");
            setupAddress("student_curr_addr");
            setupAddress("student_guardian_curr_addr");

            setupAddress("dob_addr");
            setupAddress("curr_addr");
            setupAddress("guardian_curr_addr");


        });

        // Add only one listener per button
        studentBtn.addEventListener("click", showStudent);
        staffBtn.addEventListener("click", showStaff);

        // Auto toggle based on URL param "type"
        // const urlParams = new URLSearchParams(window.location.search);
        // const type = urlParams.get("type");


        // Clean the URL
        if (window.history.replaceState) {
            const cleanURL = window.location.pathname;
            window.history.replaceState({}, document.title, cleanURL);
        }
        // Auto toggle from URL
        if (type === "staff") {
            showStaff();
        } else {
            showStudent();
        }
        const urlParams = new URLSearchParams(window.location.search);

        // remove ?type=staff from URL
        if (window.history.replaceState) {
            const cleanURL = window.location.pathname;
            window.history.replaceState({}, document.title, cleanURL);
        }
    </script>


    <script>
        flatpickr("#student_dob", {
            dateFormat: "Y-m-d",
            maxDate: "today",
            allowInput: true,
            monthSelectorType: "dropdown",
            yearSelectorType: "dropdown"
        });

        flatpickr("#dob", {
            dateFormat: "Y-m-d",
            maxDate: "today",
            allowInput: true,
            monthSelectorType: "dropdown",
            yearSelectorType: "dropdown"
        });
    </script>



</body>

</html>