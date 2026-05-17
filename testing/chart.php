<!DOCTYPE html>
<html>
<head>
<title>School Dashboard</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body{
    font-family: Arial;
    background:#f4f6f9;
    margin:20px;
}

.dashboard{
    display:grid;
    grid-template-columns: repeat(4,1fr);
    gap:20px;
}

.card{
    background:white;
    padding:20px;
    border-radius:10px;
    box-shadow:0 2px 5px rgba(0,0,0,0.1);
    text-align:center;
}

.chart-grid{
    margin-top:30px;
    display:grid;
    grid-template-columns: repeat(2,1fr);
    gap:30px;
}
</style>
</head>

<body>

<h2>School Dashboard</h2>

<!-- Stat Cards -->

<div class="dashboard">

<div class="card">
<h3>500</h3>
<p>Total Students</p>
</div>

<div class="card">
<h3>40</h3>
<p>Total Teachers</p>
</div>

<div class="card">
<h3>20</h3>
<p>Total Staff</p>
</div>

<div class="card">
<h3>650</h3>
<p>Total Enrollment</p>
</div>

</div>


<!-- Charts -->

<div class="chart-grid">

<div class="card">
<canvas id="studentsChart"></canvas>
</div>

<div class="card">
<canvas id="attendanceChart"></canvas>
</div>

<div class="card">
<canvas id="enrollmentChart"></canvas>
</div>

<div class="card">
<canvas id="invoiceChart"></canvas>
</div>

</div>


<script>

//// Students Per Grade

new Chart(document.getElementById("studentsChart"),{
type:"bar",
data:{
labels:["Grade 1","Grade 2","Grade 3","Grade 4"],
datasets:[{
label:"Students",
data:[120,100,80,70]
}]
}
});

//// Attendance Trend

new Chart(document.getElementById("attendanceChart"),{
type:"line",
data:{
labels:["Mon","Tue","Wed","Thu","Fri"],
datasets:[{
label:"Attendance %",
data:[90,85,88,92,87],
fill:false,
tension:0.4
}]
}
});

//// Enrollment By Course
// doughnut
new Chart(document.getElementById("enrollmentChart"),{
type:"doughnut",
data:{
labels:["IT {data[1}","Business","Design"],
datasets:[{
data:[200,150,100]
}]
}
});

//// Invoice Status

new Chart(document.getElementById("invoiceChart"),{
type:"pie",
data:{
labels:["Paid","Not Paid"],
datasets:[{
data:[320,80]
}]
}
});

</script>

</body>
</html>