<?php

session_start();

header('Content-Type: application/json');

require_once '../../../../config/bootstrap.php';

echo json_encode([
    'success' => true,
    'data' => ReportService::getRecentReports()
]);