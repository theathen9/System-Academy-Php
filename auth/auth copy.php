<?php
session_start();
include_once __DIR__ . '/../config/app.php';

function checkAuth()
{
    // If session exists → OK
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        return;
    }

    // Try cookie login
    $userId = verifyUserCookie();
    if ($userId) {
        // Fetch user info from DB
        global $conn; // make sure your DB connection is available
        $stmt = $conn->prepare("SELECT user_id, reference_id, reference_type, role_id FROM tblUsers WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = (int)$user['user_id'];           // tblUsers id
            $_SESSION['reference_id'] = (int)$user['reference_id']; // employee/student id
            $_SESSION['reference_type'] = $user['reference_type'];  // Employee/Student
            $_SESSION['role_id'] = (int)$user['role_id'];
            return;
        }
    }

    // Not authenticated
    header("Location: " . BASE_URL . "/auth/signin.php");
    exit();
}

/**
 * Role-based access control
 */
// function authorizeRole($roles = [])
// {
//     if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles)) {
//         // http_response_code(403);
//         // die("🚫 Access Denied");
//         header("Location: " . BASE_URL . "/auth/signin.php");
//         exit();
//     }
// }

function authorizeRole($roles = [])
{
    if (!is_array($roles)) {
        $roles = [$roles];
    }

    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles)) {
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
    $headers = function_exists('getallheaders') ? getallheaders() : [];

    $token = $headers['X-CSRF-TOKEN'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;

    if (!$token || !isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        http_response_code(403);
        echo json_encode(["message" => "Invalid CSRF token"]);
        exit();
    }
}


function verifyUserCookie()
{
    if (!isset($_COOKIE['c_user'])) return false;

    $parts = explode('.', $_COOKIE['c_user']);

    if (count($parts) !== 3) return false;

    list($userId, $expiry, $signature) = $parts;

    // ✅ Check expiration
    if ($expiry < time()) {
        return false;
    }

    // ✅ Recreate signature
    $data = $userId . '.' . $expiry;
    $expected = hash_hmac('sha256', $data, APP_SECRET);

    if (!hash_equals($expected, $signature)) {
        return false;
    }

    return (int)$userId;
}