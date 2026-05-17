<?php
include_once __DIR__ . '/../config/db.php';

// 1. Revenue per month
$revenueQuery = "
SELECT DATE_FORMAT(payment_date, '%Y-%m') as month,
SUM(amount) as total
FROM tblPayments
GROUP BY month
ORDER BY month ASC
";

$revenueResult = $conn->query($revenueQuery);
$revenueData = [];

while($row = $revenueResult->fetch_assoc()){
    $revenueData[] = $row;
}

// 2. Students per course
$courseQuery = "
SELECT c.course_name, COUNT(e.enrollment_id) as total
FROM tblCourses c
LEFT JOIN tblClasses cl ON c.course_id = cl.course_id
LEFT JOIN tblEnrollments e ON cl.class_id = e.class_id
GROUP BY c.course_id
";

$courseResult = $conn->query($courseQuery);
$courseData = [];

while($row = $courseResult->fetch_assoc()){
    $courseData[] = $row;
}

// 3. Payment status
$statusQuery = "
SELECT status, COUNT(*) as total
FROM tblPayments
GROUP BY status
";

$statusResult = $conn->query($statusQuery);
$statusData = [];

while($row = $statusResult->fetch_assoc()){
    $statusData[] = $row;
}

// Return JSON
echo json_encode([
    "revenue" => $revenueData,
    "courses" => $courseData,
    "status" => $statusData
]);