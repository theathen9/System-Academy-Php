<?php
// /data/dbShemaData.php
include_once __DIR__ . '/../config/db.php';



function getEmployees($conn, $limit, $offset, $search = '')
{

    $sql = "SELECT 
emp.employee_id,
CONCAT(emp.fst_name,' ',emp.lst_name) AS namekhmer,
CONCAT(emp.fst_name_eng,' ',emp.lst_name_eng) AS nameenglish,
emp.gender,
emp.dob,
emp.email,
emp.phone1,
emp.phone2,
dep.department_name,
CONCAT(emp.curr_addr_village,', ', emp.curr_addr_commune,', ',emp.curr_addr_district, ', ', emp.curr_addr_province) AS address
FROM tblEmployees emp
LEFT JOIN tblDepartments dep 
    ON dep.department_id = emp.department_id
";

    $params = [];
    $types = "";

    if ($search !== '') {
        $sql .= " WHERE emp.fst_name LIKE ?
        OR emp.lst_name LIKE ?
        OR emp.fst_name_eng LIKE ?
        OR emp.lst_name_eng LIKE ?
        OR emp.email LIKE ?
        OR emp.employee_id LIKE   ?";
        $like = "%$search%";
        $params = [$like, $like, $like, $like, $like, $like];
        $types = "ssssss";
    }

    $sql .= " ORDER BY emp.employee_id DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= "ii";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    return $stmt->get_result();
}


function countEmployees($conn, $search = '')
{

    $sql = "SELECT COUNT(*) AS total FROM tblEmployees emp";
    $params = [];
    $types = "";

    if ($search !== '') {
        $sql .= " WHERE emp.fst_name LIKE ?
        OR emp.lst_name LIKE ?
        OR emp.fst_name_eng LIKE ?
        OR emp.lst_name_eng LIKE ?
        OR emp.email LIKE ?
        OR emp.employee_id LIKE ?";
        $like = "%$search%";
        $params = [$like, $like, $like, $like, $like, $like];
        $types = "ssssss";
    }

    $stmt = $conn->prepare($sql);
    if ($types) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['total'];
}



function addEmployee($conn, $data)
{
    $sql = "INSERT INTO tblEmployees(
        department_id, fst_name, lst_name, fst_name_eng, lst_name_eng, gender, dob,
        dob_village, dob_commune, dob_district, dob_province,
        curr_addr_village, curr_addr_commune, curr_addr_district, curr_addr_province,
        phone1, phone2, email, profile_image, status
    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Normalize NULLs / arrays
    $department_id   = $data['department_id'] ?? 0;
    $fst_name        = $data['fst_name'] ?? '';
    $lst_name        = $data['lst_name'] ?? '';
    $fst_name_eng    = $data['fst_name_eng'] ?? '';
    $lst_name_eng    = $data['lst_name_eng'] ?? '';
    $gender          = $data['gender'] ?? '';
    $dob = $data['dob'] ?? null;

    if (!empty($dob)) {
        $dateObj = DateTime::createFromFormat('d-m-Y', $dob);
        $dob = $dateObj ? $dateObj->format('Y-m-d') : null;
    }
    $dob_village     = $data['dob_village'] ?? '';
    $dob_commune     = $data['dob_commune'] ?? '';
    $dob_district    = $data['dob_district'] ?? '';
    $dob_province    = $data['dob_province'] ?? '';
    $curr_addr_village   = $data['curr_addr_village'] ?? '';
    $curr_addr_commune   = $data['curr_addr_commune'] ?? '';
    $curr_addr_district  = $data['curr_addr_district'] ?? '';
    $curr_addr_province  = $data['curr_addr_province'] ?? '';
    $phone1          = $data['phone1'] ?? '';
    $phone2          = $data['phone2'] ?? '';
    $email           = $data['email'] ?? '';
    $profile_image = $data['profile_image'] ?? '';
    $status          = 'active';

    $stmt->bind_param(
        "isssssssssssssssssss",
        $department_id,
        $fst_name,
        $lst_name,
        $fst_name_eng,
        $lst_name_eng,
        $gender,
        $dob,
        $dob_village,
        $dob_commune,
        $dob_district,
        $dob_province,
        $curr_addr_village,
        $curr_addr_commune,
        $curr_addr_district,
        $curr_addr_province,
        $phone1,
        $phone2,
        $email,
        $profile_image,
        $status
    );
    // var_dump($data['dob']);
    // exit;
    return $stmt->execute();
}

function updateEmployee($conn, $id, $data)
{
    $stmt = $conn->prepare("UPDATE tblEmployees SET emp_id=?, name_kh=?, name_eng=?, position=?, phone=?, email=?, address=? WHERE emp_id=?");
    $stmt->bind_param(
        "sssssssi",
        $data['emp_id'],
        $data['name_kh'],
        $data['name_eng'],
        $data['position'],
        $data['phone'],
        $data['email'],
        $data['address'],
        $id
    );
    return $stmt->execute();
}

function deleteEmployee($conn, $id)
{
    $stmt = $conn->prepare("DELETE FROM tblEmployees WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

/**
 * ==========================
 * Teacher CRUD
 * ==========================
 */
function getTeachers($conn, $limit, $offset, $search = '')
{

    $sql = "SELECT 
    emp.*,
    -- GROUP_CONCAT(DISTINCT c.course_name SEPARATOR ', ') AS courses,
    GROUP_CONCAT(DISTINCT s.subject_name SEPARATOR ', ') AS subjects

FROM tblEmployees emp
LEFT JOIN tblSubjects s
    ON c.teacher_id = emp.employees_id

WHERE emp.department_id = 4

GROUP BY emp.employees_id";

    if (!empty($search)) {
        $sql .= " AND (
            fst_name LIKE ?
            OR lst_name LIKE ?
            OR fst_name_eng LIKE ?
            OR lst_name_eng LIKE ?
            OR employees_id LIKE ?
        )";
    }

    $sql .= " ORDER BY employees_id DESC LIMIT ? OFFSET ?";

    $stmt = $conn->prepare($sql);

    if (!empty($search)) {
        $like = "%$search%";
        $stmt->bind_param(
            "ssssiii",
            $like,
            $like,
            $like,
            $like,
            $like,
            $limit,
            $offset
        );
    } else {
        $stmt->bind_param("ii", $limit, $offset);
    }

    $stmt->execute();
    return $stmt->get_result();
}

function countTeacher($conn, $search = '')
{

    $sql = "SELECT COUNT(*) AS total FROM tblEmployees WHERE department_id = 4";

    if (!empty($search)) {
        $sql .= " AND (
            fst_name LIKE ?
            OR lst_name LIKE ?
            OR fst_name_eng LIKE ?
            OR lst_name_eng LIKE ?
            OR employees_id LIKE ?
        )";
    }

    $stmt = $conn->prepare($sql);

    if (!empty($search)) {
        $like = "%$search%";
        $stmt->bind_param("ssssi", $like, $like, $like, $like, $like);
    }

    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return $result['total'];
}

function addTeacher($conn, $data)
{
    $stmt = $conn->prepare("INSERT INTO tblTeacher (teacher_id, name_kh, name_eng, dob, email, phone) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $data['teacher_id'], $data['name_kh'], $data['name_eng'], $data['dob'], $data['email'], $data['phone']);
    return $stmt->execute();
}

function updateTeacher($conn, $id, $data)
{
    $stmt = $conn->prepare("UPDATE tblTeacher SET teacher_id=?, name_kh=?, name_eng=?, dob=?, email=?, phone=? WHERE id=?");
    $stmt->bind_param("ssssssi", $data['teacher_id'], $data['name_kh'], $data['name_eng'], $data['dob'], $data['email'], $data['phone'], $id);
    return $stmt->execute();
}

function deleteTeacher($conn, $id)
{
    $stmt = $conn->prepare("DELETE FROM tblTeacher WHERE teacher_id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}


/**
 * ==========================
 * Students CRUD
 * ==========================
 */
function getStudents($conn, $limit, $offset, $search = '')
{

    $sql = "SELECT * FROM tblStudents";
    $params = [];
    $types = "";

    if ($search !== '') {
        $sql .= " WHERE fst_name LIKE ?
                  OR lst_name LIKE ?
                  OR fst_name_eng LIKE ?
                  OR lst_name_eng LIKE ?
                  OR student_id LIKE ?";
        $like = "%$search%";
        $params = [$like, $like, $like, $like, $like];
        $types = "ssssi";
    }

    $sql .= " ORDER BY student_id DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= "ii";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    return $stmt->get_result();
}

function countStudent($conn, $search = '')
{

    $sql = "SELECT COUNT(*) AS total FROM tblStudents";

    if (!empty($search)) {
        $sql .= " AND (
            fst_name LIKE ?
            OR lst_name LIKE ?
            OR fst_name_eng LIKE ?
            OR lst_name_eng LIKE ?
            OR student_id LIKE ?
        )";
    }

    $stmt = $conn->prepare($sql);

    if (!empty($search)) {
        $like = "%$search%";
        $stmt->bind_param("ssssi", $like, $like, $like, $like, $like);
    }

    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    return $result['total'];
}
function addStudent($conn, $data)
{
    $sql = "INSERT INTO tblStudents(
        created_by,
        fst_name, lst_name, fst_name_eng, lst_name_eng, gender, dob,
        dob_village, dob_commune, dob_district, dob_province,
        curr_addr_village, curr_addr_commune, curr_addr_district, curr_addr_province,
        phone1, phone2, email,
        profile_image, academic_year,
        guardian1_name, guardian2_name, guardian1_relationship, guardian2_relationship,
        guardian_curr_addr_village, guardian_curr_addr_commune, guardian_curr_addr_district, guardian_curr_addr_province,
        guardian1_phone, guardian2_phone, guardian_email
    ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

    $stmt = $conn->prepare($sql);

    // ✅ Assign variables FIRST
    $created_by =  $data['created_by'];
    $fst_name = $data['fst_name'] ?? '';
    $lst_name = $data['lst_name'] ?? '';
    $fst_name_eng = $data['fst_name_eng'] ?? '';
    $lst_name_eng = $data['lst_name_eng'] ?? '';
    $gender = $data['gender'] ?? '';
    $dob = $data['dob'] ?? null;

    if (!empty($dob)) {
        $dateObj = DateTime::createFromFormat('d-m-Y', $dob);
        $dob = $dateObj ? $dateObj->format('Y-m-d') : null;
    }

    $dob_village = $data['dob_village'] ?? '';
    $dob_commune = $data['dob_commune'] ?? '';
    $dob_district = $data['dob_district'] ?? '';
    $dob_province = $data['dob_province'] ?? '';

    $curr_addr_village = $data['curr_addr_village'];
    $curr_addr_commune = $data['curr_addr_commune'];
    $curr_addr_district = $data['curr_addr_district'];
    $curr_addr_province = $data['curr_addr_province'];

    $phone1 = $data['phone1'] ?? '';
    $phone2 = $data['phone2'] ?? '';
    $email = $data['email'] ?? '';

    $profile_image = $data['profile_image'] ?? '';
    $academic_year = $data['academic_year'] ?? '';

    $guardian1_name = $data['guardian1_name'] ?? '';
    $guardian2_name = $data['guardian2_name'] ?? '';
    $guardian1_relationship = $data['guardian1_relationship'] ?? '';
    $guardian2_relationship = $data['guardian2_relationship'] ?? '';

    $guardian_curr_addr_village = $data['guardian_curr_addr_village'] ?? '';
    $guardian_curr_addr_commune = $data['guardian_curr_addr_commune'] ?? '';
    $guardian_curr_addr_district = $data['guardian_curr_addr_district'] ?? '';
    $guardian_curr_addr_province = $data['guardian_curr_addr_province'] ?? '';

    $guardian1_phone = $data['guardian1_phone'] ?? '';
    $guardian2_phone = $data['guardian2_phone'] ?? '';
    $guardian_email = $data['guardian_email'] ?? '';

    // ✅ Now bind VARIABLES only
    $stmt->bind_param(
        "issssssssssssssssssssssssssssss",
        $created_by,
        $fst_name,
        $lst_name,
        $fst_name_eng,
        $lst_name_eng,
        $gender,
        $dob,
        $dob_village,
        $dob_commune,
        $dob_district,
        $dob_province,
        $curr_addr_village,
        $curr_addr_commune,
        $curr_addr_district,
        $curr_addr_province,
        $phone1,
        $phone2,
        $email,
        $profile_image,
        $academic_year,
        $guardian1_name,
        $guardian2_name,
        $guardian1_relationship,
        $guardian2_relationship,
        $guardian_curr_addr_village,
        $guardian_curr_addr_commune,
        $guardian_curr_addr_district,
        $guardian_curr_addr_province,
        $guardian1_phone,
        $guardian2_phone,
        $guardian_email
    );

    return $stmt->execute();
}

function updateStudent($conn, $id, $data)
{
    $stmt = $conn->prepare("UPDATE tblStudent SET fst_name=?, lst_name=?, fst_name_eng=?, lst_name_eng=?, gender=?, dob=?, dob_village=?, dob_commune=?, dob_district=?, dob_province=?, curr_addr_village=?, curr_addr_commune=?, curr_addr_district=?, curr_addr_province=?, phone1=?, phone2=?, email=?, profile_image=?, guardian1_name=?, guardian2_name=?, guardian1_relationship=?, guardian2_relationship=?, guardian_curr_addr_village=?, guardian_curr_addr_commune=?, guardian_curr_addr_district=?, guardian_curr_addr_province=?, guardian1_phone=?, guardian2_phone=?, guardian_email=? WHERE student_id=?");
    $stmt->bind_param(
        "sssssssssssssssssssssssssssss",
        $data['student_id'],
        $data['fst_name'],
        $data['lst_name'],
        $data['fst_name_eng'],
        $data['lst_name_eng'],
        $data['gender'],
        $data['dob'],

        $data['dob_village'],
        $data['dob_commune'],
        $data['dob_district'],
        $data['dob_province'],

        $data['curr_addr_village'],
        $data['curr_addr_commune'],
        $data['curr_addr_district'],
        $data['curr_addr_province'],

        $data['phone1'],
        $data['phone2'],
        $data['email'],

        $data['academic_year'],

        $data['profile_image'],

        $data['guardian1_name'],
        $data['guardian2_name'],
        $data['guardian1_relationship'],
        $data['guardian2_relationship'],

        $data['guardian_curr_addr_village'],
        $data['guardian_curr_addr_commune'],
        $data['guardian_curr_addr_district'],
        $data['guardian_curr_addr_province'],

        $data['guardian1_phone'],
        $data['guardian2_phone'],
        $data['guardian_email']
    );

    return $stmt->execute();
}

function deleteStudent($conn, $id)
{
    $stmt = $conn->prepare("DELETE FROM tblStudent WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

/**
 * ==========================
 * Courses CRUD
 * ==========================
 */
function getCourses($conn)
{
    return $conn->query("SELECT * FROM tblCourses ORDER BY course_id DESC");
}

function addCourse($conn, $data)
{
    $stmt = $conn->prepare("INSERT INTO tblCourses (course_code, course_name, description) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $data['course_code'], $data['course_name'], $data['description']);
    return $stmt->execute();
}

function updateCourse($conn, $id, $data)
{
    $stmt = $conn->prepare("UPDATE tblCourse SET course_code=?, course_name=?, description=? WHERE id=?");
    $stmt->bind_param("sssi", $data['course_code'], $data['course_name'], $data['description'], $id);
    return $stmt->execute();
}

function deleteCourse($conn, $id)
{
    $stmt = $conn->prepare("DELETE FROM tblCourse WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

/**
 * ==========================
 * Departments CRUD
 * ==========================
 */
function getDepartments($conn, $limit, $offset, $search = '')
{
    $searchSql = '';
    if (!empty($search)) {
        $search = $conn->real_escape_string($search);
        $searchSql = "WHERE name LIKE '%$search%' 
                      OR email LIKE '%$search%' 
                      OR phone LIKE '%$search%' 
                      OR position LIKE '%$search%'";
    }

    $sql = "SELECT * FROM tblDepartments
            $searchSql
            ORDER BY department_id 
            LIMIT $limit OFFSET $offset";

    return $conn->query($sql);
}
function countDepartment($conn, $search = '')
{
    $searchSql = '';
    if (!empty($search)) {
        $search = $conn->real_escape_string($search);
        $searchSql = "WHERE name LIKE '%$search%' 
                      OR email LIKE '%$search%' 
                      OR phone LIKE '%$search%' 
                      OR position LIKE '%$search%'";
    }
    $result = $conn->query("SELECT COUNT(*) AS total FROM tblDepartments $searchSql");
    return $result->fetch_assoc()['total'];
}

function addDepartment($conn, $data)
{
    $departmentCode = $data['department_code'] ?? null;
    $departmentName = $data['department_name'] ?? null;
    $description = $data['description'] ?? null;

    if (!$departmentCode || !$departmentName) {
        die("❌ department_code and department_name are required.");
    }


    $createdAt = !empty($data['created_at']) ? $data['created_at'] : date('Y-m-d');
    $updatedAt = !empty($data['updated_at']) ? $data['updated_at'] : date('Y-m-d');

    $stmt = $conn->prepare("
        INSERT INTO tblDepartments 
        (department_code, department_name, description, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->bind_param("sssss", $departmentCode, $departmentName, $description, $createdAt, $createdAt);

    $result = $stmt->execute();

    if ($stmt->error) {
        die('❌ DB Error: ' . $stmt->error);
    }

    $stmt->close();
    return $result;
}



function updateDepartment($conn, $id, $data)
{
    // Make sure required fields exist
    $department_code = $data['department_code'] ?? '';
    $department_name = $data['department_name'] ?? '';
    $description     = $data['description'] ?? '';

    if ($department_code === '' || $department_name === '') {
        return false;
    }

    // Automatically set updated_at to now
    $updated_at = date('Y-m-d H:i:s');

    $stmt = $conn->prepare(
        "UPDATE tblDepartments
         SET department_code = ?, 
             department_name = ?, 
             description = ?, 
             updated_at = ?
         WHERE department_id = ?"
    );

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssi",
        $department_code,
        $department_name,
        $description,
        $updated_at,
        $id
    );

    $result = $stmt->execute();
    $stmt->close();

    return $result;
}


function deleteDepartment($conn, $id)
{
    $stmt = $conn->prepare(
        "UPDATE tblDepartments
         WHERE department_id=?"
    );

    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
function statusDepartment($conn, $id)
{
    // Get current status
    $stmt = $conn->prepare("SELECT status FROM tblDepartments WHERE department_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $currentStatus = $row['status'];
        $newStatus = $currentStatus == 1 ? 0 : 1;

        $update = $conn->prepare("UPDATE tblDepartments SET status = ?, updated_at = NOW() WHERE department_id = ?");
        $update->bind_param("ii", $newStatus, $id);
        $update->execute();
    }
}
/**
 * ==========================
 * Rooms CRUD
 * ==========================
 */
function getRooms($conn, $limit, $offset, $search = '')
{

    $sql = "SELECT * FROM tblRooms";
    $params = [];
    $types = "";

    if ($search !== '') {
        $sql .= " WHERE room_name LIKE ?";
        $like = "%$search%";
        $params = [$like];
        $types = "s";
    }

    $sql .= " ORDER BY room_id DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= "ii";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    return $stmt->get_result();
}

function addRoom($conn, $data)
{
    $stmt = $conn->prepare("INSERT INTO tblRoom (room_name, capacity) VALUES (?, ?)");
    $stmt->bind_param("si", $data['room_name'], $data['capacity']);
    return $stmt->execute();
}

function updateRoom($conn, $id, $data)
{
    $stmt = $conn->prepare("UPDATE tblRoom SET room_name=?, capacity=? WHERE id=?");
    $stmt->bind_param("sii", $data['room_name'], $data['capacity'], $id);
    return $stmt->execute();
}

function deleteRoom($conn, $id)
{
    $stmt = $conn->prepare("DELETE FROM tblRoom WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}

/**
 * ==========================
 * Enrollments CRUD
 * ==========================
 */
function getEnrollments($conn, $limit, $offset, $search = '')
{
    return $conn->query("SELECT * FROM tblenrollments ORDER BY id DESC");

    $params = [];
    $types = "";

    if ($search !== '') {
        $sql .= " WHERE department_code LIKE ?
        OR department_name LIKE ?";
        $like = "%$search%";
        $params = [$like, $like];
        $types = "ss";
    }

    $sql .= " ORDER BY department_id DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= "ii";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    return $stmt->get_result();
}

function addEnrollment($conn, $data)
{
    $stmt = $conn->prepare("INSERT INTO tblEnrollments (student_id, course_id, enrollment_date) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $data['student_id'], $data['course_id'], $data['enrollment_date']);
    return $stmt->execute();
}

function updateEnrollment($conn, $id, $data)
{
    $stmt = $conn->prepare("UPDATE tblEnrollments SET student_id=?, course_id=?, enrollment_date=? WHERE id=?");
    $stmt->bind_param("iisi", $data['student_id'], $data['course_id'], $data['enrollment_date'], $id);
    return $stmt->execute();
}

function deleteEnrollment($conn, $id)
{
    $stmt = $conn->prepare("DELETE FROM tblEnrollments WHERE id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}


//  


//

function getClasses($conn, $limit, $offset, $search = '')
{
    $sql = "SELECT c.class_id, c.class_name, c.start_date, c.end_date, c.academic_year, c.status,
                   c.course_id, c.teacher_id, c.room_id,
                   t.fst_name AS teacher_first, t.lst_name AS teacher_last,
                   r.room_name,
                   co.course_name
            FROM tblClasses c
            LEFT JOIN tblEmployees t ON t.employee_id = c.teacher_id
            LEFT JOIN tblRooms r ON r.room_id = c.room_id
            LEFT JOIN tblCourses co ON co.course_id = c.course_id";

    $params = [];
    $types = "";

    if ($search !== '') {
        $sql .= " WHERE c.class_name LIKE ?";
        $like = "%$search%";
        $params[] = $like;
        $types .= "s";
    }

    $sql .= " ORDER BY c.class_id DESC LIMIT ? OFFSET ?";
    $params[] = $limit;
    $params[] = $offset;
    $types .= "ii";

    $stmt = $conn->prepare($sql);
    if (!$stmt) die("Prepare failed: " . $conn->error);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result();
}

function countClasses($conn, $search = '')
{
    $sql = "SELECT COUNT(*) AS total FROM tblClasses c";
    $params = [];
    $types = "";

    if ($search !== '') {
        $sql .= " WHERE c.class_name LIKE ?";
        $like = "%$search%";
        $params[] = $like;
        $types .= "s";
    }

    $stmt = $conn->prepare($sql);
    if ($types) $stmt->bind_param($types, ...$params);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc()['total'];
}

function addClass($conn, $data)
{
    $sql = "INSERT INTO tblClasses (class_name, start_date, end_date, course_id, teacher_id, room_id, academic_year, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    $class_name = $data['class_name'] ?? '';
    $start_date = $data['start_date'] ?? null;
    $end_date   = $data['end_date'] ?? null;
    $course_id  = $data['course_id'] ?? 0;
    $teacher_id = $data['teacher_id'] ?? 0;
    $room_id    = $data['room_id'] ?? 0;
    $academic_year = $data['academic_year'] ?? '';
    $status     = $data['status'] ?? 'Open';

    $stmt->bind_param("ssiiiiss", $class_name, $start_date, $end_date, $course_id, $teacher_id, $room_id, $academic_year, $status);
    return $stmt->execute();
}

function updateClass($conn, $id, $data)
{
    $sql = "UPDATE tblClasses SET class_name=?, start_date=?, end_date=?, course_id=?, teacher_id=?, room_id=?, academic_year=?, status=? WHERE class_id=?";
    $stmt = $conn->prepare($sql);

    $class_name = $data['class_name'] ?? '';
    $start_date = $data['start_date'] ?? null;
    $end_date   = $data['end_date'] ?? null;
    $course_id  = $data['course_id'] ?? 0;
    $teacher_id = $data['teacher_id'] ?? 0;
    $room_id    = $data['room_id'] ?? 0;
    $academic_year = $data['academic_year'] ?? '';
    $status     = $data['status'] ?? 'Open';

    $stmt->bind_param("ssiiiissi", $class_name, $start_date, $end_date, $course_id, $teacher_id, $room_id, $academic_year, $status, $id);
    return $stmt->execute();
}

function deleteClass($conn, $id)
{
    $stmt = $conn->prepare("DELETE FROM tblClasses WHERE class_id=?");
    $stmt->bind_param("i", $id);
    return $stmt->execute();
}
// 
// Handlefile
// 

function uploadFile($conn, $id)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if (!isset($_FILES['profile']) || $_FILES['profile']['error'] !== 0) {
            die("Upload failed");
        }

        $file = $_FILES['profile'];

        // 1️⃣ Validate size (2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            die("File too large");
        }

        // 2️⃣ Validate MIME type
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        $mime = mime_content_type($file['tmp_name']);

        if (!in_array($mime, $allowed)) {
            die("Invalid file type");
        }

        // 3️⃣ Generate secure filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('emp_', true) . '.' . $extension;

        $uploadDir = __DIR__ . "/uploads/employees/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $destination = $uploadDir . $filename;

        move_uploaded_file($file['tmp_name'], $destination);

        // Save path to DB
        $pathForDB = "uploads/employees/" . $filename;

        $stmt = $conn->prepare("UPDATE tblEmployees SET profile_image=? WHERE employee_id=?");
        $stmt->bind_param("si", $pathForDB, $id);
        $stmt->execute();
    }
}
