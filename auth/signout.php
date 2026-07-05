<?php
// ./auth/signout.php

require_once "../config/bootstrap.php";

$userId = $_SESSION['user_id'] ?? null;

if ($userId) {
    $stmt = $conn->prepare("
        UPDATE tblUsers
        SET
            last_login = NOW(),
            access_token = NULL,
            refresh_token = NULL,
            access_expiry = NULL,
            refresh_expiry = NULL,
            user_agent = NULL,
            ip_address = NULL
        WHERE user_id = ?
    ");

    $stmt->bind_param("i", $userId);
    $stmt->execute();
}

// Clear all session data
$_SESSION = [];

// Delete session cookie
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();

    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Destroy session
session_destroy();

// Delete auth cookies
setcookie("access_token", "", time() - 3600, "/");
setcookie("refresh_token", "", time() - 3600, "/");
setcookie("c_user", "", time() - 3600, "/");
?>

<script>
    localStorage.removeItem("user");
    window.location.replace("signin.php");
</script>