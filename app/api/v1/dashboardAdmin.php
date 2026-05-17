<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../../config/bootstrap.php';

$db = new DB($conn);
$teacherCRUD = new ORM($db, "tblEmployees t", "employee_id");
$studentCRUD = new ORM($db, "tblStudents s", "student_id");
$enrollmentCRUD = new ORM($db, "tblEnrollments e", "enrollment_id");
$paymentCRUD    = new ORM($db, "tblPayments p", "payment_id");
$attendanceCRUD = new ORM($db, "tblAttendances a", "attendance_id");
$invoiceCRUD    = new ORM($db, "tblInvoices i", "invoice_id");
$classCRUD    = new ORM($db, "tblClasses c", "class_id");

$response = [];



try {

    $filter = $_GET['filter'] ?? 'this_month';
    $teacherId = $_GET['teacher_id'] ?? null;
    $classId = $_GET['class_id'] ?? null;
    $year = $_GET['year'] ?? date('Y');

    /*
    |--------------------------------------------------------------------------
    | DATE FILTER
    |--------------------------------------------------------------------------
    */

    $dateWhere = "";

    switch ($filter) {

        case 'daily':
            $dateWhere = "DATE(created_at) = CURDATE()";
            break;

        case 'this_month':
            $dateWhere = "
                MONTH(created_at)=MONTH(CURRENT_DATE())
                AND YEAR(created_at)=YEAR(CURRENT_DATE())
            ";
            break;

        case 'this_year':
            $dateWhere = "
                YEAR(created_at)=YEAR(CURRENT_DATE())
            ";
            break;

        case 'year':
            $dateWhere = "YEAR(created_at) = '$year'";
            break;
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL EMPLOYEES
    |--------------------------------------------------------------------------
    */

    $employees = $teacherCRUD
        ->select("COUNT(*) as total")
        ->first();

    /*
    |--------------------------------------------------------------------------
    | TOTAL TEACHERS
    |--------------------------------------------------------------------------
    */

    $teachers = $teacherCRUD
        ->select("COUNT(*) as total")
        ->join("tblDepartments d", "d.department_id = t.department_id")
        ->where("d.department_name", "=", "Teachers")
        ->first();

    /*
    |--------------------------------------------------------------------------
    | TOTAL TRAINERS
    |--------------------------------------------------------------------------
    */

    $trainers = $teacherCRUD
        ->select("COUNT(*) as total")
        ->join("tblDepartments d", "d.department_id = t.department_id")
        ->where("d.department_name", "=", "Trainers")
        ->first();

    /*
    |--------------------------------------------------------------------------
    | TOTAL ASSISTANTS
    |--------------------------------------------------------------------------
    */

    $assistants = $teacherCRUD
        ->select("COUNT(*) as total")
        ->join("tblDepartments d", "d.department_id = t.department_id")
        ->where("d.department_name", "=", "Assistants")
        ->first();

    /*
    |--------------------------------------------------------------------------
    | TOTAL STUDENTS
    |--------------------------------------------------------------------------
    */

    $studentsQuery = $studentCRUD
        ->select("COUNT(*) as total");

    if ($dateWhere) {
        $studentsQuery->whereRaw($dateWhere);
    }

    $students = $studentsQuery->first();

    /*
    |--------------------------------------------------------------------------
    | TOTAL CLASSES
    |--------------------------------------------------------------------------
    */

    $classes = $classCRUD
        ->select("COUNT(*) as total")
        ->first();

    /*
    |--------------------------------------------------------------------------
    | TOTAL COURSES
    |--------------------------------------------------------------------------
    */

    $courses = $classCRUD
        ->select("COUNT(DISTINCT course_id) as total")
        ->first();

    /*
    |--------------------------------------------------------------------------
    | MONTHLY INCOME
    |--------------------------------------------------------------------------
    */

    $income = $paymentCRUD
        ->select("IFNULL(SUM(amount),0) as total")
        ->whereRaw("
            MONTH(payment_date)=MONTH(CURRENT_DATE())
            AND YEAR(payment_date)=YEAR(CURRENT_DATE())
        ")
        ->first();

    /*
    |--------------------------------------------------------------------------
    | ATTENDANCE SUMMARY
    |--------------------------------------------------------------------------
    */

    $attendance = $attendanceCRUD
        ->select("
            SUM(CASE WHEN status='present' THEN 1 ELSE 0 END) as present,
            SUM(CASE WHEN status='absent' THEN 1 ELSE 0 END) as absent,
            SUM(CASE WHEN status='late' THEN 1 ELSE 0 END) as late
        ")
        ->first();

    /*
    |--------------------------------------------------------------------------
    | RECENT PAYMENTS
    |--------------------------------------------------------------------------
    */

    $recentPayments = $paymentCRUD
        ->select("
            p.payment_id,
            concat(s.first_name_kh, ' ', s.last_name_kh) as student_name,
            p.amount,
            p.payment_date
        ")
        ->join("tblInvoices i", "i.invoice_id = p.invoice_id")
        ->join("tblStudents s", "s.student_id = i.student_id")
        ->orderBy("p.payment_id", "DESC")
        ->limit(10)
        ->get();

    /*
    |--------------------------------------------------------------------------
    | RECENT ENROLLMENTS
    |--------------------------------------------------------------------------
    */

    $recentEnrollments = $enrollmentCRUD
        ->select("
            e.enrollment_id,
            concat(s.first_name_kh, ' ', s.last_name_kh) as student_name,
            c.class_name,
            e.created_at
        ")
        ->join("tblStudents s", "s.student_id = e.student_id")
        ->join("tblClasses c", "c.class_id = e.class_id")
        ->orderBy("e.enrollment_id", "DESC")
        ->limit(10)
        ->get();

    /*
    |--------------------------------------------------------------------------
    | INCOME CHART
    |--------------------------------------------------------------------------
    */

    $incomeChart = $paymentCRUD
        ->select("
            DATE(payment_date) as day,
            SUM(amount) as total
        ")
        ->groupBy("DATE(payment_date)")
        ->orderBy("DATE(payment_date)", "ASC")
        ->get();

    /*
    |--------------------------------------------------------------------------
    | RESPONSE
    |--------------------------------------------------------------------------
    */

    $response = [

        "success" => true,

        "filters" => [
            "teacher_id" => $teacherId,
            "class_id" => $classId,
            "filter" => $filter,
            "year" => $year
        ],

        "stats" => [

            "employees" => (int)$employees['total'],
            "teachers" => (int)$teachers['total'],
            "trainers" => (int)$trainers['total'],
            "assistants" => (int)$assistants['total'],
            "students" => (int)$students['total'],
            "classes" => (int)$classes['total'],
            "courses" => (int)$courses['total'],
            "monthly_income" => (float)$income['total']

        ],

        "attendance_summary" => [
            "present" => (int)$attendance['present'],
            "absent" => (int)$attendance['absent'],
            "late" => (int)$attendance['late']
        ],

        "recent_payments" => $recentPayments,

        "recent_enrollments" => $recentEnrollments,

        "charts" => [
            "income_chart" => $incomeChart
        ]
    ];

} catch (Exception $e) {

    $response = [
        "success" => false,
        "message" => $e->getMessage()
    ];
}

echo json_encode($response, JSON_PRETTY_PRINT);