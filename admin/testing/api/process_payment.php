session_start();

require "../../config/db.php";
require "../../core/DB.php";
require "../../core/ORM.php";

$db = new DB($conn);
$studentCRUD = new ORM($db, "tblStudents", "student_id");

$data = [
    'student_code' => $_POST['student_code'],
    'student_fst_name' => $_POST['student_fst_name'],
    'student_lst_name' => $_POST['student_lst_name'],
    'student_dob' => $_POST['student_dob'],
    'student_gender' => $_POST['student_gender'],
    'student_academic_year' => $_POST['student_academic_year'],
    'status' => 'draft'
];

$studentId = $studentCRUD->create($data);

$_SESSION['draft_student_id'] = $studentId;

echo json_encode([
    "success" => true,
    "next" => 2
]);