<?php

include "../config/db.php";

$students = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tblStudent"))['total'];

$teachers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tblEmployees where department_id = 4"))['total'];

$enrollments = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tblenrollments"))['total'];

$staff = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM tblEmployees"))['total'];

// $paid = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM invoices WHERE status='paid'"))['total'];

// $unpaid = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM invoices WHERE status='unpaid'"))['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>School System</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- ChartJS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background: #f4f6f9;
            font-family: 'Segoe UI', sans-serif;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background: #1e293b;
            position: fixed;
            color: white;
        }

        .sidebar-header {
            padding: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header img {
            width: 40px;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            width: 100%;
        }

        .sidebar-menu a {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 20px;
            color: #cbd5e1;
            text-decoration: none;
        }

        .sidebar-menu a:hover {
            background: #334155;
            color: white;
        }

        .submenu {
            list-style: none;
            padding-left: 20px;
            display: none;
        }

        .submenu a {
            font-size: 14px;
            padding: 8px 20px;
        }

        .content {
            margin-left: 250px;
            padding: 25px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
    </style>

</head>

<body>

    <!-- SIDEBAR -->
    <div class="sidebar">

        <div class="sidebar-header">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135755.png">
            <div>
                Empowerment Education<br>
                <small>English One Institute</small>
            </div>
        </div>

        <ul class="sidebar-menu">

            <li>
                <a href="#" onclick="toggleMenu('attendance')">
                    <span><i class="bi bi-calendar-check"></i> Attendance</span>
                    <i class="bi bi-chevron-down"></i>
                </a>

                <ul class="submenu" id="attendance">
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="#">Approve</a></li>
                </ul>
            </li>

            <li>
                <a href="#" onclick="toggleMenu('enrollment')">
                    <span><i class="bi bi-person-plus"></i> Enrollment</span>
                    <i class="bi bi-chevron-down"></i>
                </a>

                <ul class="submenu" id="enrollment">
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="#">Add Enrollment</a></li>
                </ul>
            </li>

        </ul>

    </div>

    <!-- CONTENT -->
    <div>

        <div class="container">

            <div class="row">

                <div class="col-md-6">
                    <canvas id="attendanceChart"></canvas>
                </div>

                <div class="col-md-6">
                    <canvas id="enrollmentChart"></canvas>
                </div>

                <div class="col-md-6 mt-4">
                    <canvas id="invoiceChart"></canvas>
                </div>

                <div class="col-md-6 mt-4">
                    <canvas id="totalChart"></canvas>
                </div>

            </div>

        </div>


    </div>


    <script>
        function toggleMenu(id) {

            let menu = document.getElementById(id)

            menu.style.display =
                menu.style.display === "block" ? "none" : "block"

        }
    </script>
    <script>
        new Chart(document.getElementById("enrollmentChart"), {

            type: 'line',

            data: {

                labels: ["Jan", "Feb", "Mar", "Apr", "May"],

                datasets: [{
                    label: "Enrollments",
                    data: [50, 70, 90, 120, 150]
                }]

            }

        });
    </script>
    <script>
        new Chart(document.getElementById("attendanceChart"), {

            type: 'bar',

            data: {
                labels: ["Math", "English", "Physics", "Chemistry"],
                datasets: [{
                    label: "Attendance %",
                    data: [90, 85, 75, 80]
                }]
            }

        });
    </script>

    <script>
        new Chart(document.getElementById("totalChart"), {

            type: 'pie',

            data: {

                labels: ["Students", "Teachers", "Enrollments", "Staff"],

                datasets: [{

                    data: [
                        <?php echo $students ?>,
                        <?php echo $teachers ?>,
                        <?php echo $enrollments ?>,
                        <?php echo $staff ?>
                    ]

                }]

            }

        });
    </script>

</body>

</html>