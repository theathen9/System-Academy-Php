<?php
$host = "127.0.0.1";
$db   = "systemacademy";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = $conn->query("SELECT 
    s.student_id,
    CONCAT(s.fst_name,' ',s.lst_name) AS student_name,
    COUNT(a.attendance_id) AS total_days,
    SUM(a.status='Present') AS present_days,
    ROUND(SUM(a.status='Present') / COUNT(*) * 100, 2) AS attendance_percent
FROM tblAttendance a
JOIN tblStudent s ON a.student_id = s.student_id
GROUP BY s.student_id");

$student_id = [];
$student_name = [];
$total_days = [];
$present_days = [];
$attendance_percent = [];

while ($row = $query->fetch_assoc()) {
    $student_id[] = $row['student_id'];
    $student_name[] = $row['student_name'];
    $total_days[] = (int)$row['total_days'];
    $present_days[] = (int)$row['present_days'];
    $attendance_percent[] = (float)$row['attendance_percent'];
}
?>

<!-- Small Card Wrapper -->
<div style="
    width: 250px; 
    padding: 15px; 
    border-radius: 10px; 
    box-shadow: 0 2px 6px rgba(0,0,0,0.15); 
    text-align: center;
    font-family: Arial, sans-serif;
">
    <h4 style="margin-bottom: 10px;">Attendance %</h4>
    <canvas id="myChart" width="200" height="200"></canvas>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const labels = <?php echo json_encode($student_name); ?>;
const attendancePercent = <?php echo json_encode($attendance_percent); ?>;

const data = {
    labels: labels,
    datasets: [{
        label: 'Attendance %',
        data: attendancePercent,
        backgroundColor: [
            'rgba(75, 192, 192, 0.7)',
            'rgba(153, 102, 255, 0.7)',
            'rgba(255, 159, 64, 0.7)',
            'rgba(255, 99, 132, 0.7)',
            'rgba(54, 162, 235, 0.7)',
            'rgba(201, 203, 207, 0.7)'
        ],
        borderColor: [
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)',
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(201, 203, 207, 1)'
        ],
        borderWidth: 1
    }]
};

const config = {
    type: 'doughnut',
    data: data,
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'right', labels: { boxWidth: 12, padding: 10 } },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.label + ': ' + context.raw + '%';
                    }
                }
            }
        }
    }
};

new Chart(document.getElementById('myChart'), config);
</script>
