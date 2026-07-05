<?php
date_default_timezone_set('Asia/Phnom_Penh');

include_once __DIR__ . '/../../../config/bootstrap.php';

header('Content-Type: application/json');

$classId = $_POST['class_id'] ?? null;
$status  = $_POST['status'] ?? null;

if (!$classId || !$status) {
    echo json_encode([
        'success' => false,
        'message' => 'Missing data'
    ]);
    exit;
}

$db = new DB($conn);
$cache = new Cache();
$scheduleCRUD = new ORM($db, 'tblClasses', 'class_id');

$result = $scheduleCRUD
    ->where('class_id', '=', $classId)
    ->update([
        'status' => $status
    ]);
$cache->clearByPrefix('schedules_list_');


echo json_encode([
    'success' => true
]);
