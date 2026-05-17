<?php
require_once '../config/db.php';

header("Content-Type: application/json");

// 📥 Get input
$data = json_decode(file_get_contents("php://input"), true);

$email    = $data['email'] ?? '';
$password = $data['password'] ?? '';

if (!$email || !$password) {
    echo json_encode(["status" => "error", "message" => "Email and password required"]);
    exit;
}

// 🔍 Find user
$stmt = $conn->prepare("SELECT * FROM tblUsers WHERE email = ? AND status = 1 LIMIT 1");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
    exit;
}

$user = $result->fetch_assoc();

// 🔐 Verify password
if (!password_verify($password, $user['password'])) {
    echo json_encode(["status" => "error", "message" => "Invalid credentials"]);
    exit;
}

// 🔑 Generate tokens
$accessToken  = bin2hex(random_bytes(32));
$refreshToken = bin2hex(random_bytes(64));

// 🔒 Hash tokens before storing
$accessTokenHash  = hash('sha256', $accessToken);
$refreshTokenHash = hash('sha256', $refreshToken);

// ⏳ Expiry
$accessExpiry  = date("Y-m-d H:i:s", strtotime("+15 minutes"));
$refreshExpiry = date("Y-m-d H:i:s", strtotime("+7 days"));

// 🌐 Device info
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
$ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

// 💾 Store token in tblUserTokens
$stmt = $conn->prepare("
    INSERT INTO tblUserTokens 
    (user_id, access_token, access_expiry, refresh_token, refresh_expiry, user_agent, ip_address)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "issssss",
    $user['user_id'],
    $accessTokenHash,
    $accessExpiry,
    $refreshTokenHash,
    $refreshExpiry,
    $userAgent,
    $ipAddress
);

$stmt->execute();

// 🕒 Update last login
$update = $conn->prepare("UPDATE tblUsers SET last_login = NOW() WHERE user_id = ?");
$update->bind_param("i", $user['user_id']);
$update->execute();

// 📤 Response
echo json_encode([
    "status" => "success",
    "message" => "Login successful",
    "data" => [
        "user" => [
            "user_id" => $user['user_id'],
            "username" => $user['username'],
            "email" => $user['email'],
            "role_id" => $user['role_id']
        ],
        "tokens" => [
            "access_token" => $accessToken,
            "access_expiry" => $accessExpiry,
            "refresh_token" => $refreshToken,
            "refresh_expiry" => $refreshExpiry
        ]
    ]
]);