<?php
// ./config/db.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = "dpg-d953l9kvikkc73d87fo0-a";
$port = "5432";
$dbname = "systemacademy";
$user = "systemacademy_user";
$password = "nvO3JsebMJWRG0U0rJ3MU3oGNsPsy35J";

try {
    $conn = new PDO(
        "pgsql:host=$host;port=$port;dbname=$dbname",
        $user,
        $password
    );

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}
