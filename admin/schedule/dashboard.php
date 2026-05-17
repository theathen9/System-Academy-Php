<?php
//./admin/institute/student.php
// require_once( __DIR__ . "/../../config/db.php");
include_once __DIR__ . '/../../config/db.php';
include_once __DIR__ . '/../../data/dataSchema.php';
include_once __DIR__ . '/../../data/dbSchemaData.php';
include_once __DIR__ . '/../components/Navbar.php';


// include_once "/config/db.php";
$routeAdmin[0]["active"] = false;
$routeAdmin[6]["active"] = true;
$routeAdmin[6]['submenu'][0]['active'] = true;

// Get student
// $getstudents;
// $result = getstudents($conn);

$limit = 18;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page  = max($page, 1);
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$totalStudent = countStudent($conn, $search);
$totalPages = ceil($totalStudent / $limit);

$result = getStudents($conn, $limit, $offset, $search);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Empowerment Education English One</title>
    <link rel="icon" type="image/png" href="<?php echo $infoSchemaData[4]["image"] ?>">
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
                            <div class="d-flex w-50 align-items-center justify-content-between gap-3 mb-3">
                                <div class="fw-semibold">
                                    <i class="bi bi-credit-card-fill me-1"></i>
                                    Schedule
                                </div>

                                <div class="w-75">
                                    <input type="text"
                                        id="searchStudent"
                                        class="form-control"
                                        placeholder="Search Student...">
                                </div>

                            </div>
                            <div>
                                <button id="editBtn" style="width: 99px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editDepartmentModal">
                                    Edit
                                </button>
                                <button id="deleteBtn" style="width: 99px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#deleteDepartmentModal">
                                    Delete
                                </button>
                                <button id="statusBtn" style="width: 99px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#statusDepartmentModal">
                                    Status
                                </button>
                                <button style="width: 99px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#departmentModal">
                                    Add
                                </button>
                            </div>

                            <div class="modal fade" id="editDepartmentModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class="modal-content px-3">
                                        <form id="departmentForm" method="POST">
                                            <input type="hidden" name="action" value="update">
                                            <input type="hidden" name="department_id" id="edit_department_id">


                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Department</h5>
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

                            <!-- update status -->
                            <div class=" modal fade" id="statusDepartmentModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class=" modal-content px-3 ">
                                        <form id="departmentForm" method="POST">
                                            <input type="hidden" name="department_id" id="status_department_id">
                                            <input type="hidden" name="action" value="status">

                                            <div class="modal-header">
                                                <h5 class="modal-title">status Department</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div id="statusText">

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">
                                                    Cancel
                                                </button>
                                                <button type="submit" class="btn btn-success">
                                                    Submit
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete -->
                            <div class=" modal fade" id="deleteDepartmentModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class=" modal-content px-3 ">
                                        <form id="departmentForm" method="POST">
                                            <input type="hidden" name="department_id" id="delete_department_id">
                                            <input type="hidden" name="action" value="delete">

                                            <div class="modal-header">
                                                <h5 class="modal-title">Delete Department</h5>
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
                            <div class="modal fade" id="departmentModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-xl">
                                    <div class="modal-content px-3">
                                        <form id="departmentForm" method="POST">
                                            <input type="hidden" name="action" value="add">

                                            <div class="modal-header">
                                                <h5 class="modal-title">Add Department</h5>
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
                                        <th>Teacher</th>
                                        <th>Room</th>
                                        <th>Day</th>
                                        <th>Time</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php if ($result && $result->num_rows > 0): ?>

                                        <?php while ($row = $result->fetch_assoc()): ?>

                                            <tr>

                                                <td><?= $row['timetable_id'] ?></td>

                                                <td><?= htmlspecialchars($row['class_name']) ?></td>

                                                <td><?= htmlspecialchars($row['subject_name']) ?></td>

                                                <td><?= htmlspecialchars($row['teacher']) ?></td>

                                                <td><?= htmlspecialchars($row['room_name']) ?></td>

                                                <td><?= htmlspecialchars($row['day_of_week']) ?></td>

                                                <td><?= $row['start_time'] ?> - <?= $row['end_time'] ?></td>

                                            </tr>

                                        <?php endwhile; ?>

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
        const statusBtn = document.getElementById("statusBtn");

        statusBtn.disabled = true;

        tableBody.addEventListener("click", function(e) {
            const row = e.target.closest("tr");
            if (!row) return;

            document.querySelectorAll("#departmentTable tr")
                .forEach(r => r.classList.remove("table-active"));

            row.classList.add("table-active");

            selectedRow = row;
            selectedId = row.dataset.id;

            editBtn.disabled = false;
            detailBtn.disabled = false;
            statusBtn.disabled = false;
        });

        statusBtn.addEventListener("click", () => {
            if (!selectedId) return;

            document.getElementById("status_department_id").value = selectedRow.dataset.id;

            const currentStatusCell = selectedRow.querySelector("td:last-child").innerText.trim();
            const statusText = document.getElementById("statusText");

            if (currentStatusCell === "Active") {
                statusText.innerHTML = "Are you sure you want to <strong class='text-danger'>Deactivate</strong> this department?";
            } else {
                statusText.innerHTML = "Are you sure you want to <strong class='text-success'>Activate</strong> this department?";
            }
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