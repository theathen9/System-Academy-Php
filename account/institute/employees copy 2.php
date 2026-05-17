<?php
// ./admin/institute/employees.php

require_once __DIR__ . '/../../data/dataSchema.php';
require_once __DIR__ . '/../../core/ORM.php';



// Set active menu
$staticSchemaData[0]["active"] = false;
$staticSchemaData[1]["active"] = true;
$staticSchemaData[1]['submenu'][0]['active'] = true;

// Pagination & search
$limit = 18;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page  = max($page, 1);
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// DB wrapper
$db = new DB($conn);
$employeeCRUD = new ORM($db, 'employees');

$totalEmployees = $employeeCRUD->count(
    "",
    ["name", "email", "phone"], // searchable columns
    $search
);

// $totalEmployees = countEmployees($conn, $search);
// $totalPages = ceil($totalEmployees / $limit);

// $result = getEmployees($conn, $limit, $offset, $search);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees | <?= htmlspecialchars($infoSchemaData[1]["name_short"]) ?></title>
    <link rel="icon" type="image/png" href="<?= $infoSchemaData[5]["image"] ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="../../src/style.css">
</head>

<body class="container-fluid p-0 overflow-x-hidden">
    <div class="row g-3">
        <!-- Sidebar -->
        <nav class="navBar col-2 p-3">
            <div class="d-flex gap-1 mb-4 align-items-center position-sticky top-0 bg-white p-0">
                <img src="<?= $infoSchemaData[5]["image"] ?>" width="60" height="60" alt="logo" class="rounded-circle">
                <div class="title">
                    <p class="m-auto"><?= htmlspecialchars($infoSchemaData[1]["name_short"]) ?></p>
                </div>
            </div>
            <ul class="nav flex-column">
                <?php foreach ($staticSchemaData as $item): ?>
                    <?php if (isset($item['submenu'])): ?>
                        <li class="nav-item mb-1">
                            <a class="nav-link rounded d-flex justify-content-between align-items-center <?= !empty($item['active']) ? 'text-dark' : 'text-dark'; ?>"
                                data-bs-toggle="collapse"
                                href="#<?= $item['submenu_id']; ?>"
                                aria-expanded="<?= !empty($item['active']) ? 'true' : 'false'; ?>">
                                <?= $item['title']; ?>
                                <span class="bi submenu-icon <?= (!empty($item['active']) || !empty(array_filter($item['submenu'], fn($s) => !empty($s['active'])))) ? 'bi-chevron-down' : 'bi-chevron-left'; ?>"></span>
                            </a>
                            <ul id="<?= $item['submenu_id']; ?>"
                                class="nav collapse flex-column ms-3 <?= (!empty($item['active']) || !empty(array_filter($item['submenu'], fn($s) => !empty($s['active'])))) ? 'show' : ''; ?>">
                                <?php foreach ($item['submenu'] as $sub): ?>
                                    <li class="nav-item mb-1 w-100">
                                        <a href="<?= $sub['link']; ?>" class="nav-link rounded <?= !empty($sub['active']) ? 'bg-primary text-white' : 'text-dark'; ?>">
                                            <?= $sub['title']; ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item mb-1 w-100">
                            <a href="<?= $item['link']; ?>" class="nav-link rounded <?= !empty($item['active']) ? 'bg-primary text-white' : 'text-dark'; ?>">
                                <?php if (!empty($item['icon'])): ?>
                                    <i class="<?= $item['icon']; ?> me-1"></i>
                                <?php endif; ?>
                                <?= $item['title']; ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            </ul>
        </nav>

        <!-- Main content -->
        <main class="col-10 bg-light">
            <div class="d-flex justify-content-between align-items-center px-2 py-2 bg-white position-sticky top-0 z-3">
                <div class="title">Welcome to <?= htmlspecialchars($infoSchemaData[0]["name"]) ?></div>
                <div class="dropdown">
                    <button class="d-flex align-items-center border-0 bg-white gap-2" data-bs-toggle="dropdown">
                        <img src="../src/assets/logo.jpg" width="60" height="60" style="border-radius:50%">
                        <div>Username</div>
                    </button>
                    <ul class="dropdown-menu bg-white">
                        <li><a href="../auth/signout.php" class="dropdown-item">Sign Out</a></li>
                        <li><a href="#" class="dropdown-item">Account</a></li>
                    </ul>
                </div>
            </div>

            <div class="container-lg p-3">
                <div class="w-100 bg-white shadow px-4 py-3 rounded">
                    <div class="d-flex justify-content-between align-items-center fw-semibold mb-2 flex-wrap">
                        <div class="d-flex w-75 align-items-center gap-3 mb-3">
                            <div class="fw-semibold"><i class="bi bi-credit-card-fill me-1"></i>Employees List</div>
                            <form method="GET" class="d-flex w-75" autocomplete="off">
                                <input type="text" name="search" class="form-control me-2" placeholder="Search employee..." value="<?= htmlspecialchars($search) ?>">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </form>
                        </div>
                        <div>
                            <button id="editBtn" class="btn btn-primary" disabled>Edit</button>
                            <button id="detailBtn" class="btn btn-primary" disabled>Detail</button>
                            <button class="btn btn-primary" style="width: 99px;" onclick="window.location.href='../register?type=staff'">Add</button>
                        </div>
                    </div>

                    <!-- Employee Table -->
                    <div style="min-height: 600px;" class="table-scroll modelBox ps-3">
                        <table class="table table-hover mb-0">
                            <thead class="head-custom">
                                <tr class="headLabel">
                                    <th class="col-1">EmpID</th>
                                    <th class="col-2">NameKh</th>
                                    <th class="col-2">NameEng</th>
                                    <th class="col-1">DateOfBirth</th>
                                    <th class="col-1">Gender</th>
                                    <th class="col-3">Email</th>
                                    <th class="col-2">Position</th>
                                    <th class="col-3">Phone</th>
                                </tr>
                            </thead>
                            <tbody id="employeeTable" class="text-lg-start fs-08">
                                <?php if ($result && $result->num_rows > 0): ?>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr data-id="<?= $row['employee_id'] ?>">
                                            <td class=""> <?= !empty($row['employee_id']) ? htmlspecialchars($row['employee_id']) : 'N/A' ?></td>
                                            <td> <?= !empty($row['namekhmer']) ? htmlspecialchars($row['namekhmer']) : 'N/A' ?></td>
                                            <td> <?= !empty($row['nameenglish']) ? htmlspecialchars($row['nameenglish']) : 'N/A' ?></td>
                                            <td> <?= !empty($row['dob']) ? htmlspecialchars($row['dob']) : 'N/A' ?></td>
                                            <td> <?= !empty($row['gender']) ? htmlspecialchars($row['gender']) : 'N/A' ?></td>
                                            <td> <?= !empty($row['email']) ? htmlspecialchars($row['email']) : 'N/A' ?></td>
                                            <td> <?= !empty($row['department_name']) ? htmlspecialchars($row['department_name']) : 'N/A' ?></td>
                                            <td>
                                                <?=
                                                !empty($row['phone1'])
                                                    ? htmlspecialchars($row['phone1']) .
                                                    (!empty($row['phone2']) ? ' ' . htmlspecialchars($row['phone2']) : '')
                                                    : 'N/A'
                                                ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted">No records found</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center align-items-center mt-3 gap-2">
                        <form method="GET" class="d-flex gap-2">
                            <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                            <?php if ($page > 1): ?>
                                <button type="submit" name="page" value="<?= $page - 1 ?>" class="btn btn-outline-primary">⬅ Prev</button>
                            <?php endif; ?>
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <button type="submit" name="page" value="<?= $i ?>" class="btn <?= $i == $page ? 'btn-primary' : 'btn-outline-primary' ?>"><?= $i ?></button>
                            <?php endfor; ?>
                            <?php if ($page < $totalPages): ?>
                                <button type="submit" name="page" value="<?= $page + 1 ?>" class="btn btn-outline-primary">Next ➡</button>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        let selectedId = null;
        const tableBody = document.getElementById("employeeTable");
        const editBtn = document.getElementById("editBtn");
        const detailBtn = document.getElementById("detailBtn");

        tableBody.addEventListener("click", function(e) {
            const row = e.target.closest("tr");
            if (!row) return;
            document.querySelectorAll("#employeeTable tr").forEach(r => r.classList.remove("table-active"));
            row.classList.add("table-active");
            selectedId = row.dataset.id;
            editBtn.disabled = false;
            detailBtn.disabled = false;
        });

        editBtn.addEventListener("click", () => {
            if (!selectedId) return;
            window.location.href = "edit?type=employee&id=" + selectedId;
        });

        detailBtn.addEventListener("click", () => {
            if (!selectedId) return;
            window.location.href = "detail?type=employee&id=" + selectedId;
        });

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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>