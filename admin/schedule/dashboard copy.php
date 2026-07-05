<?php
//./admin/schedule/dashboard.php

// require_once( __DIR__ . "/../../config/db.php");
include_once __DIR__ . '/../../config/db.php';
include_once __DIR__ . '/../../config/bootstrap.php';
require_once __DIR__ . '/../../data/dataSchema.php';
require_once __DIR__ . '/../../components/Navbar.php';


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
$sheduleCURD = new ORM($db, 'tblTimeTables t', 'timetable_id');
// $sheduleCURD = new ORM($db, 'tblClasses c', 'class_id');
$cache = new Cache();

$cacheKey = "schedules_list_{$search}_{$page}_{$limit}";

$data = $cache->get($cacheKey, 120);

if ($data === null) {
    $data = $sheduleCURD
        ->select("
            c.class_id,
            c.class_name,
            c.class_code,
            c.status,

            s.subject_name,

            CONCAT(
                emp.first_name_en,
                ' ',
                emp.last_name_en
            ) AS teacher_name,

            r.room_name,

            'Mon - Fri' AS days,

            ts.start_time,
            ts.end_time
        ")
        ->join("tblCourseSubjects cs", "cs.id = c.course_subject_id")
        ->join("tblSubjects s", "s.subject_id = cs.subject_id")
        ->join("tblEmployees emp", "emp.employee_id = c.teacher_id")
        ->join("tblRooms r", "r.room_id = c.room_id")
        ->join("tblTimeSlots ts", "ts.slot_id = c.slot_id")
        ->search($search, [
            "c.class_name",
            "c.class_code",
            "s.subject_name",
            "emp.first_name_en",
            "emp.last_name_en",
            "r.room_name"
        ])
        ->limit($limit, $offset)
        ->get();

    $cache->set($cacheKey, $data, 120);
}

$countCURD = new ORM($db, 'tblClasses c', 'class_id');

$countData = $countCURD
    ->select("COUNT(*) as total")
    ->join("tblCourseSubjects cs", "cs.id = c.course_subject_id")
    ->join("tblSubjects s", "s.subject_id = cs.subject_id")
    ->join("tblEmployees emp", "emp.employee_id = c.teacher_id")
    ->join("tblRooms r", "r.room_id = c.room_id")
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
    <title>Teacher | <?php echo $infoSchemaData[1]["name_short"] ?></title>
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

</head>

<body class="container-fluid p-0 overflow-x-hidden">
    <div class="row g-3">

        <?php Navbar($infoSchemaData, $routeAdmin); ?>

        <!-- Main area -->
        <main class=" col-lg-10 col-sm-12 bg-light">
            <div
                class="d-flex justify-content-between align-items-center px-2 py-2 bg-white py-md-1 position-sticky top-0 z-3">
                <div class="title">Welcome to <?php echo $infoSchemaData[0]["name"] ?></div>

                <div class="dropdown">
                    <button id="account" class="d-flex align-items-center border-0 bg-white gap-2" data-bs-toggle="dropdown">
                        <img id="profileImg" width="60" height="60" style="border-radius:50%">
                        <div id="username"></div>
                    </button>

                    <ul class="dropdown-menu bg-white">
                        <a href="../auth/signout.php" class="text-decoration-none">
                            <li><button class="dropdown-item">Sign Out</button></li>
                            <li><button class="dropdown-item">Account</button></li>
                        </a>
                    </ul>
                </div>
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
                                    <select name="status" class="form-select">
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
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class="modal-content px-3">
                                        <form id="departmentForm" method="POST">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="department_id" id="edit_department_id">


                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Schedule</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Department Code</label>
                                                    <input type="text" class="form-control" name="department_code" id="edit_department_code" require>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Department Name</label>
                                                    <input type="text" class="form-control" name="department_name" id="edit_department_name" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Description</label>
                                                    <input type="text" class="form-control" name="description" id="edit_description" require>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">date update</label>
                                                    <input type="date" class="form-control" name="updated_at" id="edit_created_at" required>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="btn btn-success">
                                                    Save
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



                            <!--  -->
                            <div class="modal fade" id="scheduleModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class="modal-content px-3">
                                        <form id="scheduleForm" method="POST">
                                            <input type="hidden" name="action" value="add">

                                            <div class="modal-header">
                                                <h5 class="modal-title">Add Schedule</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Department Code</label>
                                                    <input type="text" class="form-control" name="department_code" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Department Name</label>
                                                    <input type="text" class="form-control" name="department_name" required>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Description</label>
                                                    <input type="text" class="form-control" name="description">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Create At</label>
                                                    <input type="date" class="form-control" name="created_at" required>
                                                </div>
                                            </div>

                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="btn btn-success">
                                                    Save
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

                                    <?php if (!empty($data)): ?>

                                        <?php foreach ($data as $key => $row): ?>
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
                                                        <?= htmlspecialchars($row['days']) ?>
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

    <script>
        fetch("http://localhost/system-management/api/v1/users.php", {
                credentials: "include"
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.querySelector("#username").innerText = data.data.username;

                    const profileImg = data.data.profile_image ?
                        "/system-management/uploads/photos/" + data.data.profile_image :
                        "/system-management/src/assets/default-user.png";

                    document.querySelector("#profileImg").src = profileImg;
                } else {
                    console.log("Failed:", data);
                }
            });


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


        // ================= STATUS =================
        // statusBtn.addEventListener("click", () => {
        //     if (!selectedRow) return;

        //     document.getElementById("status_schedule_id").value =
        //         selectedRow.dataset.id;

        //     const currentStatus = selectedRow
        //         .querySelector(".status-select")
        //         .value
        //         .trim()
        //         .toLowerCase();

        // });
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