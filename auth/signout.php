<?php
// ./auth/signout.php
include "../config/db.php";
session_start();

$userId = $_SESSION['user_id'] ?? null;

if ($userId) {
    $stmt = $conn->prepare("
        UPDATE tblUsers 
        SET last_login = NOW(), access_token=NULL, refresh_token=NULL, access_expiry=NULL, refresh_expiry=null, user_agent=null,ip_address=null
        WHERE user_id=?
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
}

// Destroy session
session_destroy();

// Delete cookies
setcookie("access_token", "", time() - 3600, "/");
setcookie("refresh_token", "", time() - 3600, "/");
setcookie("c_user", "", time() - 3600, "/");

header("Location: signin.php");
exit;