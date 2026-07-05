    <?php
    // echo "<pre>";
    // print_r($_SESSION);
    // print_r($_COOKIE);
    // exit;
    //./admin/examination/add.php
    session_start();
    date_default_timezone_set('Asia/Phnom_Penh');
    include_once __DIR__ . '/../../config/bootstrap.php';
    include_once __DIR__ . "/../../data/dataSchema.php";
    include_once __DIR__ . "/../../components/Navbar.php";
    include_once __DIR__ . "/../../components/Avatar.php";




    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }


    $userId = checkAuth();

    if (!$userId) {
        header("Location: " . BASE_URL . "/auth/signin.php");
        exit;
    }

    authorizeRole('accountant');
    //     var_dump($_SESSION);
    // var_dump($_COOKIE);
    // exit;

    // include_once "/config/db.php";
    $routeAccount[0]["active"] = false;
    $routeAccount[5]["active"] = true;
    $routeAccount[5]['submenu'][0]['active'] = true;


    $db = new DB($conn);
    $teacherCRUD = new ORM($db, 'tblEmployees t', 'employee_id');
    $classesCRUD = new ORM($db, 'tblClasses', 'class_id');
    $studentsCRUD = new ORM($db, 'tblStudents s', 'student_id');
    $examTypeCRUD = new ORM($db, 'tblExamTypes', 'exam_type_id');
    $examCRUD = new ORM($db, 'tblExams em', 'exam_id');
    $scoresCRUD = new ORM($db, 'tblScores sc', 'score_id');
    $scoreTypesCRUD = new ORM($db, 'tblScoreTypes', 'score_type_id');
    $resultStudentCRUD = new ORM($db, 'tblStudentResults', 'result_id');


    $selectedTeacher = $_GET['teacher'] ?? '';
    $selectedClass = $_GET['class'] ?? '';
    $academicYear    = $_GET['academic_year'] ?? '';
    $selectedExamTypeId = $_GET['exam_type_id'] ?? '';
    $selectedExamDate = $_GET['exam_date'] ?? '';
    $examId = $_GET['exam_id'] ?? $_SESSION['exam_id'] ?? null;
    $years = $conn->query("
    SELECT DISTINCT academic_year
    FROM tblClasses
    ORDER BY academic_year DESC");

    $canLoadScores =
        !empty($selectedTeacher) &&
        !empty($selectedClass) &&
        !empty($academicYear) &&
        !empty($selectedExamTypeId) &&
        !empty($selectedExamDate);



    $students = [];

    if ($canLoadScores) {
        $students = $studentsCRUD
            ->select("
            s.student_id,
            e.enrollment_id,
            CONCAT(s.first_name_kh, ' ', s.last_name_kh) AS name,
            c.class_code AS class
        ")
            ->join("tblEnrollments e", "e.student_id = s.student_id")
            ->join("tblClasses c", "c.class_id = e.class_id")
            ->where("e.class_id", "=", $selectedClass)
            ->get();
    }

    $scoreTypes = $scoreTypesCRUD->select("*")->get();
    $examType = $examTypeCRUD->select("*")->get();

    $studentsScores = [];

    // $dateCheck = $examCRUD->select('exam_date')->where("exam_date", "=", $selectedExamDate)->first();

    // if ($canLoadScores && !empty($examId)) {
    //     $studentsScores = $scoresCRUD
    //         ->select("enrollment_id, score_type_id, score")
    //         ->where("exam_id", "=", $examId)
    //         ->get();
    // } else if ($canLoadScores && empty($examId) && $dateCheck) {
    //     $studentsScores = $scoresCRUD
    //         ->select("enrollment_id, score_type_id, score")
    //         ->where("exam_id", "=", $examId)
    //         ->where("exam_date", "=", $dateCheck)
    //         ->get();
    // } else if ($canLoadScores && empty($examId)) {
    //     $studentsScores = $scoresCRUD
    //         ->select("enrollment_id, score_type_id, score")
    //         ->where("exam_id", "=", $examId)
    //         // ->where("exam_id", "=", '')
    //         ->get();
    // }

    if ($canLoadScores) {

        $exam = $examCRUD
            ->select("exam_id")
            ->where("class_id", "=", $selectedClass)
            ->where("exam_type_id", "=", $selectedExamTypeId)
            ->where("exam_date", "=", $selectedExamDate)
            ->first();

        $examId = $exam['exam_id'] ?? null;

        if ($examId) {
            $studentsScores = $scoresCRUD
                ->select("enrollment_id, score_type_id, score")
                ->where("exam_id", "=", $examId)
                ->get();
        }
    }

    $scoreMap = [];

    if ($canLoadScores && !empty($studentsScores)) {
        foreach ($studentsScores as $score) {
            $scoreMap[$score['enrollment_id']][$score['score_type_id']]
                = (float)$score['score'];
        }
    }

    $teachers = $teacherCRUD
        ->select("
        t.employee_id,
        CONCAT(t.first_name_kh, ' ', t.last_name_kh) AS name")
        ->join("tblDepartments d", "d.department_id = t.department_id")
        ->where("d.department_name", "=", "Teacher")
        ->get();

    $classQuery = $classesCRUD
        ->select("
        class_id,
        class_name,
        teacher_id,
        academic_year
    ")->where('status', '=', 'active');

    if (!empty($academicYear)) {
        $classQuery->where("SUBSTRING_INDEX(academic_year, '-', -1)", "=", $academicYear);
    }

    if (!empty($selectedTeacher)) {
        $classQuery->where("teacher_id", "=", $selectedTeacher);
    }

    $classes = $classQuery->get();


    // echo "<pre>";
    // print_r($scoreMap);
    // exit;
    $secess = true;
    $error = false;


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        verifyCSRF();

        $examCRUD = new ORM($db, 'tblExams', 'exam_id');
        $scoresCRUD = new ORM($db, 'tblScores', 'score_id');
        $resultCRUD = new ORM($db, 'tblStudentResults', 'result_id');


        $selectedTeacher      = $_POST['teacher'] ?? '';
        $selectedClass      = $_POST['class'] ?? '';
        $selectedExamTypeId = $_POST['exam_type_id'] ?? '';
        $selectedExamDate   = $_POST['hidden_exam_date'] ?? '';
        $academicYear       = $_POST['academic_year'] ?? '';



        // -------------------------
        // CREATE EXAM NAME
        // -------------------------
        $monthName = date('F Y', strtotime($selectedExamDate));

        $examTypeRow = $examTypeCRUD
            ->select("exam_type_name")
            ->where("exam_type_id", "=", $selectedExamTypeId)
            ->first();

        $examName = $examTypeRow['exam_type_name'] . " - " . $monthName;

        // -------------------------
        // INSERT EXAM
        // -------------------------

        try {
            $examId = $_POST['exam_id'] ?? null;
            $message = '';

            if (!empty($examId)) {

                $examCRUD
                    ->where('exam_id', '=', $examId)
                    ->update([
                        'exam_name'     => $examName,
                        'exam_type_id'  => $selectedExamTypeId,
                        'class_id'      => $selectedClass,
                        'exam_date'     => $selectedExamDate
                    ]);

                $message = "Scores updated successfully!";
            } else {

                $check = $examCRUD
                    ->select('exam_id')
                    ->where('exam_type_id', '=', $selectedExamTypeId)
                    ->where('class_id', '=', $selectedClass)
                    ->where('exam_date', '=', $selectedExamDate)
                    ->first();

                if ($check) {

                    $examId = $check['exam_id'];
                    $message = "Scores updated successfully!";
                } else {

                    $examId = $examCRUD->insert([
                        'exam_name'     => $examName,
                        'exam_type_id'  => $selectedExamTypeId,
                        'class_id'      => $selectedClass,
                        'exam_date'     => $selectedExamDate
                    ]);

                    $message = "Scores saved successfully!";
                }
            }

            $_SESSION['success'] = $message;

            // -------------------------
            // SAVE SCORES
            // -------------------------
            foreach ($_POST['scores'] as $scoreTypeId => $studentsScores) {

                foreach ($studentsScores as $enrollmentId => $scoreValue) {

                    $data = [
                        'exam_id'        => $examId,
                        'enrollment_id'  => $enrollmentId,
                        'score_type_id'  => $scoreTypeId,
                        'score'          => (float)$scoreValue
                    ];

                    $existing = $scoresCRUD
                        ->select("score_id")
                        ->where("exam_id", "=", $examId)
                        ->where("enrollment_id", "=", $enrollmentId)
                        ->where("score_type_id", "=", $scoreTypeId)
                        ->first();

                    if ($existing) {
                        $scoresCRUD
                            ->where("score_id", "=", $existing['score_id'])
                            ->update($data);
                    } else {
                        $scoresCRUD->insert($data);
                    }
                }
            }

            // -------------------------
            // RESULTS
            // -------------------------
            $enrollments = $studentsCRUD
                ->select("e.enrollment_id")
                ->join("tblEnrollments e", "e.student_id = s.student_id")
                ->where("e.class_id", "=", $selectedClass)
                ->get();

            foreach ($enrollments as $enroll) {

                $enrollmentId = $enroll['enrollment_id'];
                $total = 0;

                foreach ($scoreTypes as $type) {

                    $row = $scoresCRUD
                        ->select("score")
                        ->where("exam_id", "=", $examId)
                        ->where("enrollment_id", "=", $enrollmentId)
                        ->where("score_type_id", "=", $type['score_type_id'])
                        ->first();

                    $total += (float)($row['score'] ?? 0);
                }

                $average = $total;

                if ($average >= 90) $gradeId = 1;
                elseif ($average >= 80) $gradeId = 2;
                elseif ($average >= 70) $gradeId = 3;
                elseif ($average >= 60) $gradeId = 4;
                else $gradeId = 5;

                $resultData = [
                    'exam_id'        => $examId,
                    'enrollment_id'  => $enrollmentId,
                    'class_id'       => $selectedClass,
                    'academic_year'  => $academicYear,
                    'total_score'    => $total,
                    'average_score'  => $average,
                    'grade_id'       => $gradeId
                ];

                $existing = $resultCRUD
                    ->select("result_id")
                    ->where("exam_id", "=", $examId)
                    ->where("enrollment_id", "=", $enrollmentId)
                    ->first();

                if ($existing) {
                    $resultCRUD
                        ->where("result_id", "=", $existing['result_id'])
                        ->update($resultData);
                } else {
                    $resultCRUD->insert($resultData);
                }
            }
            $params = [
                'exam_id' => $examId,
                'teacher' => $selectedTeacher,
                'class' => $selectedClass,
                'academic_year' => $academicYear,
                'exam_type_id' => $selectedExamTypeId,
                'exam_date' => $selectedExamDate
            ];

            // $_SESSION['success'] = "Scores saved successfully!";

            header("Location: add.php?" . http_build_query($params));
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }
    }

    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Dashboard | <?php echo $infoSchemaData[1]["name_short"] ?></title>
        <link rel="icon" type="image/png" href="<?php echo $infoSchemaData[5]["image"] ?>">
        <link rel="icon" type="image/png" href="<?php echo $infoSchemaData[5]["image"] ?>">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
        <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
        </script>
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css"
            integrity="sha512-t7Few9xlddEmgd3oKZQahkNI4dS6l80+eGEzFQiqtyVYdvcSG2D3Iub77R20BdotfRPA9caaRkg1tyaJiPmO0g=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="../../src/style.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
        <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
        <script src="/system-management/src/assets/js/user-profile.js"></script>


        <style>
            .dataLoading.loading {
                opacity: 0.6;
                pointer-events: none;
            }

            .tag-box {
                display: flex;
                flex-wrap: wrap;
                border: 1px solid #ccc;
                padding: 5px;
                border-radius: 6px;
            }

            .tag {
                background: #e3f2fd;
                margin: 3px;
                padding: 5px 10px;
                border-radius: 20px;
                display: flex;
                align-items: center;
            }

            .tag span {
                margin-left: 8px;
                cursor: pointer;
                color: red;
            }

            .sidebar a {
                display: block;
                padding: 12px 20px;
                color: #333;
                text-decoration: none;
            }

            .sidebar a:hover {
                background: #0d6efd;
                color: white;
                border-radius: 6px;
            }

            .content {
                margin-left: 240px;
                padding: 20px;
            }

            .card-box {
                border-radius: 12px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            }

            .stat-icon {
                font-size: 28px;
                padding: 15px;
                border-radius: 10px;
                color: white;
            }

            .bg-blue {
                background: #0d6efd;
            }

            .bg-green {
                background: #28a745;
            }

            .bg-orange {
                background: #f39c12;
            }

            .bg-red {
                background: #e74c3c;
            }
        </style>

    </head>

    <body class="container-fluid p-0 overflow-x-hidden">
        <div class="row g-3">

            <?php Navbar($infoSchemaData, $routeAccount) ?>

            <!-- Main area -->
            <main class="col-10 bg-light">
                <div
                    class="d-flex justify-content-between align-items-center px-2 py-2 bg-white position-sticky top-0 z-3">
                    <div class="title">Welcome to <?php echo $infoSchemaData[0]["name"] ?></div>

                    <?php Avatar($_SESSION['role']); ?>

                </div>



                <div class="w-100 d-flex mt-3 ms-3 justify-content-between gap-3 flex-wrap dataLoading">
                    <div class="w-100 bg-white shadow px-4 py-3 rounded">
                        <div class="row g-4 pb-5">
                            <div class="">
                                <div class="container-fluid">

                                    <!-- Header -->
                                    <div class="card shadow-sm border-0 mb-4">
                                        <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                                            <div>
                                                <h3 class="mb-1 fw-bold">Student Score Management</h3>
                                                <p class="text-muted mb-0">
                                                    Input and manage student scores
                                                </p>
                                            </div>

                                            <button class="btn btn-primary px-4">
                                                <i class="bi bi-save me-2"></i>
                                                Save Scores
                                            </button>
                                        </div>
                                    </div>
                                    <form enctype="multipart/form-data" method="GET">
                                        <!-- Filter -->
                                        <div class="card shadow-sm border-0 mb-4">
                                            <div class="card-body">

                                                <div class="row g-3">

                                                    <div class="col-md-3">
                                                        <label class="form-label">Teacher</label>

                                                        <select name="teacher" class="form-select" onchange="this.form.submit()" required>
                                                            <option value="">Select Teacher</option>

                                                            <?php foreach ($teachers as $teacher): ?>
                                                                <option
                                                                    value="<?= $teacher['employee_id'] ?>"
                                                                    <?= $selectedTeacher == $teacher['employee_id'] ? 'selected' : '' ?>>
                                                                    <?= $teacher['name'] ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Academic Year</label>

                                                        <input
                                                            type="text"
                                                            name="academic_year"
                                                            class="form-control"
                                                            placeholder="e.g. 2023-2024 or 2024"
                                                            value="<?= htmlspecialchars($academicYear ?? '') ?>"
                                                            required
                                                            onchange="this.form.submit()">
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Class</label>

                                                        <select name="class" class="form-select" required>
                                                            <option value="">Select Class</option>

                                                            <?php foreach ($classes as $class): ?>
                                                                <option
                                                                    value="<?= $class['class_id'] ?>"
                                                                    <?= $selectedClass == $class['class_id'] ? 'selected' : '' ?>>
                                                                    <?= $class['class_name'] ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div></div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Exam Type</label>

                                                        <select name="exam_type_id" class="form-select" required>
                                                            <option value="">Select Exam Type</option>

                                                            <?php foreach ($examType as $items): ?>
                                                                <option
                                                                    value="<?= $items['exam_type_id'] ?>"
                                                                    <?= $items['exam_type_id'] == $selectedExamTypeId ? 'selected' : '' ?>>
                                                                    <?= htmlspecialchars($items['exam_type_name']) ?>
                                                                </option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Exam Date</label>
                                                        <input
                                                            name="exam_date"
                                                            value="<?= htmlspecialchars($selectedExamDate) ?>"
                                                            type="text"
                                                            id="exam_date"
                                                            class="form-control"
                                                            placeholder="dd/mm/yyyy"
                                                            required
                                                            class="form-control">
                                                    </div>

                                                    <div class="col-md-2 d-flex align-items-end">
                                                        <button type="submit" class="btn btn-primary w-100">
                                                            <i class="bi bi-search me-1"></i>
                                                            Load
                                                        </button>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </form>



                                    <!-- Table -->
                                    <div class="card shadow-sm border-0">

                                        <div class="card-body p-0">

                                            <form id="scoreForm" method="POST" enctype="multipart/form-data">


                                                <?= csrf_field() ?>
                                                <input type="hidden" name="created_by" value="<?= $_SESSION['reference_id'] ?? '' ?>">
                                                <input type="hidden" name="exam_id" value="<?= htmlspecialchars($examId ?? '') ?>">
                                                <input type="hidden" name="teacher" value="<?= htmlspecialchars($selectedTeacher) ?>">
                                                <input type="hidden" name="class" value="<?= htmlspecialchars($selectedClass) ?>">
                                                <input type="hidden" name="academic_year" value="<?= htmlspecialchars($academicYear) ?>">
                                                <input type="hidden" name="exam_type_id" value="<?= htmlspecialchars($selectedExamTypeId) ?>">
                                                <input id="hidden_exam_date" type="hidden" name="hidden_exam_date" value="<?= htmlspecialchars($selectedExamDate) ?>">

                                                <div class="table-responsive">

                                                    <table class="table table-hover align-middle mb-0">

                                                        <thead class="table-light">
                                                            <tr>
                                                                <th>#</th>
                                                                <th>Student</th>
                                                                <th>Class</th>
                                                                <th>Speaking</th>
                                                                <th>Listening</th>
                                                                <th>Reading</th>
                                                                <th>Grammar</th>
                                                                <th>Writing</th>
                                                                <th width="150">Score</th>
                                                            </tr>
                                                        </thead>

                                                        <tbody>

                                                            <?php if (!empty($students)): ?>

                                                                <?php foreach ($students as $index => $student): ?>
                                                                    <tr>
                                                                        <td><?= $index + 1 ?></td>

                                                                        <td class="fw-semibold">
                                                                            <?= htmlspecialchars($student['name'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                                                        </td>

                                                                        <td>
                                                                            <?= htmlspecialchars($student['class'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                                                                        </td>

                                                                        <?php $total = 0; ?>

                                                                        <?php foreach ($scoreTypes as $type): ?>
                                                                            <?php
                                                                            $scoreValue = $scoreMap[$student['enrollment_id']][$type['score_type_id']] ?? '';
                                                                            $total += (float) $scoreValue;
                                                                            ?>

                                                                            <td>
                                                                                <input
                                                                                    type="number"
                                                                                    name="scores[<?= $type['score_type_id'] ?>][<?= $student['enrollment_id'] ?>]"
                                                                                    class="form-control score-input"
                                                                                    min="0"
                                                                                    max="100"
                                                                                    value="<?= htmlspecialchars($scoreValue, ENT_QUOTES, 'UTF-8') ?>">
                                                                            </td>
                                                                        <?php endforeach; ?>

                                                                        <td>
                                                                            <input
                                                                                type="number"
                                                                                class="form-control total-score"
                                                                                value="<?= $total ?>"
                                                                                readonly>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; ?>

                                                            <?php elseif ($canLoadScores): ?>

                                                                <tr>
                                                                    <td colspan="<?= count($scoreTypes) + 4 ?>" class="text-center text-muted">
                                                                        No students found.
                                                                    </td>
                                                                </tr>

                                                            <?php endif; ?>



                                                        </tbody>

                                                    </table>

                                                    <div class="m-2">
                                                        <?php if (!empty($_SESSION['success'])): ?>
                                                            <div class="alert text-center alert-success alert-dismissible fade show" role="alert">
                                                                <?= htmlspecialchars($_SESSION['success']) ?>
                                                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                            </div>
                                                            <?php unset($_SESSION['success']); ?>
                                                        <?php endif; ?>

                                                        <?php if (!empty($_SESSION['error'])): ?>
                                                            <div class="alert text-center alert-danger alert-dismissible fade show" role="alert">
                                                                <?= htmlspecialchars($_SESSION['error']) ?>
                                                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                                            </div>
                                                            <?php unset($_SESSION['error']); ?>
                                                        <?php endif; ?>
                                                    </div>



                                                </div>

                                                <div class="p-3 border-top text-end">

                                                    <button type="submit" class="btn btn-success px-4">
                                                        <i class="bi bi-check-circle me-2"></i>
                                                        Save All Scores
                                                    </button>

                                                </div>

                                            </form>

                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
            </main>
        </div>
        <script src="../../script.js"></script>
        <script src="<?= BASE_URL ?>/src/assets/js/navbar-toggle-action.js"></script>


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const inputs = document.querySelectorAll('.score-input');

                inputs.forEach((input, index) => {
                    input.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();

                            const nextInput = inputs[index + 1];
                            if (nextInput) {
                                nextInput.focus();
                                nextInput.select(); // optional: select existing value
                            }
                        }
                    });
                });
                flatpickr("#exam_date", {
                    altFormat: "d-m-Y", // ✅ what user sees
                    dateFormat: "Y-m-d", // ✅ value sent to backend
                    altInput: true, // ✅ show separate input
                    maxDate: "today",
                    allowInput: true,
                    monthSelectorType: "dropdown",
                    yearSelectorType: "dropdown",

                    onChange: function(selectedDates, dateStr) {
                        document.getElementById("exam_date").value = dateStr;

                        const hidden = document.getElementById("hidden_exam_date");
                        if (hidden) {
                            hidden.value = dateStr;
                        }
                    }
                });
            });
        </script>

        <script>
            document.querySelectorAll("tbody tr").forEach(row => {

                // dynamic score inputs
                const scoreInputs = row.querySelectorAll(".score-input");

                // total field
                const totalInput = row.querySelector(".total-score");

                function calculateTotal() {

                    let total = 0;

                    scoreInputs.forEach(input => {

                        total += Number(input.value || 0);

                    });

                    if (totalInput) {
                        totalInput.value = total;
                    }
                }

                // auto calculate on typing
                scoreInputs.forEach(input => {

                    input.addEventListener("input", calculateTotal);

                });

                // first load
                calculateTotal();
            });
        </script>

        <script>
            async function loadClasses() {
                const teacher = document.querySelector('[name="teacher"]').value;
                const year = document.querySelector('[name="academic_year"]').value;
                const classId = document.querySelector('[name="class"]').value;
                const examType = document.querySelector('[name="exam_type_id"]').value;
                const examDate = document.querySelector('[name="exam_date"]').value;

                const res = await fetch(`/system-management/app/api/v1/classes_by_teacher.php?teacher=${teacher}&academic_year=${year}&class=${classId}&exam_type=${examType}&date=${examDate}`);

                const json = await res.json();
                console.log(json);
            }

            const scoreForm = document.getElementById('scoreForm');

            if (scoreForm) {
                scoreForm.addEventListener('submit', function(e) {

                    const examType = document.querySelector('[name="exam_type_id"]').value;
                    const examDate = document.querySelector('[name="exam_date"]').value;

                    if (!examType || !examDate) {
                        e.preventDefault();
                        alert('Please select Exam Type and Exam Date first.');
                    }
                });
            }
        </script>

        <script>
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    alert.classList.remove('show');
                    setTimeout(() => alert.remove(), 1000);
                });
            }, 3000);
        </script>

    </body>

    </html>