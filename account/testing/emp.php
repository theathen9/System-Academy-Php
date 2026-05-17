<?php
//./admin/institute/employees.php
// require_once( __DIR__ . "/../../config/db.php");
include_once __DIR__ . '/../../config/db.php';
include_once __DIR__ . '/../../data/dbShemaData.php';

include "../../data/dataShema.php";
// include_once "/config/db.php";
$staticShemaData[0]["active"] = false;
$staticShemaData[1]["active"] = true;
$staticShemaData[1]['submenu'][0]['active'] = true;


// Get Employees
$getEmployees;
// $result = getEmployees($conn);

$limit = 18;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page  = max($page, 1);
$offset = ($page - 1) * $limit;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$totalEmployees = countEmployees($conn, $search);
$totalPages = ceil($totalEmployees / $limit);

$result = getEmployees($conn, $limit, $offset, $search);



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Empowerment Education English One</title>
    <link rel="icon" type="image/png" href="<?php echo $infoShemaData[4]["image"] ?>">
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
    <link rel="stylesheet" href="../../src/style.css">

</head>

<body class="container-fluid p-0 overflow-x-hidden">
    <div class="row g-3">
        <nav class="navBar col-2 p-3">
            <div class="d-flex gap-1 mb-4 align-items-center align-self-center position-sticky top-0 bg-white p-0">
                <img src="<?php echo $infoShemaData[4]["image"] ?>" width="60" height="60" alt="logo" class="rounded-circle">
                <div class="title">
                    <p class="m-auto">Empowerment <br>Education English One</p>
                </div>
            </div>
            <ul class="nav flex-column">
                <?php foreach ($staticShemaData as $item): ?>
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
        </nav>

        <!-- Main area -->
        <main class="col-10 bg-light">
            <div
                class="d-flex justify-content-between align-items-center px-2 py-2 bg-white py-md-1 position-sticky top-0 z-3">
                <div class="title">Welcome to <?php echo $infoShemaData[0]["name"] ?></div>

                <div class="dropdown">
                    <button class="d-flex align-items-center border-0 bg-white gap-2" data-bs-toggle="dropdown">
                        <img src="../src/assets/logo.jpg" width="60" height="60" style="border-radius:50%">
                        <div>Username</div>
                    </button>

                    <ul class="dropdown-menu bg-white ">
                        <a href="../auth/signout.php" class="text-decoration-none">
                            <li><button class="dropdown-item">Sign Out</button></li>
                            <li><button class="dropdown-item">Account</button></li>
                        </a>
                    </ul>
                </div>
            </div>

            <div class="container-lg container-md container-sm p-3">

                <div class="w-100 d-flex mt-3 justify-content-between gap-3 flex-wrap">
                    <div class="w-100 bg-white shadow px-4 py-3 rounded">
                        <div class="d-flex justify-content-between align-items-center fw-semibold mb-2">
                            <div class=" d-flex w-75 align-items-center justify-content-between gap-3 mb-3">
                                <div class="fw-semibold">
                                    <i class="bi bi-credit-card-fill me-1"></i>
                                    Employees List
                                </div>
                                <div class="w-75">
                                    <input type="text"
                                        id="searchEmployee"
                                        class="form-control"
                                        placeholder="Search employee..."
                                        value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
                                </div>
                            </div>
                            <div>
                                <button id="editBtn" class="btn btn-primary z-1" disabled>
                                    Edit
                                </button>
                                <button id="detailBtn" class="btn btn-primary z-1" disabled>
                                    Detail
                                </button>
                                <button style="width: 99px;" class="btn btn-primary z-1" onclick="window.location.href='../register?type=staff'">
                                    Add
                                </button>
                            </div>

                            <!-- nav student lable -->
                        </div>

                        <!-- SCROLL CONTAINER -->
                        <div style="min-height: 600px;z-index: 10;" class="table-scroll modelBox ps-3" id="searchEmployee">
                            <table class="table table-hover mb-0">
                                <thead class="head-custom">
                                    <tr class="headLabel">
                                        <th class="col-1 ">EmpID</th>
                                        <th class="col-2">NameKh</th>
                                        <th class="col-2">NameEng</th>
                                        <th class="col-1">DateOfBirth</th>
                                        <th class="col-1">Gender</th>
                                        <th class="col-3">email</th>
                                        <th class="col-2">Possition</th>
                                        <th class="col-3">Phone</th>
                                    </tr>
                                </thead>
                                <tbody id="employeeTable" class="text-lg-start fs-08">

                                    <!--  -->
                                    <?php if ($result && $result->num_rows > 0): ?>
                                        <?php while ($row = $result->fetch_assoc()): ?>
                                            <tr data-id="<?= $row['employees_id'] ?>">
                                                <td class=""> <?= !empty($row['employees_id']) ? htmlspecialchars($row['employees_id']) : 'N/A' ?></td>
                                                <td> <?= !empty($row['namekhmer']) ? htmlspecialchars($row['namekhmer']) : 'N/A' ?></td>
                                                <td> <?= !empty($row['nameenglish']) ? htmlspecialchars($row['nameenglish']) : 'N/A' ?></td>
                                                <td> <?= !empty($row['dob']) ? htmlspecialchars($row['dob']) : 'N/A' ?></td>
                                                <td> <?= !empty($row['gender']) ? htmlspecialchars($row['gender']) : 'N/A' ?></td>
                                                <td> <?= !empty($row['email']) ? htmlspecialchars($row['email']) : 'N/A' ?></td>
                                                <td> <?= !empty($row['department_name']) ? htmlspecialchars($row['department_name']) : 'N/A' ?></td>
                                                <td> <?= !empty($row['phone']) ? htmlspecialchars($row['phone']) . ' ' . htmlspecialchars($row['phone2']) : 'N/A' ?></td>
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
    <script src="../../script.js"></script>
    <script>
        document.getElementById("searchEmployee").addEventListener("keyup", function() {
            let search = this.value;


            fetch("/System-Management/ajax/search_employees.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "search=" + encodeURIComponent(search)
                })
                .then(res => res.text())
                .then(data => {
                    document.getElementById("employeeTable").innerHTML = data;

                    selectedId = null;
                    editBtn.disabled = true;
                    detailBtn.disabled = true;
                });
        });
    </script>
    <script>
        let selectedId = null;

        const tableBody = document.getElementById("employeeTable");
        const editBtn = document.getElementById("editBtn");
        const detailBtn = document.getElementById("detailBtn");

        tableBody.addEventListener("click", function(e) {
            const row = e.target.closest("tr");
            if (!row) return;
            console.dir(e.target);
            console.dir(e.target.closest);

            // Remove highlight from all rows
            document.querySelectorAll("#employeeTable tr")
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
            window.location.href = "edit?type=employee&id=" + selectedId;
        });

        detailBtn.addEventListener("click", () => {
            if (!selectedId) return;
            window.location.href = "detail?type=employee&id=" + selectedId;
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