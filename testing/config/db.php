<?php
// ./config/db.php
// Database credentials (replace with your actual values)
$servername = "localhost";   // Hosting mySQL
$username   = "root";        // Your DB username
$password   = "";            // Your DB password
$db         = "systemacademy";   // Your DB name
// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}

// Optional: Set charset to UTF-8 (recommended)
$conn->set_charset("utf8");
