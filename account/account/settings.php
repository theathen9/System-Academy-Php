<?php
include_once __DIR__ . '/../../data/dataSchema.php';
include_once __DIR__ . '/../../config/bootstrap.php';
// include_once __DIR__ . '/../../components/Navbar.php';
// include_once __DIR__ . '/../../components/Avata.php';

$userId = checkAuth();
if (!$userId) {
    header("Location: " . BASE_URL . "/auth/signin.php");
    exit;
}
authorizeRole('accountant');


$db = new DB($conn);
$userCRUD = new ORM($db, 'tblUsers u', 'user_id');

$userData = $userCRUD
    ->select("u.username, u.email, e.phone1, e.phone2, r.role_name")
    ->join("tblRoles r", "r.role_id = u.role_id")
    ->join("tblEmployees e", "e.employee_id = u.reference_id")
    ->where("u.user_id", "=", $userId)
    ->first();



?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings | <?php echo $infoSchemaData[1]["name_short"] ?></title>
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
    <link rel="stylesheet" href="../../../src/style.css">

</head>

<div class="container-fluid p-4">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-0">
                <i class="bi bi-gear-fill me-2"></i> Settings
            </h3>
            <small class="text-muted">Manage your account and system configuration</small>


        </div>
    </div>

    <!-- TABS -->
    <ul class="nav nav-pills gap-2 mb-3" id="settingsTab">

        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#profile">
                <i class="bi bi-person me-1"></i> Profile
            </button>
        </li>

        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#security">
                <i class="bi bi-shield-lock me-1"></i> Security
            </button>
        </li>

    
        <!-- BACK BUTTON -->
        <li class="nav-item">
            <a href="<?= BASE_URL ?>/admin/dashboard.php" class="nav-link text-danger">
                <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
            </a>
        </li>

    </ul>

    <!-- CONTENT -->
    <div class="tab-content">

        <!-- ================= PROFILE ================= -->
        <div class="tab-pane fade show active" id="profile">

            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <form method="POST" enctype="multipart/form-data">

                        <div class="row g-4">

                            <!-- Avatar -->
                            <div class="col-md-4 text-center border-end">

                                <img src="../../src/assets/default-user.png"
                                    class="rounded-circle border mb-3"
                                    width="120" height="120">

                                <input type="file" class="form-control" name="profile_image">

                                <small class="text-muted d-block mt-2">
                                    JPG, PNG up to 2MB
                                </small>

                            </div>

                            <!-- Info -->
                            <div class="col-md-8">

                                <div class="mb-3">
                                    <label class="form-label">Username</label>
                                    <input type="text" class="form-control"
                                        name="username"
                                        value="<?= htmlspecialchars($userData['username'] ?? '') ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control"
                                        name="email"
                                        value="<?= htmlspecialchars($userData['email'] ?? '') ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Phone 1</label>
                                    <input type="text" class="form-control"
                                        name="phone1"
                                        value="<?= htmlspecialchars($userData['phone1'] ?? '') ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Phone 2</label>
                                    <input type="text" class="form-control"
                                        name="phone2"
                                        value="<?= htmlspecialchars($userData['phone2'] ?? '') ?>">
                                </div>

                                <div class="text-end">
                                    <button class="btn btn-primary">
                                        Save Profile
                                    </button>
                                </div>

                            </div>

                        </div>

                    </form>

                </div>
            </div>

        </div>

        <!-- ================= SECURITY ================= -->
        <div class="tab-pane fade" id="security">

            <div class="card shadow-sm border-0">
                <div class="card-body">

                    <h5 class="mb-3">Change Password</h5>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <input type="password" class="form-control" name="current_password">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <input type="password" class="form-control" name="new_password">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" name="confirm_password">
                        </div>

                        <button class="btn btn-success">
                            Update Password
                        </button>

                    </form>

                </div>
            </div>

        </div>

    </div>

</div>