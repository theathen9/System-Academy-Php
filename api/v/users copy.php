<?php

session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../config/bootstrap.php';


if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized"
    ]);
    exit;
}

$user_id = $_SESSION['user_id'];

$sql = "
SELECT 
    u.user_id,
    u.username,
    u.email AS login_email,
    u.reference_type,

    e.employee_id,
    e.first_name_kh,
    e.last_name_kh,
    e.first_name_en,
    e.last_name_en,
    e.gender,
    e.phone1,
    e.phone2,
    e.email AS employee_email,
    e.profile_image,
    e.status,
    e.hired_at

FROM tblUsers u

LEFT JOIN tblEmployees e 
ON (
    u.reference_id = e.employee_id
    AND u.reference_type = 'Employee'
)

WHERE u.user_id = ?
LIMIT 1
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $user_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);

echo json_encode([
    "success" => true,
    "data" => $user
]);