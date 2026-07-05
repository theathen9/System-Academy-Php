<?php

session_start();
date_default_timezone_set('Asia/Phnom_Penh');
include_once __DIR__ . '/../../config/bootstrap.php';
include_once __DIR__ . "/../../data/dataSchema.php";
include_once __DIR__ . "/../../components/Navbar.php";
include_once __DIR__ . "/../../components/Avatar.php";




if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


$userId = checkAuth();

if (!$userId) {
    header("Location: " . BASE_URL . "/auth/signin.php");
    exit;
}

authorizeRole('admin');
//     var_dump($_SESSION);
// var_dump($_COOKIE);
// exit;

// include_once "/config/db.php";
$routeAdmin[0]["active"] = false;
$routeAdmin[8]["active"] = true;
$routeAdmin[8]['submenu'][0]['active'] = true;

$startDate = $_GET['startDate'] ?? date('Y-m-d');
$endDate   = $_GET['endDate'] ?? date('Y-m-d');

$year = $startDate;
// $firstYear = explode('-', $year)[0];
$firstYear = date('Y', strtotime($startDate));


// Format for display
$datePicker = date('M d, Y', strtotime($startDate)) . ' — ' . date('M d, Y', strtotime($endDate));
$currentYear = date('Y');

// Sample values (replace with real queries)
$totalStudents = 1250;
$totalEnrollments = 980;
$totalRevenue = 45800;
$attendanceRate = 92;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports | <?php echo $infoSchemaData[1]["name_short"] ?></title>
    <link rel="icon" type="image/png" href="<?php echo $infoSchemaData[5]["image"] ?>">
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
    <script src="/system-management/src/assets/js/user-profile.js"></script>


    <style>
        .page-title {
            font-weight: 700;
        }

        .card-report {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, .05);
            transition: .3s;
        }

        .card-report:hover {
            transform: translateY(-5px);
        }

        .card-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }

        .students {
            background: #3B82F6;
        }

        .enrollments {
            background: #8B5CF6;
        }

        .revenue {
            background: #10B981;
        }

        .attendance {
            background: #F59E0B;
        }

        .quick-report {
            cursor: pointer;
            transition: .3s;
        }

        .quick-report:hover {
            background: #f8f9fa;
        }

        .chart-container {
            height: 350px;
        }
    </style>
</head>

<body class="container-fluid p-0 overflow-x-hidden">
    <div class="row g-3">

        <?php Navbar($infoSchemaData, $routeAdmin) ?>

        <!-- Main area -->
        <main class="col-10 bg-light">
            <div
                class="d-flex justify-content-between align-items-center px-2 py-2 bg-white position-sticky top-0 z-3">
                <div class="title">Welcome to <?php echo $infoSchemaData[0]["name"] ?></div>

                <?php Avatar($_SESSION['role']); ?>

            </div>


            <div class="container-fluid p-4">

                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="page-title">Reports & Analytics</h2>
                        <p class="text-muted mb-0">
                            School Management Reports Dashboard
                        </p>
                    </div>

                    <div>
                        <button class="btn btn-danger">
                            <i class="fas fa-file-pdf"></i> PDF
                        </button>

                        <button class="btn btn-success">
                            <i class="fas fa-file-excel"></i> Excel
                        </button>

                        <button class="btn btn-primary">
                            <i class="fas fa-print"></i> Print
                        </button>
                    </div>
                </div>

                <!-- FILTER BAR -->
                <div class="card card-report mb-4 p-3">
                    <div class="card-body">

                        <div class="row g-3">

                            <div class="col-md-3">
                                <label>Date Range</label>
                                <!-- Display -->
                                <input type="text"
                                    id="dateDisplay"
                                    class="form-control"
                                    value="<?= htmlspecialchars($datePicker) ?>"
                                    readonly
                                    style="width: 260px;">

                                <!-- Hidden Start -->
                                <input type="hidden" name="startDate" id="startDate"
                                    value="<?= $startDate ?>">

                                <!-- Hidden End -->
                                <input type="hidden" name="endDate" id="endDate"
                                    value="<?= $endDate ?>">
                            </div>

                            <div class="col-md-2">
                                <label>Academic Year</label>
                                <select id="selectYear" class="form-select">
                                    <option value="">Select Year</option>

                                    <?php for ($y = $currentYear; $y >= 2020; $y--): ?>
                                        <option value="<?= $y ?>"
                                            <?= ($firstYear == $y ? 'selected' : '') ?>>
                                            <?= $y ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>Course</label>
                                <select class="form-select">
                                    <option>All Courses</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <label>Status</label>
                                <select class="form-select">
                                    <option>All</option>
                                </select>
                            </div>

                            <div class="col-md-3 d-flex align-items-end">
                                <button class="btn btn-primary w-100">
                                    <i class="fas fa-chart-line"></i>
                                    Generate Report
                                </button>
                            </div>

                        </div>

                    </div>
                </div>

                <!-- KPI CARDS -->
                <div class="row g-4 mb-4">

                    <div class="col-lg-3">
                        <div class="card card-report">
                            <div class="card-body d-flex align-items-center">

                                <div class="card-icon students me-3">
                                    <i class="fas fa-user-graduate"></i>
                                </div>

                                <div>
                                    <small>Total Students</small>
                                    <h3><?= number_format($totalStudents) ?></h3>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="card card-report">
                            <div class="card-body d-flex align-items-center">

                                <div class="card-icon enrollments me-3">
                                    <i class="fas fa-book"></i>
                                </div>

                                <div>
                                    <small>Enrollments</small>
                                    <h3><?= number_format($totalEnrollments) ?></h3>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="card card-report">
                            <div class="card-body d-flex align-items-center">

                                <div class="card-icon revenue me-3">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>

                                <div>
                                    <small>Revenue</small>
                                    <h3>$<?= number_format($totalRevenue) ?></h3>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="card card-report">
                            <div class="card-body d-flex align-items-center">

                                <div class="card-icon attendance me-3">
                                    <i class="fas fa-calendar-check"></i>
                                </div>

                                <div>
                                    <small>Attendance</small>
                                    <h3><?= $attendanceRate ?>%</h3>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>

                <!-- CHARTS -->
                <div class="row g-4 mb-4">

                    <div class="col-lg-6">
                        <div class="card card-report">
                            <div class="card-header bg-white">
                                Enrollment Trend
                            </div>

                            <div class="card-body">
                                <canvas id="enrollmentChart"></canvas>
                            </div>
                        </div>
                    </div>



                    <div class="col-lg-6">
                        <div class="card card-report">
                            <div class="card-header bg-white">
                                Revenue Trend
                            </div>

                            <div class="card-body">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>
                    </div>



                </div>



                <!-- RECENT REPORTS -->
                <div class="card card-report">
                    <div class="card-header bg-white">
                        Recent Reports
                    </div>

                    <div class="card-body">

                        <table class="table table-hover">

                            <thead>
                                <tr>
                                    <th>Report Name</th>
                                    <th>Type</th>
                                    <th>Generated By</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>

                                <tr>
                                    <td>Student Report</td>
                                    <td>PDF</td>
                                    <td>Admin</td>
                                    <td>2026-06-13</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">
                                            View
                                        </button>
                                    </td>
                                </tr>

                                <tr>
                                    <td>Payment Report</td>
                                    <td>Excel</td>
                                    <td>Accountant</td>
                                    <td>2026-06-12</td>
                                    <td>
                                        <button class="btn btn-sm btn-success">
                                            Download
                                        </button>
                                    </td>
                                </tr>

                            </tbody>

                        </table>

                    </div>
                </div>

            </div>
        </main>
    </div>

    <script>
        let startDate = "<?= $startDate ?>";
        let endDate = "<?= $endDate ?>";

        // ----------------------
        // Flatpickr Setup
        // ----------------------
        const fp = flatpickr("#dateDisplay", {
            mode: "range",
            maxDate: "today",
            defaultDate: [startDate, endDate],
            onReady: function(selectedDates) {
                if (selectedDates.length === 2) formatDisplay(selectedDates);
            },
            onChange: function(selectedDates) {
                if (selectedDates.length === 2) {

                    startDate = formatDateLocal(selectedDates[0]);
                    endDate = formatDateLocal(selectedDates[1]);

                    document.getElementById('startDate').value = startDate;
                    document.getElementById('endDate').value = endDate;

                    formatDisplay(selectedDates);

                    loadDashboard();
                }
            }
        });

        function formatDateLocal(date) {
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            return `${y}-${m}-${d}`;
        }

        function formatDisplay(dates) {
            const options = {
                month: 'short',
                day: 'numeric',
                year: 'numeric'
            };
            document.getElementById("dateDisplay").value =
                `${dates[0].toLocaleDateString('en-US', options)} — ${dates[1].toLocaleDateString('en-US', options)}`;
        }


        function loadDashboard() {
            const params = new URLSearchParams({
                startDate: startDate,
                endDate: endDate
            });

            window.location.href = '?' + params.toString();
        }


        // document.getElementById('selectYear').addEventListener('change', function() {

        //     const year = this.value;

        //     if (!year) return;

        //     const month = new Date().getMonth() + 1;
        //     const lastDay = new Date(year, month, 0).getDate();

        //     startDate = `${year}-${String(month).padStart(2, '0')}-01`;
        //     endDate = `${year}-${String(month).padStart(2, '0')}-${String(lastDay).padStart(2, '0')}`;

        //     document.getElementById('startDate').value = startDate;
        //     document.getElementById('endDate').value = endDate;

        //     fp.setDate([startDate, endDate], true);

        //     loadDashboard();
        // });

 
    


        document.getElementById('selectYear')
            .addEventListener('change', function(e) {

                const year = parseInt(this.value);
                const month = new Date().getMonth();
                if (!year) return;

                const currentDate = new Date();
                const currentYear = currentDate.getFullYear();

                const lastDay = new Date(year, month, 0).getDate();
                console.log('Last Day of Month:', lastDay);

                // always start from Jan 1
                startDate = `${year}-01-01`;


                if (year === currentYear) {
                    // up to today
                    endDate = formatDateLocal(currentDate);
                } else {
                    // full year end
                    const lastDay = new Date(year, month, 0).getDate();

                    endDate = `${year}-12-${String(lastDay).padStart(2, '0')}`;
                }

                document.getElementById('startDate').value = startDate;
                document.getElementById('endDate').value = endDate;

                fp.setDate([startDate, endDate], true);

                loadDashboard();
                e.preventDefault();

            });


        // ----------------------
        // Detect Filter Type
        // ----------------------
        function detectFilterType(start, end) {
            if (!start || !end) return 'custom';

            const s = new Date(start),
                e = new Date(end),
                today = new Date();
            [s, e, today].forEach(d => d.setHours(0, 0, 0, 0));

            const format = d => d.toISOString().split('T')[0];

            const todayStr = format(today);
            const yesterday = new Date(today);
            yesterday.setDate(today.getDate() - 1);
            const last7Start = new Date(today);
            last7Start.setDate(today.getDate() - 6);

            const firstDayMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            const firstDayLastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
            const lastDayLastMonth = new Date(today.getFullYear(), today.getMonth(), 0);

            const firstDayYear = new Date(today.getFullYear(), 0, 1);
            const firstDayLastYear = new Date(today.getFullYear() - 1, 0, 1);
            const lastDayLastYear = new Date(today.getFullYear() - 1, 11, 31);

            const sStr = format(s),
                eStr = format(e);

            if (sStr === todayStr && eStr === todayStr) return 'today';
            if (sStr === format(yesterday) && eStr === format(yesterday)) return 'yesterday';
            if (sStr === format(last7Start) && eStr === todayStr) return 'last7days';
            if (sStr === format(firstDayMonth) && eStr === todayStr) return 'thisMonth';
            if (sStr === format(firstDayLastMonth) && eStr === format(lastDayLastMonth)) return 'lastMonth';
            if (sStr === format(firstDayYear) && eStr === todayStr) return 'thisYear';
            if (sStr === format(firstDayLastYear) && eStr === format(lastDayLastYear)) return 'lastYear';

            return 'custom';
        }

        new Chart(document.getElementById('enrollmentChart'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Enrollments',
                    data: [100, 150, 180, 250, 300, 400],
                    borderWidth: 3,
                    fill: true
                }]
            }
        });

        new Chart(document.getElementById('revenueChart'), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue',
                    data: [5000, 7000, 10000, 9000, 13000, 15000]
                }]
            }
        });
    </script>

</body>

</html>