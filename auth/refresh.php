<?php
// ./auth/refresh.php
date_default_timezone_set('Asia/Phnom_Penh');

include "../config/db.php";
include "../config/app.php";

header('Content-Type: application/json');

$response = [
    "success" => false,
    "message" => "Unauthorized"
];

// ❌ No refresh token
if (empty($_COOKIE['refresh_token'])) {
    echo json_encode($response);
    exit;
}

$refreshToken = $_COOKIE['refresh_token'];
$hashedRefresh = hash('sha256', $refreshToken);

// 🔍 Find user by refresh token
$stmt = $conn->prepare("
    SELECT * FROM tblUsers 
    WHERE refresh_token = ?
    LIMIT 1
");

$stmt->bind_param("s", $hashedRefresh);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || !$row = $result->fetch_assoc()) {
    echo json_encode($response);
    exit;
}

// ⏳ Check expiry
if (strtotime($row['refresh_expiry']) < time()) {
    echo json_encode([
        "success" => false,
        "message" => "Refresh token expired"
    ]);
    exit;
}

$userId = $row['user_id'];

// 🔐 Generate new tokens
$newAccessToken  = bin2hex(random_bytes(32));
$newRefreshToken = bin2hex(random_bytes(64));

$hashedAccess  = hash('sha256', $newAccessToken);
$hashedRefresh = hash('sha256', $newRefreshToken);

$accessExpiry  = date('Y-m-d H:i:s', strtotime('+15 minutes'));
$refreshExpiry = date('Y-m-d H:i:s', strtotime('+7 days'));

// 💾 Update DB
$update = $conn->prepare("
    UPDATE tblUsers 
    SET access_token=?, refresh_token=?, access_expiry=?, refresh_expiry=? 
    WHERE user_id=?
");

$update->bind_param(
    "ssssi",
    $hashedAccess,
    $hashedRefresh,
    $accessExpiry,
    $refreshExpiry,
    $userId
);

$update->execute();

// 🍪 Secure cookie settings
$secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

// ✅ Set new cookies (RAW tokens)
setcookie("access_token", $newAccessToken, [
    'expires' => strtotime($accessExpiry),
    'path' => '/',
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Strict'
]);

setcookie("refresh_token", $newRefreshToken, [
    'expires' => strtotime($refreshExpiry),
    'path' => '/',
    'secure' => $secure,
    'httponly' => true,
    'samesite' => 'Strict'
]);

echo json_encode([
    "success" => true,
    "message" => "Token refreshed"
]);


// How to use it (Frontend / AJAX)

// fetch('/auth/refresh.php', {
//     method: 'POST',
//     credentials: 'include'
// })
// .then(res => res.json())
// .then(data => {
//     if (data.success) {
//         // retry original request
//     } else {
//         // redirect to login
//         window.location.href = '/auth/signin.php';
//     }
// });