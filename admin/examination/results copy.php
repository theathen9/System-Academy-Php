    <?php
    // echo "<pre>";
    // print_r($_SESSION);
    // print_r($_COOKIE);
    // exit;
    //./admin/examination/add.php
    session_start();
    date_default_timezone_set('Asia/Phnom_Penh');
    include_once __DIR__ . '/../../data/dataSchema.php';
    include_once __DIR__ . '/../../config/bootstrap.php';
    include_once __DIR__ . '/../../components/Navbar.php';



    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }


    $userId = checkAuth();

    if (!$userId) {
        header("Location: " . BASE_URL . "/auth/signin.php");
        exit;
    }

    authorizeRole('admin');
    //     var_dump($_SESSION);
    // var_dump($_COOKIE);
    // exit;

    // include_once "/config/db.php";
    $routeAdmin[0]["active"] = false;
    $routeAdmin[5]["active"] = true;
    $routeAdmin[5]['submenu'][1]['active'] = true;



    // Fetch DISTINCT years (clean)
    $yearResult = $conn->query("
        SELECT DISTINCT SUBSTRING_INDEX(academic_year, '-', 1) AS year
        FROM tblClasses
        ORDER BY year ASC
    ");

    $years = [];
    while ($row = $yearResult->fetch_assoc()) {
        $years[] = $row['year'];
    }

    // Get filters
    $selectedYear = isset($_GET['selectYear']) && $_GET['selectYear'] !== ''
        ? $_GET['selectYear']
        : 'allYear';

    $selectedClass = isset($_GET['selectClass']) && $_GET['selectClass'] !== ''
        ? $_GET['selectClass']
        : 'allClasses';

    $status = isset($_GET['status'])
        ? (array)$_GET['status']
        : [];




    // Dynamic query
    $sql = "SELECT class_id, class_name FROM tblClasses WHERE 1=1";
    $params = [];
    $types = "";

    if ($selectedYear !== 'allYear') {
        $sql .= " AND SUBSTRING_INDEX(academic_year, '-', 1) = ?";
        $params[] = $selectedYear;
        $types .= "s";
    }

    if ($selectedClass !== 'allClasses') {
        $sql .= " AND class_id = ?";
        $params[] = (int)$selectedClass;
        $types .= "i";
    }

    $sql .= " ORDER BY class_name ASC";

    $stmt = $conn->prepare($sql);

    if ($params) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $classes = [];
    while ($row = $result->fetch_assoc()) {
        $classes[] = $row;
    }





    $startDate = $_GET['startDate'] ?? date('Y-m-d');
    $endDate   = $_GET['endDate'] ?? date('Y-m-d');

    $year = $startDate;
    $firstYear = explode('-', $year)[0];


    // Format for display
    $datePicker = date('M d, Y', strtotime($startDate)) . ' — ' . date('M d, Y', strtotime($endDate));

    $total = 0;

    foreach ($scoreTypes as $type) {

        $score = $scoreMap[$student['student_id']][$type['score_type_id']] ?? 0;

        $total += $score;
    }

    $average = count($scoreTypes) > 0 ? $total / count($scoreTypes) : 0;

    // grade
    if ($average >= 90) {
        $grade = 'A';
    } elseif ($average >= 80) {
        $grade = 'B';
    } elseif ($average >= 70) {
        $grade = 'C';
    } elseif ($average >= 60) {
        $grade = 'D';
    } else {
        $grade = 'F';
    }

    // status
    if ($average >= 50) {
        $status = 'Pass';
        $badge = 'success';
    } else {
        $status = 'Fail';
        $badge = 'danger';
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

            <?= Navbar($infoSchemaData, $routeAdmin) ?>

            <!-- Main area -->
            <main class="col-10 bg-light">
                <div
                    class="d-flex justify-content-between align-items-center px-2 py-2 bg-white py-md-1 position-sticky top-0 z-3">
                    <div class="title">Welcome to <?php echo $infoSchemaData[0]["name"] ?></div>

                    <div class="dropdown">
                        <button class="d-flex align-items-center border-0 bg-white gap-2" data-bs-toggle="dropdown">
                            <img src="../src/assets/logo.jpg" width="60" height="60" style="border-radius:50%">
                            <div>Username</div>
                        </button>

                        <ul class="dropdown-menu bg-white ">
                            <a href="../auth/signout.php" class="text-decoration-none">
                                <li><button class="dropdown-item">Sign Out</button></li>
                                <li><button class="dropdown-item">Account</button></li>
                            </a>
                        </ul>
                    </div>
                </div>


                <div class="w-100 d-flex mt-3 ms-3 justify-content-between gap-3 flex-wrap dataLoading">
                    <div class="w-100 bg-white shadow px-4 py-3 rounded">
                        <div class="row g-4 pb-5">
                            <form>
                                <div class="">
                                    <?php
                                    $students = [
                                        [
                                            'id' => 1,
                                            'name' => 'Sok Dara',
                                            'class' => 'CH',
                                            'subject' => 'Chiness',
                                            'speaking' => 15,
                                            'listening' => 25,
                                            'reading' => 30,
                                            'grammar' => 15,
                                            'writing' => 0,
                                            'score' => 85
                                        ],
                                        [
                                            'id' => 2,
                                            'name' => 'Chan Ravy',
                                            'class' => 'CH',
                                            'subject' => 'Chiness',
                                            'speaking' => 15,
                                            'listening' => 25,
                                            'reading' => 30,
                                            'grammar' => 15,
                                            'writing' => 5,
                                            'score' => 90
                                        ],
                                        [
                                            'id' => 3,
                                            'name' => 'Kim Long',
                                            'class' => 'CH',
                                            'subject' => 'Chiness',
                                            'speaking' => 15,
                                            'listening' => 25,
                                            'reading' => 30,
                                            'grammar' => 8,
                                            'writing' => 0,
                                            'score' => 78
                                        ]
                                    ];
                                    ?>


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

                                        <!-- Filter -->
                                        <div class="card shadow-sm border-0 mb-4">
                                            <div class="card-body">

                                                <div class="row g-3">

                                                    <div class="col-md-3">
                                                        <label class="form-label">Teacher</label>

                                                        <select class="form-select">
                                                            <option>All Teachers</option>
                                                            <option>Li Sa</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Academic Year</label>

                                                        <select class="form-select">
                                                            <option>2025-2026</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Class</label>

                                                        <select class="form-select">
                                                            <option>CH</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <label class="form-label">Subject</label>

                                                        <select class="form-select">
                                                            <option>Chiness</option>
                                                        </select>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>



                                        <!-- Table -->
                                        <div class="card shadow-sm border-0">

                                            <div class="card-body p-0">

                                                <form method="POST" action="save_scores.php">

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
                                                                    <th>Grade</th>
                                                                    <th>Status</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>

                                                                <?php foreach ($students as $index => $student): ?>

                                                                    <?php
                                                                    $grade = 'F';
                                                                    $status = 'Fail';
                                                                    $badge = 'danger';

                                                                    if ($student['score'] >= 90) {
                                                                        $grade = 'A';
                                                                        $status = 'Excellent';
                                                                        $badge = 'success';
                                                                    } elseif ($student['score'] >= 80) {
                                                                        $grade = 'B';
                                                                        $status = 'Good';
                                                                        $badge = 'primary';
                                                                    } elseif ($student['score'] >= 70) {
                                                                        $grade = 'C';
                                                                        $status = 'Passed';
                                                                        $badge = 'warning';
                                                                    }
                                                                    ?>

                                                                    <tr>

                                                                        <td><?= $index + 1 ?></td>

                                                                        <td class="fw-semibold">
                                                                            <?= htmlspecialchars($student['name']) ?>
                                                                        </td>

                                                                        <td>
                                                                            <?= htmlspecialchars($student['class']) ?>
                                                                        </td>


                                                                        <td>
                                                                            <input
                                                                                type="number"
                                                                                name="speaking[<?= $student['id'] ?>]"
                                                                                value="<?= $student['speaking'] ?>"
                                                                                class="form-control"
                                                                                min="0"
                                                                                max="100">
                                                                        </td>
                                                                        <td>
                                                                            <input
                                                                                type="number"
                                                                                name="listening[<?= $student['id'] ?>]"
                                                                                value="<?= $student['listening'] ?>"
                                                                                class="form-control"
                                                                                min="0"
                                                                                max="100">
                                                                        </td>
                                                                        <td>
                                                                            <input
                                                                                type="number"
                                                                                name="reading[<?= $student['id'] ?>]"
                                                                                value="<?= $student['reading'] ?>"
                                                                                class="form-control"
                                                                                min="0"
                                                                                max="100">
                                                                        </td>
                                                                        <td>
                                                                            <input
                                                                                type="number"
                                                                                name="grammar[<?= $student['id'] ?>]"
                                                                                value="<?= $student['grammar'] ?>"
                                                                                class="form-control"
                                                                                min="0"
                                                                                max="100">
                                                                        </td>

                                                                        <td>
                                                                            <input
                                                                                type="number"
                                                                                name="writing[<?= $student['id'] ?>]"
                                                                                value="<?= $student['writing'] ?>"
                                                                                class="form-control"
                                                                                min="0"
                                                                                max="100">
                                                                        </td>
                                                                        <td>
                                                                            <input
                                                                                type="number"
                                                                                disabled
                                                                                name="scores[<?= $student['id'] ?>]"
                                                                                value="<?= $student['score'] ?>"
                                                                                class="form-control"
                                                                                min="0"
                                                                                max="100">
                                                                        </td>

                                                                        <td>
                                                                            <span class="badge bg-dark">
                                                                                <?= $grade ?>
                                                                            </span>
                                                                        </td>

                                                                        <td>
                                                                            <span class="badge bg-<?= $badge ?>">
                                                                                <?= $status ?>
                                                                            </span>
                                                                        </td>

                                                                    </tr>

                                                                <?php endforeach; ?>

                                                            </tbody>

                                                        </table>

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
                            </form>
                        </div>
                    </div>
            </main>
        </div>
        <script src="../../script.js"></script>

        <script>
            let startDate = "<?= $startDate ?>";
            let endDate = "<?= $endDate ?>";
            let selectYear = "<?= $selectedYear ?>";
            let selectClass = "<?= $selectedClass ?>";
        </script>

    </body>

    </html>