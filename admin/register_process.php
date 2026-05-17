<?php
// register_process.php
header('Content-Type: application/json');
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../bootstrap/bootstrap.php';

// include_once __DIR__ . '/../data/dbShemaData.php';
// include_once __DIR__ . '/../data/functionData.php';
include_once __DIR__ . '/../admin/register.php';



$db = new DB($conn);

$staffCRUD = new ORM($db, "tblEmployees", "employee_id");
$studentCRUD = new ORM($db, "tblStudents", "student_id");
$enrollCRUD = new ORM($db, "tblEnrollments", "enrollment_id");
$paymentCRUD = new ORM($db, "tblPayments", "payment_id");


// $createdByUserId = $_SESSION['user_id'];      // tblUsers id
// $createdByReferenceId = $_SESSION['reference_id']; // employee/student id
function uploadPhoto($fileInputName, $uploadDir = '../uploads/photos/')
{
    if (!isset($_FILES[$fileInputName]) || $_FILES[$fileInputName]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    $fileTmp  = $_FILES[$fileInputName]['tmp_name'];
    $fileName = uniqid() . '_' . basename($_FILES[$fileInputName]['name']);
    $filePath = $uploadDir . $fileName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    return move_uploaded_file($fileTmp, $filePath) ? $fileName : null;
}

function post($key, $default = null)
{
    return $_POST[$key] ?? $default;
}

function generateStudentCode($conn)
{
    $year = date("Y");

    $res = $conn->query("
        SELECT COUNT(*) as total 
        FROM tblStudents 
        WHERE YEAR(created_at) = $year
    ");

    $count = $res->fetch_assoc()['total'] + 1;

    return "STU-$year-" . str_pad($count, 5, '0', STR_PAD_LEFT);
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid request']);
    exit;
}

$action = post('action');
$step   = post('step');

verifyCSRF();

if ($action === 'register_staff') {

    try {
        $dob = DateTime::createFromFormat('d-m-Y', post('dob'));
        $photo = uploadPhoto('staff_photo');

        $data = [
            
            'fst_name_kh' => post('fst_name_kh'),
            // 'middle_name' => post('middle_name', ''),
            'lst_name_kh' => post('lst_name_kh'),

            'fst_name_eng' => post('fst_name_eng'),
            // 'middle_name_eng' => post('middle_name_eng', ''),
            'lst_name_eng' => post('lst_name_eng'),

            'dob' => $dob ? $dob->format('Y-m-d') : null,

            'gender' => post('gender'),
            'department_id' => post('department_id'),

            'birth_province' => post('birth_addr_province'),
            'birth_district' => post('birth_addr_district'),
            'birth_commune' => post('birth_addr_commune'),
            'birth_village' => post('birth_addr_village'),

            'curr_addr_province' => post('curr_addr_province'),
            'curr_addr_district' => post('curr_addr_district'),
            'curr_addr_commune' => post('curr_addr_commune'),
            'curr_addr_village' => post('curr_addr_village'),

            'phone1' => post('phone1'),
            'phone2' => post('phone2', ''),
            'email' => post('email', ''),

            'profile_image' => $photo
        ];

        $id = $staffCRUD->insert($data);

        echo json_encode(['success' => true, 'id' => $id]);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }

    exit;
}

// ==========================================================
// 🔥 STUDENT STEP 1 (CREATE DRAFT)
// ==========================================================
// ==========================================================
// ✅ STEP 1: CREATE STUDENT
// ==========================================================
if ($action === 'register_student' && $step === 1) {

    $conn->begin_transaction();

    try {

        $photo = uploadPhoto('student_photo');
        $studentCode = generateStudentCode($conn);

        $studentId = $studentCRUD->insert([
            'student_code' => $studentCode,
            'created_by'   => post('created_by'),
            'fst_name_kh' => post('student_fst_name'),
            'lst_name_kh' => post('student_lst_name'),

            'fst_name_eng' => post('student_fst_name_en'),
            'lst_name_eng' => post('student_lst_name_en'),

            'dob' => post('student_dob'),
            'gender' => post('student_gender'),
            'academic_year' => post('student_academic_year'),

            'birth_province' => post('student_birth_addr_province'),
            'birth_district' => post('student_birth_addr_district'),
            'birth_commune' => post('student_birth_addr_commune'),
            'birth_village' => post('student_birth_addr_village'),

            'curr_addr_province' => post('student_curr_addr_province'),
            'curr_addr_district' => post('student_curr_addr_district'),
            'curr_addr_commune' => post('student_curr_addr_commune'),
            'curr_addr_village' => post('student_curr_addr_village'),

            'phone1' => post('student_phone1'),
            'phone2' => post('student_phone2'),
            'email' => post('student_email'),

            'register_at' => post('student_register_at'),

            'guardian1_name' => post('student_guardian1_name'),
            'guardian2_phone' => post('student_guardian2_phone'),

            'guardian1_relationship' => post('student_guardian1_relationship'),
            'guardian2_relationship' => post('student_guardian2_relationship'),

            'guardian1_phone' => post('student_guardian1_phone'),
            'guardian2_phone' => post('student_guardian2_phone'),
            'guardian_email' => post('student_guardian_email'),

            'guardian_curr_addr_province' => post('student_guardian_curr_addr_province'),
            'guardian_curr_addr_district' => post('student_guardian_curr_addr_district'),
            'guardian_curr_addr_commune' => post('student_guardian_curr_addr_commune'),
            'guardian_curr_addr_village' => post('student_guardian_curr_addr_village'),

            'profile_image' => $photo,
            'status' => 'draft'
        ]);

        $_SESSION['student_id'] = $studentId;

        $conn->commit();

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['error' => $e->getMessage()]);
    }

    exit;
}


// ==========================================================
// ✅ STEP 2: ENROLL MULTIPLE CLASSES
// ==========================================================
if ($action === 'register_student' && $step === 2) {

    $conn->begin_transaction();

    try {

        $studentId = $_SESSION['student_id'];
        $classIds  = post('class_ids', []); // array

        if (!is_array($classIds) || empty($classIds)) {
            throw new Exception("No class selected");
        }

        foreach ($classIds as $classId) {

            // 🔒 Lock class

            $stmt = $conn->prepare("SELECT * FROM tblClasses WHERE class_id=? FOR UPDATE");
            $stmt->bind_param("i", $classId);
            $stmt->execute();

            $class = $stmt->get_result()->fetch_assoc();

            if (!$class) {
                throw new Exception("Class not found");
            }

            if ($class['current_students'] >= $class['max_students']) {
                throw new Exception("Class full: " . $class['class_name']);
            }

            $enrollCRUD->insert([
                'student_id' => $studentId,
                'class_id'   => $classId,
                'created_by'   => post('created_by')
            ]);
        }

        $conn->commit();

        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['error' => $e->getMessage()]);
    }

    exit;
}


// ==========================================================
// ✅ STEP 3: PAYMENT (AUTO PRICE)
// ==========================================================
if ($action === 'register_student' && $step === 3) {

    $conn->begin_transaction();

    try {

        $studentId = $_SESSION['student_id'];


        // 🔥 Get all enrolled classes + course price
        $result = $conn->query("
            SELECT c.class_id, cr.price
            FROM tblEnrollments e
            JOIN tblClasses c ON e.class_id = c.class_id
            JOIN tblCourses cr ON c.course_id = cr.course_id
            WHERE e.student_id = $studentId
        ");

        $total = 0;

        while ($row = $result->fetch_assoc()) {
            $total += $row['price'];
        }

        $discount = (float) post('discount', 0);
        $paid     = (float) post('amount_paid');

        $finalTotal = $total - $discount;
        $balance    = $finalTotal - $paid;

        if ($paid > $finalTotal) {
            throw new Exception("Paid exceeds total");
        }

        // insert payment
        $paymentId = $paymentCRUD->insert([
            'student_id' => $studentId,
            'total' => $finalTotal,
            'paid' => $paid,
            'balance' => $balance
        ]);

        // update class student count
        $conn->query("
            UPDATE tblClasses c
            JOIN tblEnrollments e ON c.class_id = e.class_id
            SET c.current_students = c.current_students + 1
            WHERE e.student_id = $studentId
        ");

        // activate student
        $studentCRUD
            ->where('student_id', '=', $studentId)
            ->update(['status' => 'active']);

        unset($_SESSION['student_id']);

        $conn->commit();

        // echo json_encode([
        //     'success' => true,
        //     'payment_id' => $paymentId
        // ]);
    } catch (Exception $e) {
        $conn->rollback();
        // echo json_encode(['error' => $e->getMessage()]);
    }

    exit;
}


// ==========================================================
// load page instead
include "register.php";
exit;
