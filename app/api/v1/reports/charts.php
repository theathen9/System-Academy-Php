<?php

session_start();

header('Content-Type: application/json');

require_once '../../../../config/bootstrap.php';

echo json_encode([
    'success' => true,
    'data' => [
        'enrollment' => ReportService::getEnrollmentTrend(),
        'revenue' => ReportService::getRevenueTrend(),
        'attendance' => ReportService::getAttendanceTrend()
    ]
]);