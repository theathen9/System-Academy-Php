<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>3E One Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            background: #f4f6f9;
        }

        .sidebar {
            width: 230px;
            height: 100vh;
            position: fixed;
            background: #ffffff;
            border-right: 1px solid #eee;
            padding-top: 20px;
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

<body>

    <!-- Sidebar -->

    <div class="sidebar">

        <h5 class="text-center mb-4">3E One</h5>

        <a href="#"><i class="fa fa-chart-line"></i> Dashboard</a>
        <a href="#"><i class="fa fa-school"></i> Institute</a>
        <a href="#"><i class="fa fa-user-plus"></i> Enrollment</a>
        <a href="#"><i class="fa fa-check-circle"></i> Attendance</a>
        <a href="#"><i class="fa fa-file"></i> Examination</a>
        <a href="#"><i class="fa fa-calendar"></i> Schedule</a>
        <a href="#"><i class="fa fa-user"></i> Register</a>
        <a href="#"><i class="fa fa-dollar"></i> Payment</a>

    </div>

    <!-- Main Content -->

    <div class="content">

        <h4 class="mb-4">Welcome to Empowerment Education English One</h4>

        <div class="row g-4">

            <!-- Students -->

            <div class="col-md-3">
                <div class="card card-box">
                    <div class="card-body d-flex justify-content-between">

                        <div>
                            <h6>Total Students</h6>
                            <h3>1250</h3>
                        </div>

                        <div class="stat-icon bg-blue">
                            <i class="fa fa-graduation-cap"></i>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Teachers -->

            <div class="col-md-3">
                <div class="card card-box">
                    <div class="card-body d-flex justify-content-between">

                        <div>
                            <h6>Total Teachers</h6>
                            <h3>75</h3>
                        </div>

                        <div class="stat-icon bg-green">
                            <i class="fa fa-users"></i>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Exams -->

            <div class="col-md-3">
                <div class="card card-box">
                    <div class="card-body d-flex justify-content-between">

                        <div>
                            <h6>Examinations</h6>
                            <h3>30</h3>
                        </div>

                        <div class="stat-icon bg-orange">
                            <i class="fa fa-file"></i>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Institutes -->

            <div class="col-md-3">
                <div class="card card-box">
                    <div class="card-body d-flex justify-content-between">

                        <div>
                            <h6>Institutes</h6>
                            <h3>5</h3>
                        </div>

                        <div class="stat-icon bg-red">
                            <i class="fa fa-school"></i>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <br>

        <div class="row">

            <!-- Enrollment Chart -->

            <div class="col-md-7">

                <div class="card card-box">
                    <div class="card-body">

                        <h5>Enrollment Statistics</h5>

                        <canvas id="enrollChart"></canvas>

                    </div>
                </div>

            </div>

            <!-- Attendance Chart -->

            <div class="col-md-5">

                <div class="card card-box">
                    <div class="card-body">

                        <h5>Attendance Overview</h5>

                        <canvas id="attendanceChart"></canvas>

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

    <script>
        const enrollChart = new Chart(document.getElementById('enrollChart'), {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                datasets: [{
                    label: 'Enrollments',
                    data: [120, 90, 140, 110, 130, 170, 150, 180, 200, 190, 210, 195],
                }]
            }
        })

        const attendanceChart = new Chart(document.getElementById('attendanceChart'), {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Absent', 'Leave'],
                datasets: [{
                    data: [60, 25, 15],
                }]
            }
        })
    </script>

</body>

</html>