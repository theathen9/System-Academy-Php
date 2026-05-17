<?php
// ./auth/auth.php
include_once __DIR__ . '/../config/app.php';
include_once __DIR__ . '/../config/db.php';

function checkAuth()
{
    global $conn;

    // 1. SESSION
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        return $_SESSION['user_id'];
    }

    // 2. COOKIE
    $userId = verifyUserCookie();

    if (!$userId) {
        return false; // ✅ FIX: no redirect here
    }

    // 3. LOAD USER
    session_regenerate_id(true);

    $stmt = $conn->prepare("
        SELECT user_id, reference_id, reference_type, role_id
        FROM tblUsers
        WHERE user_id = ?
        LIMIT 1
    ");

    $stmt->bind_param("i", $userId);

    if (!$stmt->execute()) {
        return false; // ✅ FIX
    }

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        return false; // ✅ FIX
    }

    // 4. SESSION
    $_SESSION['loggedin'] = true;
    $_SESSION['user_id'] = (int)$user['user_id'];
    $_SESSION['reference_id'] = (int)$user['reference_id'];
    $_SESSION['reference_type'] = $user['reference_type'];
    $_SESSION['role_id'] = (int)$user['role_id'];

    return (int)$user['user_id'];
}

function authorizeRole($roles = [])
{
    if (!is_array($roles)) {
        $roles = [$roles];
    }

    if (!isset($_SESSION['role_id']) || !in_array($_SESSION['role_id'], $roles)) {
        header("Location: " . BASE_URL . "/auth/signin.php");
        exit();
    }
}

/**
 * Permission check
 */
function hasPermission($permission)
{
    return isset($_SESSION['permissions']) && in_array($permission, $_SESSION['permissions']);
}

function verifyCSRF()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $token =
        $_POST['csrf_token'] ??
        $_SERVER['HTTP_X_CSRF_TOKEN'] ??
        null;

    if (
        !$token ||
        !isset($_SESSION['csrf_token']) ||
        !hash_equals($_SESSION['csrf_token'], $token)
    ) {
        // If normal form → redirect
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $_SESSION['error'] = "Invalid CSRF token";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // If AJAX → JSON
        http_response_code(403);
        echo json_encode(["message" => "Invalid CSRF token"]);
        exit;
    }
}


// function verifyUserCookie()
// {
//     global $conn;

//     if (!isset($_COOKIE['c_user'])) return false;

//     $parts = explode('.', $_COOKIE['c_user']);
//     if (count($parts) !== 2) return false;

//     list($userId, $signature) = $parts;

//     // Validate signature
//     $expected = hash_hmac('sha256', $userId, APP_SECRET);
//     if (!hash_equals($expected, $signature)) return false;

//     // Fetch from DB
//     $stmt = $conn->prepare("
//         SELECT access_token, access_expiry, refresh_token, refresh_expiry
//         FROM tblUsers
//         WHERE user_id = ?
//     ");
//     $stmt->bind_param("i", $userId);
//     $stmt->execute();
//     $res = $stmt->get_result()->fetch_assoc();

//     if (!$res) return false;

//     // 🔐 Check access token
//     if (isset($_COOKIE['access_token'])) {
//         $hashedAccess = hash('sha256', $_COOKIE['access_token']);

//         if (
//             hash_equals($res['access_token'], $hashedAccess) &&
//             strtotime($res['access_expiry']) >= time()
//         ) {
//             return (int)$userId;
//         }
//     }

//     // 🔁 Check refresh token
//     if (!isset($_COOKIE['refresh_token'])) return false;

//     $hashedRefresh = hash('sha256', $_COOKIE['refresh_token']);

//     if (
//         !hash_equals($res['refresh_token'], $hashedRefresh) ||
//         strtotime($res['refresh_expiry']) < time()
//     ) {
//         return false;
//     }

//     // 🔄 Generate new tokens
//     $newAccessToken = bin2hex(random_bytes(32));
//     $hashedAccessToken = hash('sha256', $newAccessToken);

//     $newRefreshToken = bin2hex(random_bytes(64));
//     $hashedRefreshToken = hash('sha256', $newRefreshToken);

//     $newAccessExpiry = date('Y-m-d H:i:s', strtotime('+30 minutes'));
//     $newRefreshExpiry = date('Y-m-d H:i:s', strtotime('+1 days'));

//     // Update DB
//     $update = $conn->prepare("
//         UPDATE tblUsers
//         SET access_token = ?, access_expiry = ?, refresh_token = ?, refresh_expiry = ?
//         WHERE user_id = ?
//     ");
//     $update->bind_param("ssssi", $hashedAccessToken, $newAccessExpiry, $hashedRefreshToken, $newRefreshExpiry, $userId);
//     $update->execute();

//     // Cookie settings
//     $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

//     $signature = hash_hmac('sha256', $userId, APP_SECRET);
//     $value = $userId . "." . $signature;

//     setcookie("c_user", $value, [
//         'expires' => strtotime($newRefreshExpiry),
//         'path' => '/',
//         'secure' => $secure,
//         'httponly' => true,
//         'samesite' => 'Strict'
//     ]);

//     setcookie("access_token", $newAccessToken, [
//         'expires' => strtotime($newAccessExpiry),
//         'path' => '/',
//         'secure' => $secure,
//         'httponly' => true,
//         'samesite' => 'Strict'
//     ]);

//     setcookie("refresh_token", $newRefreshToken, [
//         'expires' => strtotime($newRefreshExpiry),
//         'path' => '/',
//         'secure' => $secure,
//         'httponly' => true,
//         'samesite' => 'Strict'
//     ]);

//     return (int)$userId;
// }
// function verifyUserCookie()
// {
//     global $conn;

//     if (!isset($_COOKIE['c_user'])) return false;

//     $parts = explode('.', $_COOKIE['c_user']);
//     if (count($parts) !== 2) return false;

//     [$userId, $cookieSignature] = $parts;

//     // =========================
//     // 1. VERIFY SIGNATURE
//     // =========================
//     $expected = hash_hmac('sha256', $userId, APP_SECRET);
//     if (!hash_equals($expected, $cookieSignature)) return false;

//     // =========================
//     // 2. FETCH USER TOKENS
//     // =========================
//     $stmt = $conn->prepare("
//         SELECT access_token, access_expiry, refresh_token, refresh_expiry
//         FROM tblUsers
//         WHERE user_id = ?
//         LIMIT 1
//     ");

//     $stmt->bind_param("i", $userId);
//     $stmt->execute();
//     $res = $stmt->get_result()->fetch_assoc();

//     if (!$res) return false;

//     $now = time();

//     // =========================
//     // 3. ACCESS TOKEN VALID
//     // =========================
//     if (isset($_COOKIE['access_token'])) {

//         $hashedAccess = hash('sha256', $_COOKIE['access_token']);

//         if (
//             hash_equals($res['access_token'], $hashedAccess) &&
//             strtotime($res['access_expiry']) > $now
//         ) {
//             return (int)$userId;
//         }
//     }

//     // =========================
//     // 4. REFRESH TOKEN CHECK
//     // =========================
//     if (!isset($_COOKIE['refresh_token'])) return false;

//     $hashedRefresh = hash('sha256', $_COOKIE['refresh_token']);

//     if (!hash_equals($res['refresh_token'], $hashedRefresh)) {
//         return false;
//     }

//     if (strtotime($res['refresh_expiry']) < $now) {
//         return false;
//     }

//     // =========================
//     // 5. ROTATE TOKENS (SECURE)
//     // =========================

//     $newAccessToken = bin2hex(random_bytes(32));
//     $newRefreshToken = bin2hex(random_bytes(64));

//     $hashedAccessToken = hash('sha256', $newAccessToken);
//     $hashedRefreshToken = hash('sha256', $newRefreshToken);

//     $newAccessExpiry = date('Y-m-d H:i:s', strtotime('+30 minutes'));
//     $newRefreshExpiry = date('Y-m-d H:i:s', strtotime('+1 days'));

//     $update = $conn->prepare("
//         UPDATE tblUsers
//         SET access_token = ?,
//             access_expiry = ?,
//             refresh_token = ?,
//             refresh_expiry = ?
//         WHERE user_id = ?
//     ");

//     if (!$update) return false;

//     $update->bind_param(
//         "ssssi",
//         $hashedAccessToken,
//         $newAccessExpiry,
//         $hashedRefreshToken,
//         $newRefreshExpiry,
//         $userId
//     );

//     if (!$update->execute()) {
//         return false;
//     }

//     // =========================
//     // 6. SET NEW COOKIES
//     // =========================
//     $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

//     $newSignature = hash_hmac('sha256', $userId, APP_SECRET);
//     $cookieValue = $userId . "." . $newSignature;

//     setcookie("c_user", $cookieValue, [
//         'expires' => strtotime($newRefreshExpiry),
//         'path' => '/',
//         'secure' => $secure,
//         'httponly' => true,
//         'samesite' => 'Strict'
//     ]);

//     setcookie("access_token", $newAccessToken, [
//         'expires' => strtotime($newAccessExpiry),
//         'path' => '/',
//         'secure' => $secure,
//         'httponly' => true,
//         'samesite' => 'Strict'
//     ]);

//     setcookie("refresh_token", $newRefreshToken, [
//         'expires' => strtotime($newRefreshExpiry),
//         'path' => '/',
//         'secure' => $secure,
//         'httponly' => true,
//         'samesite' => 'Strict'
//     ]);

//     return (int)$userId;
// }


function verifyUserCookie()
{
    global $conn;

    // =========================
    // 0. BASIC COOKIE CHECK
    // =========================
    if (empty($_COOKIE['c_user'])) {
        return false;
    }

    $parts = explode('.', $_COOKIE['c_user']);
    if (count($parts) !== 2) {
        return false;
    }

    [$userId, $signature] = $parts;

    if (!ctype_digit($userId)) {
        return false;
    }

    $userId = (int)$userId;

    // =========================
    // 1. VERIFY SIGNATURE
    // =========================
    $expectedSignature = hash_hmac('sha256', $userId, APP_SECRET);

    if (!hash_equals($expectedSignature, $signature)) {
        return false;
    }

    // =========================
    // 2. LOAD USER TOKENS
    // =========================
    $stmt = $conn->prepare("
        SELECT access_token, access_expiry, refresh_token, refresh_expiry
        FROM tblUsers
        WHERE user_id = ?
        LIMIT 1
    ");

    if (!$stmt) return false;

    $stmt->bind_param("i", $userId);

    if (!$stmt->execute()) return false;

    $res = $stmt->get_result()->fetch_assoc();

    if (!$res) return false;

    $now = time();

    // =========================
    // 3. VALID ACCESS TOKEN
    // =========================
    if (!empty($_COOKIE['access_token'])) {

        $hashedAccess = hash('sha256', $_COOKIE['access_token']);

        if (
            hash_equals($res['access_token'], $hashedAccess) &&
            strtotime($res['access_expiry']) > $now
        ) {
            return $userId;
        }
    }

    // =========================
    // 4. VALID REFRESH TOKEN
    // =========================
    if (empty($_COOKIE['refresh_token'])) {
        return false;
    }

    $hashedRefresh = hash('sha256', $_COOKIE['refresh_token']);

    if (!hash_equals($res['refresh_token'], $hashedRefresh)) {
        return false;
    }

    if (strtotime($res['refresh_expiry']) <= $now) {
        return false;
    }

    // =========================
    // 5. ROTATE TOKENS
    // =========================
    $newAccessToken  = bin2hex(random_bytes(32));
    $newRefreshToken = bin2hex(random_bytes(64));

    $hashedAccessToken  = hash('sha256', $newAccessToken);
    $hashedRefreshToken = hash('sha256', $newRefreshToken);

    $newAccessExpiry  = date('Y-m-d H:i:s', strtotime('+30 minutes'));
    $newRefreshExpiry = date('Y-m-d H:i:s', strtotime('+1 days'));

    $update = $conn->prepare("
        UPDATE tblUsers
        SET access_token = ?, 
            access_expiry = ?, 
            refresh_token = ?, 
            refresh_expiry = ?
        WHERE user_id = ?
    ");

    if (!$update) return false;

    $update->bind_param(
        "ssssi",
        $hashedAccessToken,
        $newAccessExpiry,
        $hashedRefreshToken,
        $newRefreshExpiry,
        $userId
    );

    if (!$update->execute()) {
        return false;
    }

    // =========================
    // 6. SET NEW COOKIES
    // =========================
    $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

    $newSignature = hash_hmac('sha256', $userId, APP_SECRET);
    $cookieValue = $userId . "." . $newSignature;

    $cookieOptions = [
        'path' => '/',
        'secure' => $isSecure,
        'httponly' => true,
        'samesite' => 'Lax' // safer for dev
    ];

    setcookie("c_user", $cookieValue, $cookieOptions + [
        'expires' => strtotime($newRefreshExpiry)
    ]);

    setcookie("access_token", $newAccessToken, $cookieOptions + [
        'expires' => strtotime($newAccessExpiry)
    ]);

    setcookie("refresh_token", $newRefreshToken, $cookieOptions + [
        'expires' => strtotime($newRefreshExpiry)
    ]);

    return $userId;
}
