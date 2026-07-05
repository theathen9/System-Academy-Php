<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include_once __DIR__ . "/../../config/db.php";
include_once __DIR__ . '/../../data/dbSchemaData.php';
include_once __DIR__ . '/../../components/Avatar.php';

$currentPage = 'edit';
require_once __DIR__ . '/../../data/dataSchema.php';

/* 1️⃣ Validate input */
if (
    !isset($_GET['type'], $_GET['id']) ||
    !in_array($_GET['type'], ['teacher', 'student', 'employee']) ||
    !is_numeric($_GET['id'])
) {
    echo "<div class='alert alert-danger'>Invalid request</div>";
    exit;
}

$type = $_GET['type'];
$id   = (int) $_GET['id'];


$queryString = "?type={$type}&id={$id}";


$getPat;

if ($type == "teacher") {
    $getPat = "teacher";
} elseif ($type == "employee") {
    $getPat = "employees";
} else {
    $getPat = "student";
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edite | <?= htmlspecialchars($infoSchemaData[1]["name_short"]) ?></title>
    <link rel="icon" type="image/png" href="<?= $infoSchemaData[5]["image"] ?>">
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
    <script src="/system-management/src/assets/js/user-profile.js"></script>


</head>

<body class="container-fluid p-0 overflow-x-hidden">
    <div class="row g-3">
        <nav class="navBar col-12 col-md-3 col-lg-2 p-3">
            <a href="/system-management/admin/institute/<?= htmlspecialchars($getPat) ?>"
                class="btn btn-outline-secondary w-100 text-start">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            <div class="d-flex gap-1 mb-4 mt-4 align-items-center align-self-center">

                <img
                    src="/system-management/src/assets/default-user.png"
                    width="90"
                    height="90"
                    alt="Profile"
                    class="rounded-circle">

            </div>
            <ul class="nav flex-column gap-1 w-100">
                <?php foreach ($staffSchemaData as $item): ?>
                    <li class="nav-item">
                        <a href="<?= htmlspecialchars($item['link']) ?>"
                            class="nav-link rounded px-3 py-2 <?= !empty($item['active']) ? 'bg-primary text-white' : 'text-dark'; ?>">
                            <?= htmlspecialchars($item['title']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <!-- Main Area -->
        <main class="col-12 col-md-9 col-lg-10 bg-light min-vh-100">

            <!-- Header -->
            <div class="bg-white shadow-sm sticky-top z-3">
                <div class="d-flex justify-content-between align-items-center px-4 py-3">

                    <div>
                        <h5 class="mb-0 fw-semibold">
                            Edit <?= ucfirst($type) ?> Information
                        </h5>
                        <small class="text-muted">
                            Update and manage selected record details
                        </small>
                    </div>

                    <?php Avatar($_SESSION['role']); ?>
                </div>
            </div>

            <!-- Content -->
            <div class="container-fluid px-4 py-4">

                <!-- Breadcrumb -->
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div class="text-muted small">
                        Dashboard / <?= ucfirst($type) ?> / Edit
                    </div>

                    <a href="/system-management/admin/institute/<?= htmlspecialchars($getPat) ?>"
                        class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>

                </div>

                <!-- Form Card -->
                <div class="card border-0 shadow-sm">

                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="mb-0 fw-semibold">Basic Information</h6>
                    </div>

                    <div class="card-body">

                        <form method="POST" action="" class="row g-3">

                            <!-- Full Name -->
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Enter full name">
                            </div>

                            <!-- Email -->
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Enter email address">
                            </div>

                            <!-- Phone -->
                            <div class="col-md-6">
                                <label class="form-label">Phone Number</label>
                                <input type="text" class="form-control" name="phone" placeholder="Enter phone number">
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>

                            <!-- Actions -->
                            <div class="col-12 d-flex justify-content-end gap-2 mt-3">

                                <button type="reset" class="btn btn-light border">
                                    Reset
                                </button>

                                <button type="submit" class="btn btn-primary px-4">
                                    Save Changes
                                </button>

                            </div>

                        </form>

                    </div>
                </div>

            </div>
        </main>
    </div>
</body>

</html>