<?php
//./admin/register.php
session_start();

include_once __DIR__ . '/../../config/db.php';
include_once __DIR__ . '/../../core/DB.php';
// include_once __DIR__ . '/../../core/CRUD.php';
include_once __DIR__ . '/../../core/ORM.php';
include_once __DIR__ . '/../../data/dbSchemaData.php';
include_once __DIR__ . '/../../data/functionData.php';
// include_once __DIR__ . '/../../data/register_staff.php';
// include_once __DIR__ . '/../../data/register_student.php';
require_once __DIR__ . '/../../auth/auth.php';
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

include __DIR__ . '/../../data/dataSchema.php';
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

// $id=8;

$autoNameF = "សា_" . time();
$autoNameL = "នា_" . time();
$autoNameEnF = "kim_" . time();
$autoNameEnL = "Na_" . time();
$student_code = sprintf("STU-%s-%05d", date("Y"), $idCode);
$start = strtotime("-25 years");
$end   = strtotime("-10 years");

$randomTimestamp = rand($start, $end);

$dob = date("Y-m-d", $randomTimestamp);

$prefixes = [
    '010',
    '011',
    '012',
    '015',
    '016',
    '017',
    '018',
    '060',
    '061',
    '066',
    '067',
    '068',
    '069',
    '070',
    '077',
    '078',
    '085',
    '086',
    '087',
    '088',
    '089',
    '090',
    '092',
    '093',
    '095',
    '096',
    '097',
    '098',
    '099'
];

$prefix = $prefixes[array_rand($prefixes)];
$number1 = $prefix . rand(1000000, 9999999); // 7 digits
$number2 = $prefix . rand(1000000, 9999999); // 7 digits

$email = "user" . rand(1000, 9999) . "@gmail.com";

$autoNameGMF = "ថា_" . time();
$autoNameGML = "មេង_" . time();
$autoNameGFF = "នា_" . time();
$autoNameGFL = "ហុង_" . time();
$numberG1 = $prefix . rand(1000000, 9999999); // 7 digits
$emailG = "user" . rand(1000, 9999) . "@gmail.com";



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

<body>
    <form id="studentForm" method="POST" enctype="multipart/form-data" class="card shadow border-0">
        <div class="card-body p-4 p-md-5">
            <input type="hidden" name="step" id="step" value="1">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            <!-- ================= STEP NAV ================= -->
            <div class="d-flex mb-4">


            </div>

            <!-- ================= STEP 1 ================= -->
            <div class="step" id="step1">


                <!-- Student Information -->

                <h3 class="mb-4">Student Information</h3>
                <div class="d-flex justify-content-between">
                    <div class="w-75">
                        <div class="row g-3 mb-3">
                            <input type="hidden" name="action" value="register_student">
                            <input type="text" name="student_code" value="<?= $student_code ?>" class="form-control" readonly>
                            <div class="col-md-4">
                                <input type="text" name="student_fst_name" value="<?= $autoNameF ?>" class="form-control" placeholder="First Name Khmer" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="student_middle_name" class="form-control" placeholder="Middle Name Khmer">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="student_lst_name" value="<?= $autoNameF ?>" class="form-control" placeholder="Last Name Khmer" required>
                            </div>

                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <input type="text" name="student_fst_name_eng" value="<?= $autoNameEnF ?>" class="form-control" placeholder="First Name English" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="student_middle_name_eng" class="form-control" placeholder="Middle Name English">
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="student_lst_name_eng" value="<?= $autoNameEnL ?>" class="form-control" placeholder="Last Name English" required>
                            </div>
                        </div>
                    </div>
                    <div class="w-25 text-center">

                        <label for="student_photo" style="cursor:pointer;">
                            <img id="studentPreviewPhoto"
                                src="<?= BASE_URL ?>/src/assets/register.png" alt=""
                                style="height:99px;width:99px;object-fit:cover;border-radius:6px;border:1px solid #ccc;">
                        </label>

                        <input
                            type="file"
                            name="student_photo"
                            id="student_photo"
                            accept="image/*"
                            style="display:none">

                        <div class="small text-muted mt-1">Click photo to upload</div>

                    </div>
                </div>

                <div class="row g-3 mb-5">
                    <div class="col-lg-3">
                        <input
                            type="text"
                            id="student_dob"
                            name="student_dob"
                            value="<?= $dob ?>"
                            class="form-control"
                            placeholder="Date of Birth"
                            required>
                    </div>
                    <div class="col-lg-3">
                        <select name="student_gender" id="gender" class="form-control" required>
                            <option value="">-- Gender --</option>
                            <option value="Male">ប្រុស</option>
                            <option value="Female">ស្រី</option>
                        </select>
                    </div>
                    <div class="col-lg-3">
                        <input type="text" name="student_academic_year" class="form-control" placeholder="Academic Year" required>
                    </div>


                </div>

                <!-- Address -->

                <h3 class="mb-4">Address Date Of Birth</h3>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <select id="student_dob_addr_province" name="student_dob_addr_province" class="form-select" required>
                            <option value="">-- Province --</option>

                        </select>

                    </div>
                    <div class="col-md-6">
                        <select id="student_dob_addr_district" name="student_dob_addr_district" class="form-select" disabled required>
                            <option value="">-- District --</option>
                            <option value="other">-- Other --</option>
                        </select>
                        <input type="text" id="other_student_dob_addr_district" class="form-control" placeholder="Enter village name" style="display:none;">

                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <select id="student_dob_addr_commune" name="student_dob_addr_commune" class="form-select" disabled required>
                            <option value="">-- Commune --</option>
                            <option value="other">-- Other --</option>

                        </select>
                        <input type="text" id="other_student_dob_addr_commune" class="form-control" placeholder="Enter village name" style="display:none;">
                    </div>
                    <div class="col-md-6">
                        <select id="student_dob_addr_village" name="student_dob_addr_village" class="form-select" disabled required>
                            <option value="">-- Village --</option>
                            <option value="other">-- Other --</option>
                        </select>
                        <input type="text" id="other_student_dob_addr_village" class="form-control" placeholder="Enter village name" style="display:none;">
                    </div>
                </div>

                <h3 class="mb-4">Current Address </h3>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <select id="student_curr_addr_province" name="student_curr_addr_province" class="form-select" required>
                            <option value="">-- Province --</option>

                        </select>

                    </div>
                    <div class="col-md-6">
                        <select id="student_curr_addr_district" name="student_curr_addr_district" class="form-select" disabled required>
                            <option value="">-- District --</option>
                            <option value="other">-- Other --</option>
                        </select>
                        <input type="text" id="other_student_curr_addr_district" class="form-control" placeholder="Enter village name" style="display:none;">

                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <select id="student_curr_addr_commune" name="student_curr_addr_commune" class="form-select" disabled required>
                            <option value="">-- Commune --</option>
                            <option value="other">-- Other --</option>

                        </select>
                        <input type="text" id="other_student_curr_addr_commune" class="form-control" placeholder="Enter village name" style="display:none;">
                    </div>
                    <div class="col-md-6">
                        <select id="student_curr_addr_village" name="student_curr_addr_village" class="form-select" disabled required>
                            <option value="">-- Village --</option>
                            <option value="other">-- Other --</option>
                        </select>
                        <input type="text" id="other_student_curr_addr_village" class="form-control" placeholder="Enter village name" style="display:none;">
                    </div>
                </div>

                <!-- Contact Information -->

                <h3 class="mb-4">Contact Information</h3>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <input type="email" name="student_email" value="<?= $email ?>" class="form-control" placeholder="Email Address">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <input type="tel" name="student_phone1" value="<?= $number1 ?>" class="form-control" placeholder="Phone 1">
                    </div>
                    <div class="col-md-6">
                        <input type="tel" name="student_phone2" class="form-control" placeholder="Phone 2">
                    </div>
                </div>

                <!-- guardian imformation -->

                <h3 class="mb-4">Parent/Guardian Information</h3>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <input type="text" name="student_guardian1_fst_name" value="<?= $autoNameGMF ?>" class="form-control" placeholder="Guardian First Name" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="student_guardian1_lst_name" value="<?= $autoNameGML ?>" class="form-control" placeholder="Guardian Last Name">
                    </div>
                    <div class="col-md-4">
                        <select name="student_guardian1_relationship" class="form-select" required>
                            <option value="">-- Relationship --</option>
                            <option value="Father">Father</option>
                            <option value="Mother">Mother</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <input type="text" name="student_guardian2_fst_name" value="<?= $autoNameGFF ?>" class="form-control" placeholder="Guardian First Name" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="student_guardian2_lst_name" value="<?= $autoNameGFL ?>" class="form-control" placeholder="Guardian Last Name">
                    </div>
                    <div class="col-md-4">
                        <select name="student_guardian2_relationship" class="form-select" required>
                            <option value="">-- Relationship --</option>
                            <option value="Father">Father</option>
                            <option value="Mother">Mother</option>
                        </select>
                    </div>

                </div>
                <!-- Phone -->
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <input type="text" name="student_guardian1_phone" value="<?= $numberG1 ?>" class="form-control" placeholder="Guardian 1 Phone Number " required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="student_guardian2_phone" class="form-control" placeholder="Guardian 2 Phone Number ">
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="student_guardian_email" class="form-control" placeholder="Guardian Email">
                    </div>
                </div>



                <h3 class="mb-4">Parent/Guardian Current Address </h3>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <select id="student_guardian_curr_addr_province" name="student_guardian_curr_addr_province" class="form-select" required>
                            <option value="">-- Province --</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select id="student_guardian_curr_addr_district" name="student_guardian_curr_addr_district" class="form-select" disabled required>
                            <option value="">-- District --</option>
                            <option value="other">-- Other --</option>

                        </select>
                        <input type="text" id="other_student_guardian_curr_addr_district" class="form-control" placeholder="Enter village name" style="display:none;">

                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <select id="student_guardian_curr_addr_commune" name="student_guardian_curr_addr_commune" class="form-select" disabled required>
                            <option value="">-- Commune --</option>
                            <option value="other">-- Other --</option>

                        </select>
                        <input type="text" id="other_student_guardian_curr_addr_commune" class="form-control" placeholder="Enter village name" style="display:none;">

                    </div>
                    <div class="col-md-6">
                        <select id="student_guardian_curr_addr_village" name="student_guardian_curr_addr_village" class="form-select" disabled required>
                            <option value="">-- Village --</option>
                            <option value="other">-- Other --</option>
                        </select>
                        <input type="text" id="other_student_guardian_curr_addr_village" class="form-control" placeholder="Enter village name" style="display:none;">

                    </div>
                </div>

                <button type="button" class="btn btn-primary next-step">Next</button>

            </div>

            <!-- ================= STEP 2 ================= -->
            <div class="step d-none" id="step2">

                <h3 class="mb-4">Assign Class</h3>

                <div class="row">
                    <div class="mb-3 d-flex">
                        <div class="col-lg-6">
                            <label class="form-label">Select Class</label>

                            <select name="class_ids[]" id="classSelect" class="form-select" multiple required>
                                <option value="" disabled>-- Choose Class --</option>

                                <?php foreach ($classes as $classId => $class): ?>

                                    <?php
                                    $total  = (int)($class['current_students'] ?? 0);
                                    $max    = (int)($class['max_students'] ?? 0);
                                    $status = $class['status'] ?? 'Active';

                                    if ($status !== 'Active' || $total >= $max) continue;
                                    ?>

                                    <option value="<?= $classId ?>">
                                        <?= htmlspecialchars($class['class_name']) ?>
                                        (<?= $total ?>/<?= $max ?>)
                                    </option>

                                <?php endforeach; ?>

                                <option value="newClass" class="">Create New Class</option>
                            </select>
                        </div>

                        <div class="col-lg-10">
                            <button id="btnNewClass" class="btn btn-primary">Create New Class</button>
                        </div>

                    </div>
                    <!-- CREATE NEW CLASS -->
                    <div class="col-md-6 mb-3">
                        <div class="card class-card p-3 h-100 border-primary">
                            <strong class="text-primary">+ Create New Class</strong>

                            <div class="small text-muted mt-2">
                                Add a new class with schedule and details
                            </div>

                            <!-- EXISTING CLASSES -->

                            <div id="newClassForm" class="mt-3 d-none">
                                <input type="text" name="new_class_name" class="form-control mb-2" placeholder="Class name">

                                <select name="new_day" class="form-select mb-2">
                                    <option>Monday</option>
                                    <option>Tuesday</option>
                                    <option>Wednesday</option>
                                    <option>Thursday</option>
                                    <option>Friday</option>
                                    <option>Saturday</option>
                                    <option>Sunday</option>
                                </select>

                                <input type="time" name="new_start" class="form-control mb-2">
                                <input type="time" name="new_end" class="form-control mb-2">
                            </div>

                        </div>
                    </div>

                </div>

                <button type="button" class="btn btn-secondary prev-step">Back</button>
                <button type="button" class="btn btn-primary next-step">Next</button>

            </div>

            <!-- ================= STEP 3 ================= -->
            <div class="step d-none" id="step3">

                <h3 class="mb-4">Payment Summary</h3>

                <!-- ✅ CLASS LIST -->
                <div id="classSummary" class="mb-4"></div>

                <!-- PAYMENT INPUT -->
                <div class="row g-3">

                    <!-- TOTAL (READONLY) -->
                    <div class="col-md-4">
                        <label>Total Fee</label>
                        <input type="number" id="totalInput" class="form-control" readonly>
                    </div>

                    <!-- DISCOUNT -->
                    <div class="col-md-4">
                        <label>Discount</label>
                        <input type="number" id="discount" name="discount" class="form-control" value="0">
                    </div>

                    <!-- PAID -->
                    <div class="col-md-4">
                        <label>Amount Paid</label>
                        <input type="number" id="paid" name="amount_paid" class="form-control" required>
                    </div>

                </div>

                <!-- RESULT -->
                <div class="mt-4">
                    <strong>Final Total: </strong> <span id="finalTotal">0</span><br>
                    <strong>Balance: </strong> <span id="balance">0</span>
                </div>

                <div class="mt-4">
                    <button type="button" class="btn btn-secondary prev-step">Back</button>
                    <button id="finalSubmit" type="button" class="btn btn-success">Register</button>
                </div>

            </div>

        </div>
    </form>

    <!-- ================= SCRIPT ================= -->
    <script>
        let addressData = {}; // global variable

        // Load JSON data asynchronously
        // async function loadAddressData() {
        //     const response = await fetch("/system-management/data/addressCambodia.json");
        //     addressData = await response.json();
        //     console.log("Address data loaded:", addressData);
        // }

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
                dateFormat: "Y-m-d",
                maxDate: "today",
                allowInput: true,
                altFormat: "d-m-Y",
                monthSelectorType: "dropdown",
                yearSelectorType: "dropdown"
            });
            flatpickr("#dob", {
                dateFormat: "d-m-Y",
                maxDate: "today",
                allowInput: true,
                altFormat: "d-m-Y",
                monthSelectorType: "dropdown",
                yearSelectorType: "dropdown"
            });
        });
    </script>
    <script>
        let currentStep = 1;
        const form = document.getElementById("studentForm");

        // ================= STEP CONTROL =================
        function showStep(step) {
            document.querySelectorAll('.step').forEach(el => el.classList.add('d-none'));
            document.getElementById('step' + step).classList.remove('d-none');

            document.getElementById('step').value = step;

            // ✅ Load summary when entering step 3
            if (step === 3) {
                loadSummary();
            }
        }

        // NEXT
        document.querySelectorAll('.next-step').forEach(btn => {
            btn.onclick = async () => {

                if (!validateStep(currentStep)) return;

                const success = await submitStep(currentStep);
                if (!success) return;

                currentStep++;
                showStep(currentStep);
            };
        });

        // BACK
        document.querySelectorAll('.prev-step').forEach(btn => {
            btn.onclick = () => {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            };
        });

        // ================= VALIDATION =================
        function validateStep(step) {

            if (step == 1) {
                const required = document.querySelectorAll('#step1 [required]');
                for (let el of required) {
                    if (!el.value.trim()) {
                        el.focus();
                        alert("Please fill all required student info");
                        return false;
                    }
                }
            }

            if (step === 2) {
                let selected = Array.from(document.getElementById('classSelect').selectedOptions)
                    .map(o => o.value);

                if (selected.length === 0) {
                    alert("Please select at least one class");
                    return false;
                }
            }

            return true;
        }

        // ================= API SUBMIT =================
        async function submitStep(step) {

            const formData = new FormData(form);
            formData.set("action", "register_student");
            formData.set("step", step);

            try {
                const res = await fetch("register_process.php", {
                    method: "POST",
                    body: formData
                });

                const data = await res.json(); // ✅ ONLY ONCE

                if (!data.success) {
                    alert(data.error || "Something went wrong");
                    return false;
                }

                return true;

            } catch (err) {
                console.error(err);
                alert("Network error");
                return false;
            }
        }

        // ================= LOAD SUMMARY =================
        async function loadSummary() {

            const formData = new FormData(form);
            formData.set("action", "get_summary");

            const res = await fetch("register_process.php", {
                method: "POST",
                body: formData
            });

            const data = await res.json();

            if (!data.success) return;

            let html = "";
            data.classes.forEach(c => {
                html += `
            <div class="border p-2 mb-2">
                <strong>${c.class_name}</strong><br>
                Course: ${c.course_name}<br>
                Time: ${c.start_time} - ${c.end_time}<br>
                Price: $${c.price}
            </div>
        `;
            });

            document.getElementById("classSummary").innerHTML = html;

            // ✅ FIX
            document.getElementById("totalInput").value = data.total;

            calc();
        }

        // ================= PAYMENT CALC =================
        function calc() {

            let total = +document.getElementById('totalInput').value || 0;
            let discount = +document.getElementById('discount').value || 0;
            let paid = +document.getElementById('paid').value || 0;

            let finalTotal = total - discount;
            let balance = finalTotal - paid;

            document.getElementById('finalTotal').innerText = finalTotal;
            document.getElementById('balance').innerText = balance;
        }

        // Auto update
        document.querySelectorAll('#discount, #paid')
            .forEach(el => el.addEventListener('input', calc));

        // ================= FINAL SUBMIT =================
        document.getElementById("finalSubmit").onclick = async () => {

            if (currentStep !== 3) return;

            let paid = document.getElementById('paid').value;

            if (!paid) {
                alert("Please enter payment");
                return;
            }

            const success = await submitStep(3);

            if (success) {
                alert("✅ Student registered successfully!");
                window.location.reload();
            }
        };

        // INIT
        showStep(currentStep);
    </script>

    <script>
        const studentPhoto = document.getElementById("student_photo");
        const staffPhoto = document.getElementById("staff_photo");
        if (studentPhoto) {
            studentPhoto.addEventListener("change", function() {
                const file = this.files[0];
                if (file) {
                    document.getElementById("studentPreviewPhoto").src = URL.createObjectURL(file);
                }
            });
        }
        if (studentPhoto) {
            studentPhoto.addEventListener("change", function() {
                const file = this.files[0];
                if (file) {
                    document.getElementById("staffPreviewPhoto").src = URL.createObjectURL(file);
                }
            });
        }
    </script>

</body>

</html>