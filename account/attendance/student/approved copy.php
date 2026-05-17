<?php

date_default_timezone_set('Asia/Phnom_Penh');

include_once __DIR__ . '/../../../data/dataSchema.php';
include_once __DIR__ . '/../../../config/bootstrap.php';

$userId = checkAuth();

if (!$userId) {
    header("Location: " . BASE_URL . "/auth/signin.php");
    exit;
}

authorizeRole('admin');

/*
|--------------------------------------------------------------------------
| ACTIVE MENU
|--------------------------------------------------------------------------
*/

$staticSchemaData[0]["active"] = false;
$staticSchemaData[4]["active"] = true;
$staticSchemaData[4]['submenu'][1]['active'] = true;

/*
|--------------------------------------------------------------------------
| DATABASE
|--------------------------------------------------------------------------
*/

$db = new DB($conn);
$cache = new Cache();

/*
|--------------------------------------------------------------------------
| ORM
|--------------------------------------------------------------------------
*/

$studentCRUD     = new ORM($db, "tblStudents s");
$classCRUD       = new ORM($db, "tblClasses cl");
$attendanceCRUD  = new ORM($db, "tblAttendances a");
$enrollmentCRUD  = new ORM($db, "tblEnrollments e");

/*
|--------------------------------------------------------------------------
| PAGINATION
|--------------------------------------------------------------------------
*/

$limit  = 10;
$page   = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$classId = isset($_GET['class_id'])
    ? (int) $_GET['class_id']
    : 0;
$page   = max($page, 1);

$offset = ($page - 1) * $limit;

/*
|--------------------------------------------------------------------------
| SEARCH
|--------------------------------------------------------------------------
*/

$search = isset($_GET['search'])
    ? trim($_GET['search'])
    : '';

/*
|--------------------------------------------------------------------------
| CACHE KEY
|--------------------------------------------------------------------------
*/

$cacheKey = "attendance_list_{$search}_{$page}_{$limit}";

/*
|--------------------------------------------------------------------------
| GET ATTENDANCE LIST
|--------------------------------------------------------------------------
*/

$cacheKey = "students_class_" . $classId;

$students = $cache->get($cacheKey, 120);

if ($students === null) {

    $studentsORM = $enrollmentCRUD

        ->join("tblStudents s", "e.student_id = s.student_id")

        ->join("tblClasses cl", "e.class_id = cl.class_id")

        ->select("
            e.enrollment_id,

            s.student_id,
            s.first_name_kh,
            s.last_name_kh,

            cl.class_id,
            cl.class_name,
            cl.class_code
        ");

    if ($classId > 0) {

        $studentsORM->where(
            "cl.class_id",
            "=",
            $classId
        );
    }

    $students = $studentsORM

        ->orderBy("s.student_id", "ASC")

        ->get();

    $cache->set($cacheKey, $students, 120);
}
/*
|--------------------------------------------------------------------------
| COUNT TOTAL
|--------------------------------------------------------------------------
*/

$countORM = new ORM($db, "tblAttendances a");

$countData = $countORM

    ->join("tblEnrollments e", "a.enrollment_id = e.enrollment_id")

    ->join("tblStudents s", "e.student_id = s.student_id")

    ->join("tblClasses cl", "e.class_id = cl.class_id")

    ->join("tblEmployees t", "e.created_by = t.employee_id", "LEFT")

    ->search($search, [
        "s.student_id",
        "s.first_name_kh",
        "s.last_name_kh",
        "cl.class_name",
        "cl.class_code",
        "a.status"
    ])

    ->select("COUNT(*) AS total")
    ->first();



/*
|--------------------------------------------------------------------------
| PAGINATION TOTAL
|--------------------------------------------------------------------------
*/

$totalAttendances = $countData['total'] ?? 0;

$totalPages = ceil($totalAttendances / $limit);

/*
|--------------------------------------------------------------------------
| ACTIVE CLASSES
|--------------------------------------------------------------------------
*/

$activeClasses = $classCRUD

    ->select("
        cl.class_id,
        cl.class_name,
        cl.class_code
    ")

    ->where("cl.status", "=", "Active")

    ->orderBy("cl.class_name", "ASC")

    ->get();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Approved | <?php echo $infoSchemaData[1]["name_short"] ?></title>
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
    <link rel="stylesheet" href="../../../src/style.css">

</head>

<body class="container-fluid p-0 overflow-x-hidden ">
    <div class="row g-3">
        <nav class="navBar col-12 col-md-3 col-lg-2 p-3">
            <div class="d-flex gap-1 mb-4 align-items-center align-self-center position-sticky top-0 bg-white p-0">
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
                                <a class="nav-link rounded d-flex justify-content-between align-items-center <?= !empty($item['active']) ? 'text-dark' : ' text-dark'; ?>"
                                    data-bs-toggle="collapse"
                                    href="#<?= $item['submenu_id']; ?>"
                                    aria-expanded="<?= (!empty($item['active']) ? 'true' : 'false'); ?>">
                                    <?= $item['title']; ?>
                                    <span class=" bi submenu-icon <?= (!empty($item['active']) || !empty(array_filter($item['submenu'], fn($s) => !empty($s['active'])))) ? 'bi-chevron-down' : 'bi-chevron-left'; ?>"></span>
                                </a>
                                <ul id="<?= $item['submenu_id']; ?>"
                                    class="nav collapse flex-column ms-3
<?= (!empty($item['active']) || !empty(array_filter($item['submenu'], fn($s) => !empty($s['active'])))) ? 'show' : ''; ?>">

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
        <main class="col-10 bg-light ">
            <div
                class="d-flex justify-content-between align-items-center px-2 py-2 bg-white py-md-1 position-sticky top-0 ">
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

            <div class="container-lg container-md container-sm p-3 vh-100">

                <div class="w-100 h-100 d-flex mt-3 justify-content-between gap-3 flex-wrap">
                    <div class="w-100 h-100 bg-white shadow px-4 py-3 rounded">

                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div>
                                <h4 class="fw-bold mb-1">
                                    <i class="bi bi-calendar-check me-2"></i>
                                    Submit Attendance
                                </h4>
                                <p class="text-muted mb-0">
                                    Manage daily student attendance
                                </p>
                            </div>

                            <div>
                                <button class="btn btn-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Save Attendance
                                </button>
                            </div>
                        </div>

                        <!-- Filters -->
                        <form method="GET" class="mb-4">
                            <div class="row g-3 mb-4">

                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">
                                        Select Class
                                    </label>

                                    <select name="class_id" class="form-select" id="classSelect">
                                        <option value="">Choose Class</option>

                                        <?php foreach ($activeClasses as $class): ?>
                                            <!-- <option value="<?= $class['class_id']; ?>">
                                            <?= $class['class_name']; ?>
                                            (<?= $class['class_code']; ?>)
                                        </option> -->

                                            <option value="<?= $class['class_id']; ?>" <?= ($classId == $class['class_id']) ? 'selected' : ''; ?>>
                                                <?= $class['class_name']; ?>
                                                (<?= $class['class_code']; ?>)
                                            <?php endforeach; ?>

                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">
                                        Attendance Date
                                    </label>

                                    <input
                                        type="date"
                                        name="attendance_date"
                                        class="form-control"
                                        value="<?= date('Y-m-d'); ?>">
                                </div>



                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="bi bi-search me-1"></i>
                                        Load
                                    </button>
                                </div>

                            </div>
                        </form>

                        <!-- Attendance Table -->
                        <form method="POST">
                            <div class="table-responsive">

                                <table class="table align-middle table-hover">

                                    <thead class="table-light">
                                        <tr>
                                            <th width="80">ID</th>
                                            <th>Student Name</th>
                                            <th>Class</th>
                                            <th width="180">Status</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>

                                    <tbody class="bg-white" id="attendTable">

                                        <?php if ($classId > 0): ?>

                                            <?php foreach ($students as $student): ?>

                                                <tr>

                                                    <td>
                                                        <?= $student['student_id']; ?>
                                                    </td>

                                                    <td>
                                                        <div class="fw-semibold">
                                                            <?= $student['first_name_kh']; ?>
                                                            <?= $student['last_name_kh']; ?>
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <?= $student['class_name']; ?>
                                                    </td>

                                                    <td>

                                                        <select
                                                            name="status[<?= $student['enrollment_id']; ?>]"
                                                            class="form-select">

                                                            <option value="absent">
                                                                ❌ Absent
                                                            </option>

                                                            <option value="present">
                                                                ✅ Present
                                                            </option>

                                                            <option value="late">
                                                                ⏰ Late
                                                            </option>

                                                        </select>

                                                    </td>

                                                    <td>

                                                        <input
                                                            type="text"
                                                            name="remarks[<?= $student['enrollment_id']; ?>]"
                                                            class="form-control"
                                                            placeholder="Optional remarks...">

                                                    </td>

                                                </tr>

                                            <?php endforeach; ?>

                                        <?php else: ?>

                                            <tr class="text-center py-5">

                                                <td colspan="5">

                                                    <div class="alert alert-info mb-0">

                                                        Please select a class and click Load.

                                                    </div>

                                                </td>

                                            </tr>

                                        <?php endif; ?>

                                    </tbody>

                                </table>

                            </div>

                            <!-- Footer Actions -->
                            <div class="d-flex justify-content-end gap-2 mt-4 mb-5">

                                <button class="btn btn-outline-secondary">
                                    Cancel
                                </button>

                                <button type="submit" class="btn btn-success px-4">
                                    <i class="bi bi-save me-1"></i>
                                    Submit Attendance
                                </button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.getElementById("searchStudent").addEventListener("keyup", function() {
            let search = this.value;

            fetch("/System-Management/ajax/search_student.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "search=" + encodeURIComponent(search)
                })
                .then(res => res.text())
                .then(data => {
                    document.getElementById("attendTable").innerHTML = data;

                    selectedId = null;
                    editBtn.disabled = true;
                    detailBtn.disabled = true;
                });
        });
    </script>
    <script>
        let selectedId = null;

        const tableBody = document.getElementById("attendTable");
        const editBtn = document.getElementById("editBtn");
        const detailBtn = document.getElementById("detailBtn");

        tableBody.addEventListener("click", function(e) {
            const row = e.target.closest("tr");
            if (!row) return;
            console.dir(e.target);
            console.dir(e.target.closest);

            // Remove highlight from all rows
            document.querySelectorAll("#attendTable tr")
                .forEach(r => r.classList.remove("table-active"));

            // Highlight clicked row
            row.classList.add("table-active");

            // Store ID
            selectedId = row.dataset.id;

            // Enable buttons
            editBtn.disabled = false;
            detailBtn.disabled = false;

            console.log("Selected ID:", selectedId);
        });

        editBtn.addEventListener("click", () => {
            if (!selectedId) return;
            window.location.href = "edit?type=student&id=" + selectedId;
        });

        detailBtn.addEventListener("click", () => {
            if (!selectedId) return;
            window.location.href = "detail?type=student&id=" + selectedId;
        });
    </script>

    <script>
        document.querySelectorAll('[data-bs-toggle="collapse"]').forEach(function(menu) {

            const icon = menu.querySelector(".submenu-icon");
            const target = document.querySelector(menu.getAttribute("href"));

            if (!icon || !target) return;

            target.addEventListener("show.bs.collapse", function() {
                icon.classList.remove("bi-chevron-left");
                icon.classList.add("bi-chevron-down");
            });

            target.addEventListener("hide.bs.collapse", function() {
                icon.classList.remove("bi-chevron-down");
                icon.classList.add("bi-chevron-left");
            });

        });
    </script>

</body>

</html>