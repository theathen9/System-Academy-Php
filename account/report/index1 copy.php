
<?php
include_once __DIR__ . '/../../config/bootstrap.php';
include_once __DIR__ . '/../../data/dataSchema.php';
include_once __DIR__ . '/../../components/Navbar.php';
include_once __DIR__ . '/../../components/Avatar.php';

$routeAdmin[0]["active"] = false;
$routeAdmin[8]["active"] = true;

$userId = checkAuth();

if (!$userId) {
    header("Location: ../auth/signin.php");
    exit;
}

authorizeRole('admin');

/*
|--------------------------------------------------------------------------
| SAMPLE DATA
|--------------------------------------------------------------------------
| Replace with database queries later
*/

$totalStudents = 1245;
$totalEnrollments = 982;
$totalRevenue = 24850;
$attendanceRate = 95.4;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports | <?= $infoSchemaData[1]["name_short"] ?></title>

    <link rel="icon" type="image/png"
        href="<?= $infoSchemaData[5]["image"] ?>">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="../../src/style.css">

    <style>

        body{
            background:#f5f7fb;
        }

        .report-card{
            background:#fff;
            border-radius:22px;
            padding:25px;

            box-shadow:
            0 10px 30px rgba(0,0,0,.06);

            transition:.3s;

            display:flex;
            justify-content:space-between;
            align-items:center;
        }

        .report-card:hover{
            transform:translateY(-5px);
        }

        .report-icon{
            width:65px;
            height:65px;

            border-radius:18px;

            display:flex;
            align-items:center;
            justify-content:center;

            font-size:28px;
            color:white;
        }

        .students{
            background:linear-gradient(
                135deg,
                #7c3aed,
                #a855f7
            );
        }

        .enrollments{
            background:linear-gradient(
                135deg,
                #22c55e,
                #16a34a
            );
        }

        .revenue{
            background:linear-gradient(
                135deg,
                #f59e0b,
                #f97316
            );
        }

        .attendance{
            background:linear-gradient(
                135deg,
                #2563eb,
                #3b82f6
            );
        }

        .dashboard-card{
            background:#fff;
            border-radius:22px;
            padding:25px;
            box-shadow:
            0 10px 30px rgba(0,0,0,.05);
        }

        .quick-report{
            padding:14px;
            border-radius:14px;
            background:#f8fafc;
            cursor:pointer;
            transition:.3s;
            margin-bottom:12px;
        }

        .quick-report:hover{
            background:#eef2ff;
        }

        .table-modern{
            background:white;
            border-radius:20px;
            overflow:hidden;
        }

        .table-modern thead{
            background:#f8fafc;
        }

    </style>
</head>

<body class="container-fluid p-0 overflow-x-hidden">

<div class="row g-3">

    <?php Navbar($infoSchemaData, $routeAdmin); ?>

    <main class="col-lg-10 col-sm-12">

        <div
            class="d-flex justify-content-between align-items-center px-3 py-3 bg-white sticky-top shadow-sm">

            <div>
                <h4 class="mb-0 fw-bold">
                    Reports Center
                </h4>

                <small class="text-muted">
                    Analytics & Reporting Dashboard
                </small>
            </div>

            <?php Avatar($_SESSION['role']); ?>

        </div>

        <div class="container-fluid py-4">

            <!-- FILTERS -->

            <div class="dashboard-card mb-4">

                <div class="row g-3">

                    <div class="col-md-3">
                        <label class="form-label">
                            Date Range
                        </label>

                        <input
                            type="date"
                            class="form-control">
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">
                            Academic Year
                        </label>

                        <select class="form-select">
                            <option>2025-2026</option>
                            <option>2024-2025</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">
                            Course
                        </label>

                        <select class="form-select">
                            <option>All Courses</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">

                        <button
                            class="btn btn-primary w-100">

                            <i class="bi bi-funnel-fill me-2"></i>
                            Generate Report

                        </button>

                    </div>

                </div>

            </div>

            <!-- KPI -->

            <div class="row g-4 mb-4">

                <div class="col-xl-3 col-md-6">

                    <div class="report-card">

                        <div>
                            <small class="text-muted">
                                Students
                            </small>

                            <h2 class="fw-bold">
                                <?= number_format($totalStudents) ?>
                            </h2>
                        </div>

                        <div class="report-icon students">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>

                    </div>

                </div>

                <div class="col-xl-3 col-md-6">

                    <div class="report-card">

                        <div>
                            <small class="text-muted">
                                Enrollments
                            </small>

                            <h2 class="fw-bold">
                                <?= number_format($totalEnrollments) ?>
                            </h2>
                        </div>

                        <div class="report-icon enrollments">
                            <i class="bi bi-journal-bookmark-fill"></i>
                        </div>

                    </div>

                </div>

                <div class="col-xl-3 col-md-6">

                    <div class="report-card">

                        <div>
                            <small class="text-muted">
                                Revenue
                            </small>

                            <h2 class="fw-bold">
                                $<?= number_format($totalRevenue) ?>
                            </h2>
                        </div>

                        <div class="report-icon revenue">
                            <i class="bi bi-cash-stack"></i>
                        </div>

                    </div>

                </div>

                <div class="col-xl-3 col-md-6">

                    <div class="report-card">

                        <div>
                            <small class="text-muted">
                                Attendance
                            </small>

                            <h2 class="fw-bold">
                                <?= $attendanceRate ?>%
                            </h2>
                        </div>

                        <div class="report-icon attendance">
                            <i class="bi bi-calendar-check-fill"></i>
                        </div>

                    </div>

                </div>

            </div>

            <!-- CHARTS -->

            <div class="row g-4 mb-4">

                <div class="col-lg-8">

                    <div class="dashboard-card">

                        <h5 class="fw-bold mb-3">
                            Enrollment Trend
                        </h5>

                        <canvas id="enrollmentChart"></canvas>

                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="dashboard-card">

                        <h5 class="fw-bold mb-3">
                            Quick Reports
                        </h5>

                        <div class="quick-report">
                            🎓 Student Report
                        </div>

                        <div class="quick-report">
                            💵 Payment Report
                        </div>

                        <div class="quick-report">
                            📅 Attendance Report
                        </div>

                        <div class="quick-report">
                            👨‍🏫 Employee Report
                        </div>

                    </div>

                </div>

            </div>

            <!-- RECENT REPORTS -->

            <div class="dashboard-card">

                <div
                    class="d-flex justify-content-between align-items-center mb-3">

                    <h5 class="fw-bold mb-0">
                        Recent Reports
                    </h5>

                    <button
                        class="btn btn-success">

                        <i class="bi bi-file-earmark-excel"></i>
                        Export

                    </button>

                </div>

                <div class="table-responsive">

                    <table
                        class="table table-hover table-modern">

                        <thead>

                        <tr>
                            <th>Report</th>
                            <th>Type</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>

                        </thead>

                        <tbody>

                        <tr>
                            <td>Student Report</td>
                            <td>Student</td>
                            <td>2026-06-13</td>
                            <td>
                                <span class="badge bg-success">
                                    Completed
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-primary">
                                    View
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

new Chart(
    document.getElementById('enrollmentChart'),
    {
        type:'line',

        data:{
            labels:[
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun'
            ],

            datasets:[
            {
                label:'Enrollments',

                data:[
                    120,
                    180,
                    220,
                    280,
                    350,
                    420
                ],

                tension:.4,

                fill:true
            }]
        }
    }
);

</script>

</body>
</html>