<?php include_once __DIR__ . '/../../config/db.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background:#f5f7fb; }
        .card { border-radius:15px; }
        .card h3 { font-weight:bold; }
    </style>
</head>

<body class="p-4">

<div class="container-fluid">

    <h2 class="mb-4">🚀 Advanced Dashboard</h2>

    <!-- FILTERS -->
    <div class="row mb-4">

        <div class="col-md-3">
            <select id="year" class="form-select">
                <option value="">All Years</option>
                <?php
                $years = $conn->query("SELECT DISTINCT academic_year FROM tblStudents");
                while($y = $years->fetch_assoc()){
                    echo "<option value='{$y['academic_year']}'>{$y['academic_year']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-3">
            <select id="course" class="form-select">
                <option value="">All Courses</option>
                <?php
                $courses = $conn->query("SELECT * FROM tblCourses");
                while($c = $courses->fetch_assoc()){
                    echo "<option value='{$c['course_id']}'>{$c['course_name']}</option>";
                }
                ?>
            </select>
        </div>

    </div>

    <!-- CARDS -->
    <div class="row mb-4" id="cards"></div>

    <!-- CHARTS -->
    <div class="row">
        <div class="col-md-6"><canvas id="revenueChart"></canvas></div>
        <div class="col-md-6"><canvas id="enrollChart"></canvas></div>
        <div class="col-md-6 mt-4"><canvas id="statusChart"></canvas></div>
    </div>

</div>

<script>
let revenueChart, enrollChart, statusChart;

function loadDashboard(){

    let year = document.getElementById('year').value;
    let course = document.getElementById('course').value;

    fetch(`./dashboard_data.php?year=${year}&course=${course}`)
    .then(res => res.json())
    .then(data => {

        // Cards
        document.getElementById('cards').innerHTML = `
            <div class="col-md-3">
                <div class="card p-3 shadow">
                    <h6>Students</h6>
                    <h3>${data.cards.students}</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 shadow">
                    <h6>Revenue</h6>
                    <h3>$${data.cards.revenue}</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 shadow">
                    <h6>Classes</h6>
                    <h3>${data.cards.classes}</h3>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card p-3 shadow">
                    <h6>Payments</h6>
                    <h3>${data.cards.paid}% Paid</h3>
                </div>
            </div>
        `;

        // Destroy old charts
        if(revenueChart) revenueChart.destroy();
        if(enrollChart) enrollChart.destroy();
        if(statusChart) statusChart.destroy();

        // Revenue Chart
        revenueChart = new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: data.revenue.map(r=>r.month),
                datasets: [{ data: data.revenue.map(r=>r.total) }]
            }
        });

        // Enrollment Chart
        enrollChart = new Chart(document.getElementById('enrollChart'), {
            type: 'bar',
            data: {
                labels: data.enroll.map(e=>e.course),
                datasets: [{ data: data.enroll.map(e=>e.total) }]
            }
        });

        // Status Chart
        statusChart = new Chart(document.getElementById('statusChart'), {
            type: 'pie',
            data: {
                labels: data.status.map(s=>s.status),
                datasets: [{ data: data.status.map(s=>s.total) }]
            }
        });

    });
}

// Events
document.getElementById('year').onchange = loadDashboard;
document.getElementById('course').onchange = loadDashboard;

// Initial load
loadDashboard();
</script>

</body>
</html>