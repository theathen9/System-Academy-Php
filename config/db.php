<?php
// ./config/db.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Database credentials (replace with your actual values)
$servername = "dpg-d953l9kvikkc73d87fo0-a";   // Hosting mySQL
$username   = "systemacademy_user";        // Your DB username
$password   = "nvO3JsebMJWRG0U0rJ3MU3oGNsPsy35J";            // Your DB password            
$db         = "systemacademy";   // Your DB name
// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

// Optional: Set charset to UTF-8 (recommended)
$conn->set_charset("utf8");
