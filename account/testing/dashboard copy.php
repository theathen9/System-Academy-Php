<?php
//./admin/institute/employees.php
// require_once( __DIR__ . "/../../config/db.php");
date_default_timezone_set('Asia/Phnom_Penh');
include_once __DIR__ . '/../config/db.php';
include_once __DIR__ . '/../data/dbShemaData.php';
// include_once __DIR__ . '/api/dashboard.php';

include "../data/dataShema.php";
// include_once "/config/db.php";
$staticShemaData[0]["active"] = true;

$totalStudent = countStudent($conn, "");
$totalTeacher = countTeacher($conn, "");



$start = $_GET['startDate'] ?? date('Y-m-d');
$end   = $_GET['endDate'] ?? date('Y-m-d');

// Format for display
$datePicker = date('M d, Y', strtotime($start)) . ' — ' . date('M d, Y', strtotime($end));

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | <?php echo $infoShemaData[1]["name_short"] ?></title>
    <link rel="icon" type="image/png" href="<?php echo $infoShemaData[5]["image"] ?>">
    <link rel="icon" type="image/png" href="<?php echo $infoShemaData[5]["image"] ?>">
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>


    <style>
        .tag-box {
            display: flex;
            flex-wrap: wrap;
            border: 1px solid #ccc;
            padding: 5px;
            border-radius: 6px;
        }

        .tag {
            background: #e3f2fd;
            margin: 3px;
            padding: 5px 10px;
            border-radius: 20px;
            display: flex;
            align-items: center;
        }

        .tag span {
            margin-left: 8px;
            cursor: pointer;
            color: red;
        }

        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
        }

        .sidebar a:hover {
            background: #0d6efd;
            color: white;
            border-radius: 6px;
        }

        .content {
            margin-left: 240px;
            padding: 20px;
        }

        .card-box {
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .stat-icon {
            font-size: 28px;
            padding: 15px;
            border-radius: 10px;
            color: white;
        }

        .bg-blue {
            background: #0d6efd;
        }

        .bg-green {
            background: #28a745;
        }

        .bg-orange {
            background: #f39c12;
        }

        .bg-red {
            background: #e74c3c;
        }
    </style>

</head>

<body class="container-fluid p-0 overflow-x-hidden">
    <div class="row g-3">
        <nav class="navBar col-2 p-3">
            <div class="d-flex gap-1 mb-4 align-items-center align-self-center position-sticky top-0 bg-white p-0">
                <img src="<?php echo $infoShemaData[5]["image"] ?>" width="60" height="60" alt="logo" class="rounded-circle">
                <div class="title">
                    <p class="m-auto"><?php echo $infoShemaData[1]["name_short"] ?></p>
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
                <div class="w-100 d-flex justify-content-between gap-3 flex-wrap h-25">
                    <div class="d-flex w-50">
                        <h3 class="text-break me-3">Analytics</h3>
                        <div style="width: 450px;" class="tag-box" id="tagBox">
                            <input type="text" id="tagInput" placeholder="Enter tags..." class="form-control">
                        </div>
                        <input type="hidden" name="tags" id="tagsHidden">
                    </div>


                    <div style="height: 36px;" class="d-flex">

                        <form method="GET" id="filterForm" class="d-flex align-items-center" autocomplete="off">

                            <!-- Date Picker -->
                            <div class="d-flex me-3">

                                <!-- Display -->
                                <input type="text"
                                    id="dateDisplay"
                                    class="form-control"
                                    value="<?= htmlspecialchars($datePicker) ?>"
                                    readonly
                                    style="width: 260px;">

                                <!-- Hidden Start -->
                                <input type="hidden" name="startDate" id="startDate"
                                    value="<?= $_GET['startDate'] ?? '' ?>">

                                <!-- Hidden End -->
                                <input type="hidden" name="endDate" id="endDate"
                                    value="<?= $_GET['endDate'] ?? '' ?>">

                            </div>

                            <!-- Filter Dropdown -->
                            <div class="dropdown">
                                <button style="width: 117px;"
                                    type="button"
                                    class="btn btn-primary d-flex align-items-center justify-content-center"
                                    data-bs-toggle="dropdown">

                                    <i class="fa fa-filter me-2"></i> Filter
                                </button>

                                <div class="dropdown-menu p-3 bg-white">

                                    <?php
                                    $selectedStatus = $_GET['status'] ?? [];
                                    ?>

                                    <div class="form-check">
                                        <input id="filterAll" class="form-check-input" type="checkbox" name="status[]" value="all"
                                            <?= in_array('all', $selectedStatus) ? 'checked' : '' ?>>
                                        <label class="form-check-label">All</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="status[]" value="attendance"
                                            <?= in_array('attendance', $selectedStatus) ? 'checked' : '' ?>>
                                        <label class="form-check-label">Attendance</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="status[]" value="payment"
                                            <?= in_array('payment', $selectedStatus) ? 'checked' : '' ?>>
                                        <label class="form-check-label">Payment</label>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="status[]" value="courses"
                                            <?= in_array('courses', $selectedStatus) ? 'checked' : '' ?>>
                                        <label class="form-check-label">Courses</label>
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100 mt-2">
                                        Apply Filter
                                    </button>

                                </div>
                            </div>

                        </form>
                        <button style="width: 135px;" type="button" class="btn btn-success ms-3">
                            Export Data
                        </button>
                    </div>

                </div>


            </div>

            <div class="w-100 d-flex mt-3 ms-3 justify-content-between gap-3 flex-wrap">
                <div class="w-100 bg-white shadow px-4 py-3 rounded">
                    <div class="row g-4">
                        <div class="row g-4">
                            <?php
                            $cards = [
                                ["Total Revenue", "totalRevenueCard", "fa-dollar-sign", "bg-green"],
                                ["Total Students", "totalStudentCard", "fa-graduation-cap", "bg-blue"],
                                ["Total Teachers", "totalTeacherCard", "fa-users", "bg-green"],
                                ["Institutes", "institutesCard", "fa-school", "bg-red"],
                            ];
                            ?>

                            <?php foreach ($cards as $c): ?>
                                <div class="col-md-3">
                                    <div class="card card-box">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6><?= htmlspecialchars($c[0]) ?></h6>
                                                <h3 id="<?= htmlspecialchars($c[1]) ?>">0</h3> <!-- Default value 0 -->
                                            </div>
                                            <div class="stat-icon <?= htmlspecialchars($c[3]) ?>">
                                                <i class="fa <?= htmlspecialchars($c[2]) ?>"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <br>

                    <div class="row g-4 mt-2">


                        <!-- Attendance -->
                        <div class="col-md-8">
                            <div class="card card-box">
                                <div class="card-body">
                                    <h5>Attendance Statistics</h5>
                                    <canvas id="attenChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card card-box">
                                <div class="card-body">
                                    <h5>Attendance Overview</h5>
                                    <canvas id="attenOverviewChart"></canvas>
                                </div>
                            </div>
                        </div>



                        <!-- Enrollment -->
                        <div class="col-md-8">
                            <div class="card card-box">
                                <div class="card-body">
                                    <h5>Enrollment Statistics</h5>
                                    <canvas id="enrollChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card card-box">
                                <div class="card-body">
                                    <h5>Enrollment Overview</h5>
                                    <canvas id="enrollOverviewChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                    <!-- Recent Activity -->

                    <div class="card card-box">
                        <div class="card-body">

                            <h5>Recent Activity</h5>

                            <ul class="list-group">

                                <li class="list-group-item">
                                    New student enrolled — Grade 3B
                                </li>

                                <li class="list-group-item">
                                    Scheduled examination next Monday
                                </li>

                                <li class="list-group-item">
                                    Attendance marked for Grade 6A
                                </li>

                                <li class="list-group-item">
                                    Tuition fee payment completed
                                </li>

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script src="../../script.js"></script>


    <script>
        let enrollChart, attendanceChart;

        function initCharts() {
            enrollChart = new Chart(document.getElementById('enrollChart'), {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Enrollments',
                        data: []
                    }]
                }
            });

            const attendanceChart = new Chart(document.getElementById('attenOverviewChart'), {
                type: 'doughnut',
                data: {
                    labels: [`Present ${15}`,`Absent ${12}`],
                    datasets: [{
                        data: [15, 12], // example numbers
                        backgroundColor: ['#28a745', '#e74c3c']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        datalabels: {
                            color: '#fff',
                            formatter: (value, ctx) => {
                                const data = ctx.chart.data.datasets[0].data;
                                const total = data.reduce((a, b) => a + b, 0);
                                const percent = ((value / total) * 100).toFixed(2);
                                return percent + '%';
                            },
                            font: {
                                weight: 'bold',
                                size: 14
                            }
                        }
                    }
                },
                plugins: [ChartDataLabels]
            });

    

        }

        async function loadDashboard() {

            const start = document.getElementById('startDate').value;
            const end = document.getElementById('endDate').value;

            const res = await fetch(`/system-management/admin/api/dashboard.php`);
            const data = await res.json();

            if (data.studentCard !== undefined) {
                document.getElementById('totalStudentCard').innerText = data.studentCard;
            }
            if (data.teacherCard !== undefined) {
                document.getElementById('totalTeacherCard').innerText = data.teacherCard;
            }

            // Update cards
            document.getElementById('totalStudentCard').innerText = data.studentCard;
            document.getElementById('totalTeacherCard').innerText = data.teacherCard;
            document.getElementById('totalRevenueCard').innerText = '$' + data.revenue;

            // Update charts
            enrollChart.data.labels = data.labels;
            enrollChart.data.datasets[0].data = data.monthly;
            enrollChart.update();

            attendanceChart.data.datasets[0].data = [
                data.attendance.present,
                data.attendance.absent
            ];
            attendanceChart.update();
        }

        // Init
        initCharts();
        loadDashboard();
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


    <script>
        const startDate = "<?= $start ?>";
        const endDate = "<?= $end ?>";
    </script>
    <script>
        const today = new Date();

        flatpickr("#dateDisplay", {
            mode: "range",
            maxDate: "today",
            defaultDate: [startDate, endDate],

            onReady: function(selectedDates) {
                if (selectedDates.length === 2) {
                    formatDisplay(selectedDates);

                }
            },

            onChange: function(selectedDates) {
                if (selectedDates.length === 2) {

                    document.getElementById('startDate').value = selectedDates[0].toISOString().split('T')[0];
                    document.getElementById('endDate').value = selectedDates[1].toISOString().split('T')[0];
                    formatDisplay(selectedDates);

                    // document.getElementById("filterForm").submit();
                    loadDashboard(); // 🔥 NO RELOAD
                }
            }
        });

        function formatDateLocal(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }

        function formatDisplay(dates) {
            const options = {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            };

            const start = formatDateLocal(dates[0]);
            const end = formatDateLocal(dates[1]);

            document.getElementById("startDate").value = start;
            document.getElementById("endDate").value = end;

            document.getElementById("dateDisplay").value =
                `${dates[0].toLocaleDateString('en-US', options)} — ${dates[1].toLocaleDateString('en-US', options)}`;
        }
    </script>



    <script>
        let tags = [];

        const input = document.getElementById("tagInput");
        const tagBox = document.getElementById("tagBox");
        const hidden = document.getElementById("tagsHidden");

        input.addEventListener("keydown", function(e) {
            if (e.key === "Enter") {
                e.preventDefault();

                let value = input.value.trim();
                if (value && !tags.includes(value)) {
                    tags.push(value);
                    renderTags();
                }

                input.value = "";
            }
        });

        function renderTags() {
            document.querySelectorAll(".tag").forEach(el => el.remove());

            tags.forEach((tag, index) => {
                let div = document.createElement("div");
                div.className = "tag";
                div.innerHTML = `${tag} <span onclick="removeTag(${index})">&times;</span>`;
                tagBox.insertBefore(div, input);
            });

            hidden.value = tags.join(",");
        }

        function removeTag(index) {
            tags.splice(index, 1);
            renderTags();
        }
    </script>

    <script>
        document.getElementById('filterAll').addEventListener('change', function() {
            let checkboxes = document.querySelectorAll('input[name="status[]"]');

            checkboxes.forEach(cb => {
                if (cb !== this) cb.checked = this.checked;
            });
        });
    </script>

</body>

</html>