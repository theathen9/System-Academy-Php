<?php
//./admin/register.php
session_start();

include_once __DIR__ . '/../config/db.php';
include_once __DIR__ . '/../core/DB.php';
// include_once __DIR__ . '/../../core/CRUD.php';
include_once __DIR__ . '/../core/ORM.php';
include_once __DIR__ . '/../data/dbSchemaData.php';
include_once __DIR__ . '/../data/functionData.php';
include_once __DIR__ . '/../data/register_staff.php';
include_once __DIR__ . '/../data/register_student.php';
require_once __DIR__ . '/../auth/auth.php';
// include_once __DIR__ . '/api/dashboard.php';
// Generate token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


$userId = checkAuth();

if (!$userId) {
    header("Location: " . BASE_URL . "/auth/signin.php");
    exit;
}

authorizeRole([1]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/register_process.php';
    exit; // stop rendering page
}
//     var_dump($_SESSION);
// var_dump($_COOKIE);
// exit;


// $cambodaiData = json_decode(file_get_contents(__DIR__ . '/../data/addressCambodia.json'), true);

include __DIR__ . '/../data/dataSchema.php';
// include_once "/config/db.php";
$staticSchemaData[0]["active"] = false;
$staticSchemaData[7]["active"] = true;
// $staticSchemaData[1]['submenu'][3]['active'] = true;

// Get Employees
// $getEmployees;
// $result = getEmployees($conn);



$type = $_GET['type'] ?? 'student';
$queryString = "?type={$type}";
$step = $_POST['step'] ?? null;


$db = new DB($conn);
$studentCRUD = new ORM($db, "tblStudents", "student_id");
$classCRUD = new ORM($db, "tblClasses", "class_id");
$enrollmentCRUD = new ORM($db, "tblEnrollments", "enrollment_id");

$getClass = $classCRUD->get("*", "", [], "", 100, 0);


$row = $studentCRUD
    ->select("COUNT(*) as total")
    ->first();

$totalStudents = $row['total'] ?? 0;
$idCode = $totalStudents + 1;


$classes = [];


if (is_array($getClass)) {
    foreach ($getClass as $row) {
        $classes[$row['class_id']] = $row;
    }
}


$type = $_GET['type'] ?? 'student';
$typeAddr = $_GET['typeAddr'] ?? 'provinces';
$queryString = "?type={$type}";



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | <?= htmlspecialchars($infoSchemaData[1]["name_short"]) ?></title>
    <link rel="icon" type="image/png" href="<?php echo $infoSchemaData[5]["image"] ?>">
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
                <img src="<?php echo $infoSchemaData[5]["image"] ?>" width="60" height="60" alt="logo" class="rounded-circle">
                <div class="title">
                    <p class="m-auto"><?php echo $infoSchemaData[1]["name_short"] ?></p>
                </div>
            </div>
            <ul class="nav flex-column">
                <ul class="nav flex-column">
                    <?php foreach ($staticSchemaData as $item): ?>
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
                class="d-flex justify-content-between align-items-center px-2 py-2 bg-white py-md-1 position-sticky top-0 z-3">
                <div class="title">Welcome to <?php echo $infoSchemaData[0]["name"] ?></div>

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
                        <!-- student register -->

                        <div id="studentSection">
                            <form method="post" enctype="multipart/form-data">
                                <input type="hidden" name="step" id="step" value="1">
                                <input type="hidden" name="action" value="register_student">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="created_by" value="<?= $_SESSION['reference_id'] ?? '' ?>">
                                <?php register_student($conn, []); ?>
                            </form>
                        </div>

                        <div id="staffSection" style="display:none;">
                            <form method="post" enctype="multipart/form-data">
                                <input type="hidden" name="action" value="register_staff">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <input type="hidden" name="created_by" value="<?= $_SESSION['reference_id'] ?? '' ?>">
                                <?php register_staff($conn); ?>
                            </form>
                        </div>


                    </div>
                    <?php if (!empty($_SESSION['success']) || !empty($_SESSION['error'])): ?>
                        <div class="container mt-3">
                            <?php if (!empty($_SESSION['success'])): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?= htmlspecialchars($_SESSION['success']) ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php unset($_SESSION['success']); ?>
                            <?php endif; ?>

                            <?php if (!empty($_SESSION['error'])): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?= htmlspecialchars($_SESSION['error']) ?>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                                <?php unset($_SESSION['error']); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    <script>
        document.getElementById("student_photo").addEventListener("change", function() {

            const file = this.files[0];

            if (file) {
                const preview = document.getElementById("studentPreviewPhoto");
                preview.src = URL.createObjectURL(file);
            }

        });
        document.getElementById("staff_photo").addEventListener("change", function() {

            const file = this.files[0];

            if (file) {
                const preview = document.getElementById("staffPreviewPhoto");
                preview.src = URL.createObjectURL(file);
            }

        });
    </script>

    <!-- <script>
        let addressData = {}; // global variable

        async function loadAddressData() {
            const response = await fetch("/system-management/data/addressCambodia.json");
            addressData = await response.json();
            console.log(addressData); // now you have actual data
        }
        

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

                // Show selects
                district.style.display = "block";
                commune.style.display = "block";
                village.style.display = "block";

                if (district.value === "other") {
                    commune.disabled = false;

                    province.style.display = "block";
                    district.style.display = "none";
                    district.name = "";


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


                if (commune.value == "other") {
                    if (district.value == "other") {
                        otherDistrictInput.style.display = "block";
                        otherCommuneInput.style.display = "block";
                        otherCommuneInput.name = prefix + "_commune";
                        commune.name = "";

                        commune.style.display = "none";
                        village.disabled = false;


                        return
                    }

                    // Show selects
                    district.style.display = "block";
                    commune.style.display = "block";
                    village.style.display = "block";
                    otherDistrictInput.style.display = "none";
                    otherCommuneInput.name = prefix + "_commune";
                    otherCommuneInput.style.display = "block";
                    commune.style.display = "none";
                    commune.name = "";
                    village.disabled = false;
                    return

                } else {
                    // Hide "other" inputs
                    otherDistrictInput.style.display = "none";
                    otherDistrictInput.name = "";
                    otherCommuneInput.style.display = "none";
                    otherCommuneInput.name = "";
                    otherVillageInput.style.display = "none";
                    otherVillageInput.name = "";
                    village.disabled = false;
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
                    return
                }

                if (!commune.value) return;

                village.disabled = false;


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

                // Show selects
                district.style.display = "block";
                commune.style.display = "block";
                village.style.display = "block";


                if (village.value === "other") {
                    if (commune.value == "other") {
                        district.style.display = "none"
                    }


                    village.style.display = "none";
                    commune.style.display = "none";
                    village.name = "";
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
            const type = <?= json_encode($type) ?>; // <-- define first

            if (type === "student") {
                setupAddress("student_dob_addr");
                setupAddress("student_curr_addr");
                setupAddress("student_guardian_curr_addr");
            } else {
                setupAddress("dob_addr");
                setupAddress("curr_addr");
                setupAddress("guardian_curr_addr");
            }
        });
    </script> -->

    <script>
        let addressData = {}; // global variable

        // Load JSON data asynchronously
        async function loadAddressData() {
            const response = await fetch("/system-management/data/addressCambodia.json");
            addressData = await response.json();
            console.log("Address data loaded:", addressData);
        }

        // Setup the address selects
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

                // Show selects
                district.style.display = "block";
                commune.style.display = "block";
                village.style.display = "block";

                if (district.value === "other") {
                    commune.disabled = false;

                    province.style.display = "block";
                    district.style.display = "none";
                    district.name = "";


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

                otherVillageInput.style.display = "none";
                otherVillageInput.name = "";
                village.innerHTML = "<option value=''>-- Village --</option><option value='other'>-- Other --</option>";
                village.disabled = true;


                if (commune.value == "other") {
                    if (district.value == "other") {
                        otherDistrictInput.style.display = "block";
                        otherCommuneInput.style.display = "block";
                        otherCommuneInput.name = prefix + "_commune";
                        commune.name = "";

                        commune.style.display = "none";
                        village.disabled = false;


                        return
                    }

                    // Show selects
                    district.style.display = "block";
                    commune.style.display = "block";
                    village.style.display = "block";
                    otherDistrictInput.style.display = "none";
                    otherCommuneInput.name = prefix + "_commune";
                    otherCommuneInput.style.display = "block";
                    commune.style.display = "none";
                    commune.name = "";
                    village.disabled = false;
                    return

                } else {
                    // Hide "other" inputs
                    otherDistrictInput.style.display = "none";
                    otherDistrictInput.name = "";
                    otherCommuneInput.style.display = "none";
                    otherCommuneInput.name = "";
                    otherVillageInput.style.display = "none";
                    otherVillageInput.name = "";
                    village.disabled = false;
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
                    return
                }

                if (!commune.value) return;

                village.disabled = false;


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

                // Show selects
                district.style.display = "block";
                commune.style.display = "block";
                village.style.display = "block";


                if (village.value === "other" && commune.value == "other" && district.value == "") {
                    village.style.display = "none";
                    commune.style.display = "none";
                    village.name = "";
                    otherVillageInput.style.display = "block";
                    otherVillageInput.name = prefix + "_village";

                    return;
                }
                if (village.value === "other" && commune.value == "other" && district.value == "other") {

                    district.style.display = "none";
                    village.style.display = "none";
                    commune.style.display = "none";

                    otherVillageInput.style.display = "block";
                    otherVillageInput.name = prefix + "_village";


                    return;
                }
                if (village.value === "other" && commune.value == "other" && district.value !== "") {

                    village.style.display = "none";
                    commune.style.display = "none";

                    otherVillageInput.style.display = "block";
                    otherVillageInput.name = prefix + "_village";


                    return;
                }
                if (village.value === "other" && commune.value == "other") {

                    village.style.display = "none";
                    commune.style.display = "none";

                    otherVillageInput.style.display = "block";
                    otherVillageInput.name = prefix + "_village";


                    return;
                }

                if (village.value === "other" && commune.value !== "other") {
                    village.style.display = "none";

                    otherVillageInput.style.display = "block";
                    otherVillageInput.name = prefix + "_village";


                    return;
                }



                otherVillageInput.style.display = "none";
                otherVillageInput.name = "";

                return;
            };

        }


        document.addEventListener("DOMContentLoaded", async function() {
            let type = <?= json_encode($type) ?>;
            let typeAddr = <?= json_encode($typeAddr) ?>;
            console.log(type);
            const studentBtn = document.getElementById("studentBtn");
            const staffBtn = document.getElementById("staffBtn");
            const studentSection = document.getElementById("studentSection");
            const staffSection = document.getElementById("staffSection");

            // Load address JSON
            const response = await fetch("/system-management/data/addressCambodia.json");
            addressData = await response.json();

            // Setup address selects
            const studentPrefixes = ["student_dob_addr", "student_curr_addr", "student_guardian_curr_addr"];
            const staffPrefixes = ["dob_addr", "curr_addr"];
            studentPrefixes.forEach(p => setupAddress(p));
            staffPrefixes.forEach(p => setupAddress(p));

            let currentType = type;
            console.log(currentType);


            // ---------- TOGGLE SYSTEM ----------
            function setSection(active, inactive, activeBtn, inactiveBtn) {

                active.style.display = "block";
                inactive.style.display = "none";

                activeBtn.classList.add("btn-primary");
                activeBtn.classList.remove("btn-secondary");

                inactiveBtn.classList.add("btn-secondary");
                inactiveBtn.classList.remove("btn-primary");

                active.querySelectorAll("input, select, textarea")
                    .forEach(el => el.disabled = false);

                inactive.querySelectorAll("input, select, textarea")
                    .forEach(el => el.disabled = true);
            }

            function setActive(show, hide, activeBtn, inactiveBtn) {

                show.style.display = "block";
                hide.style.display = "none";

                activeBtn.classList.add("btn-primary");
                activeBtn.classList.remove("btn-secondary");

                inactiveBtn.classList.add("btn-secondary");
                inactiveBtn.classList.remove("btn-primary");

                show.querySelectorAll("input, select, textarea")
                    .forEach(el => el.disabled = false);

                hide.querySelectorAll("input, select, textarea")
                    .forEach(el => el.disabled = true);
            }

            function showStudent() {
                console.log("student");
                setActive(studentSection, staffSection, studentBtn, staffBtn);
            }

            function showStaff() {
                console.log("staff");
                setActive(staffSection, studentSection, staffBtn, studentBtn);
            }

            studentBtn.addEventListener("click", showStudent);
            staffBtn.addEventListener("click", showStaff);

            // IMPORTANT: initial state

            if (type === "staff") {
                showStaff();
            } else {
                showStudent();
            }
            // Clean URL query
            if (window.history.replaceState) {
                window.history.replaceState({}, document.title, window.location.pathname);
            }

            // Flatpickr
            flatpickr("#student_dob", {
                dateFormat: "Y-m-d", // ✅ value sent to backend
                altInput: true, // ✅ show separate input
                altFormat: "d-m-Y", // ✅ what user sees
                maxDate: "today",
                allowInput: true,
                monthSelectorType: "dropdown",
                yearSelectorType: "dropdown"
            });
            flatpickr("#student_register_at", {

                dateFormat: "Y-m-d",
                maxDate: "today",
                allowInput: true,
                altFormat: "d-m-Y",
                monthSelectorType: "dropdown",
                yearSelectorType: "dropdown",
                onReady: function(selectedDates, dateStr, instance) {
                    if (instance.altInput) {
                        instance.altInput.placeholder = "Student Register Date";
                    }
                }

            });
            flatpickr("#dob", {
                dateFormat: "Y-m-d",
                maxDate: "today",
                allowInput: true,
                altFormat: "d-m-Y",
                monthSelectorType: "dropdown",
                yearSelectorType: "dropdown"
            });
        });
    </script>



</body>

</html>