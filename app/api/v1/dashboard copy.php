<?php
// ./admin/api/dashboard.php

ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

date_default_timezone_set('Asia/Phnom_Penh');

include_once __DIR__ . '/../../../config/bootstrap.php';

/* =========================
   AUTH
========================= */
if (!isset($_COOKIE['c_user']) || empty($_COOKIE['c_user'])) {

    http_response_code(401);

    echo json_encode([
        "success" => false,
        "message" => "Unauthorized"
    ]);

    exit;
}

/* =========================
   ORM MODELS
========================= */
$db = new DB($conn);
$teacherCRUD = new ORM($db, "tblEmployees t", "employee_id");
$studentCRUD = new ORM($db, "tblStudents s", "student_id");
$enrollmentCRUD = new ORM($db, "tblEnrollments e", "enrollment_id");
$paymentCRUD    = new ORM($db, "tblPayments p", "payment_id");
$attendanceCRUD = new ORM($db, "tblAttendances a", "attendance_id");
$invoiceCRUD    = new ORM($db, "tblInvoices i", "invoice_id");
$classCRUD    = new ORM($db, "tblClasses c", "class_id");

/* =========================
   INPUTS
========================= */
$filterType    = $_GET['filterType'] ?? 'today';
$selectedYear  = $_GET['selectYear'] ?? null;
$selectedClass = $_GET['selectClass'] ?? 'allClasses';
$teacher = $_GET['teacher'] ?? 'allTeachers';

/* =========================
   HELPERS
========================= */
function safeDate($date)
{
    return preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)
        ? $date
        : date('Y-m-d');
}

function resolveDateRange($filterType, $selectedYear)
{
    $today = date('Y-m-d');

    if ($selectedYear && $selectedYear !== 'allYear') {
        return [
            $selectedYear . '-01-01',
            $selectedYear . '-12-31'
        ];
    }

    switch ($filterType) {

        case 'today':
            return [$today, $today];

        case 'yesterday':
            $y = date('Y-m-d', strtotime('-1 day'));
            return [$y, $y];

        case 'last7days':
            return [
                date('Y-m-d', strtotime('-6 days')),
                $today
            ];

        case 'thisMonth':
            return [
                date('Y-m-01'),
                $today
            ];

        case 'lastMonth':
            return [
                date('Y-m-01', strtotime('first day of last month')),
                date('Y-m-t', strtotime('last day of last month'))
            ];

        case 'thisYear':
            return [
                date('Y-01-01'),
                $today
            ];

        case 'lastYear':
            return [
                date('Y-01-01', strtotime('-1 year')),
                date('Y-12-31', strtotime('-1 year'))
            ];

        case 'all':
            return [
                '2000-01-01',
                $today
            ];

        case 'custom':
        default:

            $s = safeDate($_GET['startDate'] ?? $today);
            $e = safeDate($_GET['endDate'] ?? $today);

            if ($s > $e) {
                [$s, $e] = [$e, $s];
            }

            return [$s, $e];
    }
}

function fillMissingDates($start, $end, $dataRows, $isMonthly = false)
{
    $map = [];

    foreach ($dataRows as $row) {
        $map[$row['d']] = (int)$row['total'];
    }

    $labels = [];
    $data   = [];

    $current = strtotime($start);
    $endTime = strtotime($end);

    while ($current <= $endTime) {

        if ($isMonthly) {

            $key = date('Y-m', $current);

            $labels[] = date('M Y', $current);
            $data[]   = $map[$key] ?? 0;

            $current = strtotime("+1 month", $current);

        } else {

            $key = date('Y-m-d', $current);

            $labels[] = date('M d', $current);
            $data[]   = $map[$key] ?? 0;

            $current = strtotime("+1 day", $current);
        }
    }

    return [$labels, $data];
}

function fillAttendanceDates($start, $end, $rows)
{
    $map = [];

    foreach ($rows as $r) {

        $map[$r['d']] = [
            'present' => (int)$r['present'],
            'absent'  => (int)$r['absent'],
            'late'    => (int)$r['late']
        ];
    }

    $labels  = [];
    $present = [];
    $absent  = [];
    $late    = [];

    $current = strtotime($start);
    $endTime = strtotime($end);

    while ($current <= $endTime) {

        $key = date('Y-m-d', $current);

        $labels[]  = date('M d', $current);

        $present[] = $map[$key]['present'] ?? 0;
        $absent[]  = $map[$key]['absent'] ?? 0;
        $late[]    = $map[$key]['late'] ?? 0;

        $current = strtotime("+1 day", $current);
    }

    return [$labels, $present, $absent, $late];
}

[$startDate, $endDate] = resolveDateRange(
    $filterType,
    $selectedYear
);
/* =========================
   TOTAL Cards
========================= */
/* =========================
   TOTAL STUDENTS
========================= */

$totalStudent = $studentCRUD
    ->select("COUNT(*) as total")
    ->first();

$totalStudentCard = (int)($totalStudent['total'] ?? 0);

/* =========================
   TOTAL Classes
========================= */

$totalClass = $classCRUD
    ->select("COUNT(*) as total")
    // ->from("tblEnrollments e")
    ->first();

$totalClassCard = (int)($totalClass['total'] ?? 0);

/* =========================
   TOTAL Teachers
========================= */

$totalTeacher = $teacherCRUD
    ->select("COUNT(*) as total")
    ->join("tblDepartments d", "t.department_id = d.department_id")
    ->where("d.department_name", "=", "Teacher") 
    // ->from("tblEnrollments e")
    ->first();

$totalTeacherCard = (int)($totalTeacher['total'] ?? 0);

/* =========================
   TOTAL REVENUE
========================= */
$revenue = $paymentCRUD
    ->select("IFNULL(SUM(p.amount),0) as revenue")
    ->first();
   


$revenueCard = round((float)($revenue['revenue'] ?? 0), 2);

/* =========================
   ATTENDANCE OVERVIEW
========================= */
$attendanceOverview = $attendanceCRUD
    ->select("
        SUM(a.status='Present') as present,
        SUM(a.status='Absent') as absent,
        SUM(a.status='Late') as late
    ")
    ->from("tblAttendances a")
    ->join(
        "tblEnrollments e",
        "a.enrollment_id = e.enrollment_id"
    )
    ->where("a.attendance_date", ">=", $startDate)
    ->where("a.attendance_date", "<", date('Y-m-d', strtotime($endDate . ' +1 day')));

if ($selectedClass !== 'allClasses') {
    $attendanceOverview->where("e.class_id", "=", (int)$selectedClass);
}

$attendanceOverview = $attendanceOverview->first();

/* =========================
   ENROLLMENT OVERVIEW
========================= */
$enrollmentOverview = $invoiceCRUD
    ->select("
        SUM(inv.status='Paid') as paid,
        SUM(inv.status='Unpaid') as unpaid
    ")
    ->from("tblInvoices inv")
    ->join(
        "tblEnrollments e",
        "inv.student_id = e.enrollment_id"
    )
    ->where("inv.invoice_date", ">=", $startDate)
    ->where("inv.invoice_date", "<", date('Y-m-d', strtotime($endDate . ' +1 day')));

if ($selectedClass !== 'allClasses') {
    $enrollmentOverview->where("e.class_id", "=", (int)$selectedClass);
}

$enrollmentOverview = $enrollmentOverview->first();

/* =========================
   ENROLLMENT CHART
========================= */
$enrollChart = $enrollmentCRUD
    ->select("
        DATE(e.created_at) as d,
        COUNT(*) as total
    ")
    ->from("tblEnrollments e")
    ->where("e.created_at", ">=", $startDate)
    ->where("e.created_at", "<", date('Y-m-d', strtotime($endDate . ' +1 day')))
    ->groupBy("d")
    ->orderBy("d", "ASC");

if ($selectedClass !== 'allClasses') {
    $enrollChart->where("e.class_id", "=", (int)$selectedClass);
}

$enrollRows = $enrollChart->get();

$daysDiff = (
    strtotime($endDate) - strtotime($startDate)
) / (60 * 60 * 24) + 1;

$isMonthly = $daysDiff > 30;

list($labels, $monthly) = fillMissingDates(
    $startDate,
    $endDate,
    $enrollRows,
    $isMonthly
);

/* =========================
   ATTENDANCE CHART
========================= */
$attendanceChart = $attendanceCRUD
    ->select("
        DATE(a.attendance_date) as d,
        SUM(a.status='Present') as present,
        SUM(a.status='Absent') as absent,
        SUM(a.status='Late') as late
    ")
    ->from("tblAttendances a")
    ->join(
        "tblEnrollments e",
        "a.enrollment_id = e.enrollment_id"
    )
    ->where("a.attendance_date", ">=", $startDate)
    ->where("a.attendance_date", "<", date('Y-m-d', strtotime($endDate . ' +1 day')))
    ->groupBy("d")
    ->orderBy("d", "ASC");

if ($selectedClass !== 'allClasses') {
    $attendanceChart->where("e.class_id", "=", (int)$selectedClass);
}

$attendanceRows = $attendanceChart->get();

list(
    $attendanceLabels,
    $presentData,
    $absentData,
    $lateData
) = fillAttendanceDates(
    $startDate,
    $endDate,
    $attendanceRows
);

/* =========================
   RESPONSE
========================= */
echo json_encode([

    "studentCard" => $totalStudentCard,
    "classCard" => $totalClassCard,
    "teacherCard" => $totalTeacherCard,

    "revenueCard" => $revenueCard,

    "attendanceOverview" => [
        "present" => (int)($attendanceOverview['present'] ?? 0),
        "absent"  => (int)($attendanceOverview['absent'] ?? 0),
        "late"    => (int)($attendanceOverview['late'] ?? 0),
    ],

    "enrollmentOverview" => [
        "paid"   => (int)($enrollmentOverview['paid'] ?? 0),
        "unpaid" => (int)($enrollmentOverview['unpaid'] ?? 0)
    ],

    "enrollChart" => [
        "labels" => $labels,
        "data"   => $monthly
    ],

    "attendanceChart" => [
        "labels"  => $attendanceLabels,
        "present" => $presentData,
        "absent"  => $absentData,
        "late"    => $lateData
    ]
],JSON_PRETTY_PRINT);