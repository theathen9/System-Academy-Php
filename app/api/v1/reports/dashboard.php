<?php

session_start();

header('Content-Type: application/json');

require_once '../../../../config/bootstrap.php';

try {

    $data = ReportService::getDashboardStats();

    echo json_encode([
        'success' => true,
        'data' => $data
    ]);

} catch (Throwable $e) {

    http_response_code(500);

    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}