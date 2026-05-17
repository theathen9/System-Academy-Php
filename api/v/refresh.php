<?php
header("Content-Type: application/json");

include_once __DIR__ . "/../../config/db.php";
include_once __DIR__ . "/../v1/auth.php";

$userId = authenticate();

echo json_encode([
    "message" => "Token refreshed",
    "user_id" => $userId
]);