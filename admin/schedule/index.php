<?php
//./admin/schedule/dashboard.php

// require_once( __DIR__ . "/../../config/db.php");
include_once __DIR__ . '/../../config/db.php';
include_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../data/dataSchema.php';
require_once __DIR__ . '/../../components/Navbar.php';
require_once __DIR__ . '/../../components/Avatar.php';


// include_once "/config/db.php";
$routeAdmin[0]["active"] = false;
$routeAdmin[6]["active"] = true;
$routeAdmin[6]['submenu'][0]['active'] = true;

$userId = checkAuth();

if (!$userId) {
    header("Location: ../auth/signin.php");
    exit;
}

authorizeRole('admin');

$limit = 18;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page  = max($page, 1);
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';




$db = new DB($conn);
$sheduleCURD = new ORM($db, 'tblTimeTables tt', 'timetable_id');
// $sheduleCURD = new ORM($db, 'tblClasses c', 'class_id');
$cache = new Cache();

$cacheKey = "schedules_list_{$search}_{$page}_{$limit}";

// $data = $cache->get($cacheKey, 120);
$data = null;

if ($data === null) {
    $data = $sheduleCURD
        ->select("
        c.class_id,
        c.class_name,
        c.class_code,
        c.status,

        GROUP_CONCAT(d.day_name ORDER BY d.sort_order SEPARATOR ',') AS days,

        s.subject_name,
        CONCAT(emp.first_name_en, ' ', emp.last_name_en) AS teacher_name,
        r.room_name,
        ts.start_time,
        ts.end_time
    ")
        ->join("tblClasses c", "c.class_id = tt.class_id")
        ->join("tblDays d", "d.day_id = tt.day_id")
        ->join("tblCourseSubjects cs", "cs.id = c.course_subject_id")
        ->join("tblSubjects s", "s.subject_id = cs.subject_id")
        ->join("tblEmployees emp", "emp.employee_id = c.teacher_id")
        ->join("tblRooms r", "r.room_id = c.room_id")
        ->join("tblTimeSlots ts", "ts.slot_id = c.slot_id")

        ->groupBy("c.class_id, c.class_name, c.class_code , c.status, s.subject_name, emp.first_name_en, emp.last_name_en, r.room_name, ts.start_time, ts.end_time")
        ->get();
    // $cache->set($cacheKey, $data, 120);
}


$grouped = [];

foreach ($data as $row) {
    $id = $row['class_id'];

    if (!isset($grouped[$id])) {
        $grouped[$id] = [
            'class_id' => $row['class_id'],
            'class_name' => $row['class_name'],
            'class_code' => $row['class_code'],
            'status' => $row['status'],
            'subject_name' => $row['subject_name'],
            'teacher_name' => $row['teacher_name'],
            'room_name' => $row['room_name'],
            'start_time' => $row['start_time'],
            'end_time' => $row['end_time'],
            'days' => $row['days'] // ✅ IMPORTANT FIX
        ];
    }

    // ✅ THIS IS THE FIX
    // $grouped[$id]['days'][] = $row['days'];
}


function formatDays($days)
{
    if (empty($days)) return '';

    // Convert string → array
    $days = array_map('trim', explode(',', $days));

    // Week order map
    $map = [
        'Mon' => 1,
        'Tue' => 2,
        'Wed' => 3,
        'Thu' => 4,
        'Fri' => 5,
        'Sat' => 6,
        'Sun' => 7
    ];

    $reverse = array_flip($map);

    // Convert to numbers
    $indexes = [];
    foreach ($days as $d) {
        // normalize full names too (optional safety)
        $short = substr($d, 0, 3);
        if (isset($map[$short])) {
            $indexes[] = $map[$short];
        }
    }

    if (empty($indexes)) return '';

    sort($indexes);
    $indexes = array_values(array_unique($indexes));

    // 🔥 Full Mon–Fri shortcut
    if ($indexes === [1, 2, 3, 4, 5]) {
        return "Mon - Fri";
    }

    // 🔥 Single continuous range detection
    $start = $indexes[0];
    $end = end($indexes);

    // If consecutive range (like Mon–Tue or Mon–Wed)
    $isConsecutive = true;
    for ($i = 1; $i < count($indexes); $i++) {
        if ($indexes[$i] !== $indexes[$i - 1] + 1) {
            $isConsecutive = false;
            break;
        }
    }

    if ($isConsecutive) {
        return $reverse[$start] . " - " . $reverse[$end];
    }

    // fallback: list format
    return implode(', ', array_map(fn($i) => $reverse[$i], $indexes));
}

$countCURD = new ORM($db, 'tblTimetables tt', 'timetable_id');

$countData = $countCURD
    ->select("COUNT(*) as total")
    ->join("tblClasses c", "c.class_id = tt.class_id")
    ->join("tblCourseSubjects cs", "cs.id = c.course_subject_id")
    ->join("tblSubjects s", "s.subject_id = cs.subject_id")
    ->join("tblEmployees emp", "emp.employee_id = c.teacher_id")
    ->join("tblRooms r", "r.room_id = c.room_id")

    // IMPORTANT FIX HERE
    ->join("tblTimeSlots ts", "ts.slot_id = c.slot_id")

    ->search($search, [
        "c.class_name",
        "c.class_code",
        "s.subject_name",
        "emp.first_name_en",
        "emp.last_name_en",
        "r.room_name"
    ])
    ->first();

$totalTeachers = $countData['total'] ?? 0;
$totalPages = ceil($totalTeachers / $limit);




?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Schedules | <?php echo $infoSchemaData[1]["name_short"] ?></title>
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
    <link rel="stylesheet" href="../../src/style.css">
    <script src="/system-management/src/assets/js/user-profile.js"></script>
    <style>
        .page-title {
            font-weight: 700;
        }
    </style>

</head>

<body class="container-fluid p-0 overflow-x-hidden">
    <div class="row g-3">

        <?php Navbar($infoSchemaData, $routeAdmin); ?>

        <!-- Main area -->
        <main class=" col-lg-10 col-sm-12 bg-light">
            <div
                class="d-flex justify-content-between align-items-center px-2 py-2 bg-white py-md-1 position-sticky top-0 z-3">
                <div class="title">Welcome to <?php echo $infoSchemaData[0]["name"] ?></div>

                <?php Avatar($_SESSION['role']); ?>

            </div>

            <div class="container-lg container-md container-sm p-3">
                <div hidden style="background: #006d9c;
background: linear-gradient(139deg, rgba(0, 109, 156, 1) 32%, rgba(0, 109, 156, 1) 50%, rgba(101, 155, 90, 1) 61%, rgba(128, 164, 74, 1) 63%, rgba(154, 173, 59, 1) 66%, rgba(255, 208, 0, 1) 70%, rgba(255, 208, 0, 1) 100%);"
                    class="w-100 bg-gradient-custom py-3 px-4 rounded">
                    <div class="d-flex justify-content-between text-white">
                        <div class="">
                            <h3 class="mb-4"><?php echo $infoSchemaData[0]["name"] ?></h3>
                            <div>
                                <div>
                                    <i
                                        class="bi bi-envelope-fill me-1 mb-0"></i><?php echo $infoSchemaData[1]["email"] ?>
                                </div>
                                <div>
                                    <i
                                        class="bi bi-geo-alt-fill me-1 mb-0"></i><?php echo $infoSchemaData[2]["address"] ?>
                                </div>
                                <div>
                                    <i
                                        class="bi bi-telephone-fill me-1 mb-0"></i><?php echo $infoSchemaData[3]["phone"] ?>
                                </div>
                            </div>
                        </div>
                        <img style="width: 135px; border-radius: 50%;" src="<?php echo $infoSchemaData[4]["image"] ?>"
                            alt="" srcset="" class="h-100">
                    </div>
                </div>

                <div class="w-100 d-flex mt-3 justify-content-between gap-3 flex-wrap">
                    <div class="w-100 bg-white shadow px-4 py-3 rounded">
                        <div class="d-flex justify-content-between align-items-center fw-semibold mb-2">
                            <div class="d-flex align-items-center justify-content-between gap-3 mb-3">
                                <div class="col-md-4 fw-semibold">
                                    <i class="bi bi-credit-card-fill me-1"></i>
                                    Schedule
                                </div>

                                <div class="col-md-4">
                                    <select name="status" class="form-select" onchange="this.form.submit()">
                                        <option value="">All Status</option>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Cancelled">Cancelled</option>
                                    </select>
                                </div>

                                <div class="col-md-9">
                                    <input type="text"
                                        id="searchStudent"
                                        class="form-control"
                                        placeholder="Search Student...">
                                </div>

                            </div>
                            <div>
                                <button id="editBtn" style="width: 99px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editScheduleModal">
                                    Edit
                                </button>
                                <button id="deleteBtn" style="width: 99px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#deleteScheduleModal">
                                    Delete
                                </button>

                                <button style="width: 99px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scheduleModal">
                                    Add
                                </button>

                            </div>

                            <div class="modal fade" id="editScheduleModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content shadow">

                                        <form id="scheduleEditForm" method="POST">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="class_id" id="edit_class_id">

                                            <!-- Header -->
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-pencil-square me-2"></i> Edit Schedule
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>

                                            <!-- Body -->
                                            <div class="modal-body">

                                                <div class="row g-3">

                                                    <!-- Class Name -->
                                                    <div class="col-md-6">
                                                        <label class="form-label">Class Name</label>
                                                        <input type="text" class="form-control" id="edit_class_name" readonly>
                                                    </div>

                                                    <!-- Class Code -->
                                                    <div class="col-md-6">
                                                        <label class="form-label">Class Code</label>
                                                        <input type="text" class="form-control" id="edit_class_code" readonly>
                                                    </div>

                                                    <!-- Subject -->
                                                    <div class="col-md-6">
                                                        <label class="form-label">Subject</label>
                                                        <input type="text" class="form-control" id="edit_subject_name" readonly>
                                                    </div>

                                                    <!-- Teacher -->
                                                    <div class="col-md-6">
                                                        <label class="form-label">Teacher</label>
                                                        <input type="text" class="form-control" id="edit_teacher_name" readonly>
                                                    </div>

                                                    <!-- Room -->
                                                    <div class="col-md-6">
                                                        <label class="form-label">Room</label>
                                                        <input type="text" class="form-control" id="edit_room_name" readonly>
                                                    </div>

                                                    <!-- Status -->
                                                    <div class="col-md-6">
                                                        <label class="form-label">Status</label>
                                                        <select class="form-select" name="status" id="edit_status" required>
                                                            <option value="Active">Active</option>
                                                            <option value="Inactive">Inactive</option>
                                                            <option value="Completed">Completed</option>
                                                            <option value="Cancelled">Cancelled</option>
                                                        </select>
                                                    </div>

                                                </div>

                                            </div>

                                            <!-- Footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="btn btn-success">
                                                    Save Changes
                                                </button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>

                            <!-- Delete -->
                            <div class=" modal fade" id="deleteScheduleModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class=" modal-content px-3 ">
                                        <form id="scheduleForm" method="POST">
                                            <input type="hidden" name="schedule_id" id="delete_schedule_id">
                                            <input type="hidden" name="action" value="delete">

                                            <div class="modal-header">
                                                <h5 class="modal-title">Delete Schedule</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div id="deleteText">

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="btn btn-success">
                                                    Delete
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>



                            <!-- Add Schedule -->
                            <div class="modal fade" id="scheduleModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content shadow">

                                        <form id="scheduleForm" method="POST">
                                            <input type="hidden" name="action" value="add">

                                            <!-- Header -->
                                            <div class="modal-header bg-primary text-white">
                                                <h5 class="modal-title">
                                                    <i class="bi bi-calendar-plus me-2"></i> Add Schedule
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>

                                            <!-- Body -->
                                            <div class="modal-body">

                                                <div class="row g-3">

                                                    <!-- Class -->
                                                    <div class="col-md-6">
                                                        <label class="form-label">Class</label>
                                                        <select class="form-select" name="class_id" required>
                                                            <option value="">Select Class</option>
                                                            <!-- PHP loop here -->
                                                        </select>
                                                    </div>

                                                    <!-- Subject -->
                                                    <div class="col-md-6">
                                                        <label class="form-label">Subject</label>
                                                        <input type="text" class="form-control" name="subject_name" readonly>
                                                    </div>

                                                    <!-- Teacher -->
                                                    <div class="col-md-6">
                                                        <label class="form-label">Teacher</label>
                                                        <input type="text" class="form-control" name="teacher_name" readonly>
                                                    </div>

                                                    <!-- Room -->
                                                    <div class="col-md-6">
                                                        <label class="form-label">Room</label>
                                                        <input type="text" class="form-control" name="room_name" readonly>
                                                    </div>

                                                    <!-- Day -->
                                                    <div class="col-md-6">
                                                        <label class="form-label">Days</label>
                                                        <select class="form-select" name="days[]" multiple required>
                                                            <option value="Mon">Monday</option>
                                                            <option value="Tue">Tuesday</option>
                                                            <option value="Wed">Wednesday</option>
                                                            <option value="Thu">Thursday</option>
                                                            <option value="Fri">Friday</option>
                                                            <option value="Sat">Saturday</option>
                                                            <option value="Sun">Sunday</option>
                                                        </select>
                                                    </div>

                                                    <!-- Time Slot -->
                                                    <div class="col-md-6">
                                                        <label class="form-label">Time Slot</label>
                                                        <select class="form-select" name="slot_id" required>
                                                            <option value="">Select Time</option>
                                                            <!-- PHP loop -->
                                                        </select>
                                                    </div>

                                                    <!-- Status -->
                                                    <div class="col-md-6">
                                                        <label class="form-label">Status</label>
                                                        <select class="form-select" name="status">
                                                            <option value="Active">Active</option>
                                                            <option value="Inactive">Inactive</option>
                                                            <option value="Completed">Completed</option>
                                                            <option value="Cancelled">Cancelled</option>
                                                        </select>
                                                    </div>

                                                </div>

                                            </div>

                                            <!-- Footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="btn btn-success">
                                                    Save Schedule
                                                </button>
                                            </div>

                                        </form>

                                    </div>
                                </div>
                            </div>

                            <!-- nav student lable -->
                        </div>

                        <!-- SCROLL CONTAINER -->
                        <div style="min-height: 600px;" class="table-scroll modelBox ps-3">
                            <table class="table table-hover">

                                <thead class="head-custom">

                                    <tr>
                                        <th>ID</th>
                                        <th>Class</th>
                                        <th>Subject</th>
                                        <th class="col-md-2">Teacher</th>
                                        <th>Room</th>
                                        <th>Day</th>
                                        <th>Time</th>
                                        <th style="width: 150px;">Status</th>
                                    </tr>

                                </thead>

                                <tbody id="scheduleTable">

                                    <?php if (!empty($grouped)): ?>

                                        <?php foreach ($grouped as $key => $row): ?>
                                            <tr data-id="<?= $row['class_id'] ?>"
                                                data-name="<?= htmlspecialchars($row['class_name']) ?>"
                                                data-code="<?= htmlspecialchars($row['class_code']) ?>"
                                                data-status="<?= htmlspecialchars($row['status']) ?>">

                                                <td class="text-center"><?= $key + 1 ?></td>

                                                <td>
                                                    <?= htmlspecialchars($row['class_name']) ?>
                                                    <br>
                                                    <small class="text-muted">
                                                        <?= htmlspecialchars($row['class_code']) ?>
                                                    </small>
                                                </td>

                                                <td><?= htmlspecialchars($row['subject_name']) ?></td>

                                                <td><?= htmlspecialchars($row['teacher_name']) ?></td>

                                                <td><?= htmlspecialchars($row['room_name']) ?></td>

                                                <td>
                                                    <span class="badge bg-primary">
                                                        <?= formatDays($row['days']) ?>
                                                    </span>
                                                </td>

                                                <td>
                                                    <?= date('g:i A', strtotime($row['start_time'])) ?>
                                                    -
                                                    <?= date('g:i A', strtotime($row['end_time'])) ?>
                                                </td>


                                                <?php
                                                $statusClasses = [
                                                    'Active'    => 'success',
                                                    'Inactive'  => 'secondary',
                                                    'Completed' => 'primary',
                                                    'Cancelled' => 'danger'
                                                ];

                                                $badgeClass = $statusClasses[$row['status']] ?? 'dark';
                                                ?>
                                                <td>
                                                    <select
                                                        class="form-select  form-select status-select"
                                                        data-id="<?= $row['class_id'] ?>">

                                                        <option value="Active"
                                                            <?= $row['status'] == 'Active' ? 'selected' : '' ?>>
                                                            Active
                                                        </option>

                                                        <option value="Inactive"
                                                            <?= $row['status'] == 'Inactive' ? 'selected' : '' ?>>
                                                            Inactive
                                                        </option>

                                                        <option value="Completed"
                                                            <?= $row['status'] == 'Completed' ? 'selected' : '' ?>>
                                                            Completed
                                                        </option>

                                                        <option value="Cancelled"
                                                            <?= $row['status'] == 'Cancelled' ? 'selected' : '' ?>>
                                                            Cancelled
                                                        </option>
                                                    </select>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>

                                    <?php else: ?>

                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                No schedule found
                                            </td>
                                        </tr>

                                    <?php endif; ?>

                                </tbody>

                            </table>
                        </div>
                        <div class="d-flex justify-content-center align-items-center mt-3 gap-2">
                            <form method="GET" class="d-flex gap-2">

                                <!-- Preserve search -->
                                <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">

                                <!-- Previous -->
                                <?php if ($page > 1): ?>
                                    <button type="submit" name="page" value="<?= $page - 1 ?>" class="btn btn-outline-primary">
                                        ⬅ Prev
                                    </button>
                                <?php endif; ?>

                                <!-- Page numbers -->
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <button type="submit" name="page" value="<?= $i ?>"
                                        class="btn <?= $i == $page ? 'btn-primary' : 'btn-outline-primary' ?>">
                                        <?= $i ?>
                                    </button>
                                <?php endfor; ?>

                                <!-- Next -->
                                <?php if ($page < $totalPages): ?>
                                    <button type="submit" name="page" value="<?= $page + 1 ?>" class="btn btn-outline-primary">
                                        Next ➡
                                    </button>
                                <?php endif; ?>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="<?= BASE_URL ?>/src/assets/js/navbar-toggle-action.js"></script>

    <script>
        document.querySelectorAll(".status-select").forEach(select => {

            select.addEventListener("change", function() {

                const classId = this.dataset.id;
                const status = this.value;

                fetch("<?= BASE_URL ?>/api/v1/update_status.php", {
                        credentials: "include",
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: new URLSearchParams({
                            class_id: classId,
                            status: status
                        })
                    })
                    .then(response => response.json())
                    .then(data => {

                        if (data.success) {
                            console.log("Status updated successfully");
                        } else {
                            console.error("Failed to update status");
                        }

                    })
                    .catch(error => {
                        console.error(error);
                        alert("Server error");
                    });

            });

        });


        let selectedRow = null;
        let selectedId = null;

        // عناصر
        const tableBody = document.getElementById("scheduleTable");
        const editBtn = document.getElementById("editBtn");
        const deleteBtn = document.getElementById("deleteBtn");
        // const statusBtn = document.getElementById("statusBtn");

        // modal text containers
        const deleteText = document.getElementById("deleteText");
        // const statusText = document.getElementById("statusText");

        // default state
        editBtn.disabled = true;
        deleteBtn.disabled = true;
        // statusBtn.disabled = true;


        // ================= ROW SELECT =================
        tableBody.addEventListener("click", function(e) {
            const row = e.target.closest("tr");
            if (!row) return;

            // remove highlight
            document.querySelectorAll("#scheduleTable tr")
                .forEach(r => r.classList.remove("table-active"));

            // highlight selected
            row.classList.add("table-active");

            // store data
            selectedRow = row;
            selectedId = row.dataset.id;

            // enable buttons
            editBtn.disabled = false;
            deleteBtn.disabled = false;
            // statusBtn.disabled = false;

            console.log("Selected ID:", selectedId);
        });


        // ================= EDIT =================
        editBtn.addEventListener("click", () => {
            if (!selectedRow) return;

            document.getElementById("edit_schedule_id").value = selectedRow.dataset.id;
            document.getElementById("edit_schedule_code").value = selectedRow.dataset.code;
            document.getElementById("edit_schedule_name").value = selectedRow.dataset.name;
            document.getElementById("edit_description").value = selectedRow.dataset.description;

            // ❌ REMOVE THIS if input doesn't exist
            // document.getElementById("edit_created_at").value = selectedRow.dataset.created;
        });


        // ================= DELETE =================
        deleteBtn.addEventListener("click", () => {
            if (!selectedRow) return;

            document.getElementById("delete_schedule_id").value = selectedRow.dataset.id;

            deleteText.innerHTML =
                `Are you sure you want to <strong class='text-danger'>Delete</strong> 
         <strong>${selectedRow.dataset.name}</strong> schedule?`;
        });
    </script>



</body>

</html>