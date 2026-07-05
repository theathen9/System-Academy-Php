<?php

header('Content-Type: application/json');

require_once __DIR__ . '/../../../config/bootstrap.php';

if (!isset($_COOKIE['refresh_token']) || empty($_COOKIE['refresh_token'])) {
    // echo json_encode(["error" => "Session expired"]);
    http_response_code(401);
    exit;
}

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
    r.role_name,

    e.employee_id,
    TRIM(CONCAT(
    COALESCE(e.first_name_kh, ''),
    ' ',
    COALESCE(e.last_name_kh, '')
)) AS full_name_kh,

TRIM(CONCAT(
    COALESCE(e.first_name_en, ''),
    ' ',
    COALESCE(e.last_name_en, '')
)) AS full_name_en,
 CASE 
    WHEN e.first_name_kh IS NOT NULL AND e.first_name_kh != ''
        THEN TRIM(CONCAT(e.first_name_kh, ' ', e.last_name_kh))
    WHEN e.first_name_en IS NOT NULL AND e.first_name_en != ''
        THEN TRIM(CONCAT(e.first_name_en, ' ', e.last_name_en))
    ELSE u.username
    END AS display_name,
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
LEFT JOIN tblRoles r
ON u.role_id = r.role_id
WHERE u.user_id = ?
LIMIT 1
";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param($stmt, "i", $user_id);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

$user = mysqli_fetch_assoc($result);

// echo json_encode([
//     "success" => true,
//     "data" => $user
// ]);
echo json_encode([
    "success" => true,
    "data" => [
        ...$user,
        "display_name" => $user['full_name_kh']
            ?? $user['full_name_en']
            ?? $user['username']
    ]
]);
