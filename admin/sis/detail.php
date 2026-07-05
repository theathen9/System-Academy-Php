<?php
include_once __DIR__ . "/../../config/db.php";
include_once __DIR__ . '/../../data/dbSchemaData.php';
include_once __DIR__ . '/../../data/dataSchema.php.php';

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


$currentPage = 'detail';
$queryString = "?type={$type}&id={$id}";


// $editFolder = basename(__DIR__);


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
    <title>Detail | Empowerment Education English One</title>
    <link rel="icon" type="image/png" href="<?php echo $infoSchemaData[4]["image"] ?>">
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

<body class="container-fluid p-0 ">
    <div class="row g-3">
        <nav class="navBar col-12 col-md-3 col-sm-3 col-lg-2 p-3 vh-100 position-sticky top-0">
            <a href="/system-management/admin/sis/<?= htmlspecialchars($getPat) ?>"
                class="btn btn-outline-secondary w-100 text-start">
                <i class="bi bi-arrow-left"></i> Back
            </a>
            <div class="d-flex gap-1 mb-4 align-items-center align-self-center">

                <img src="<?php echo $infoSchemaData[4]["image"] ?>" width="90" height="90" alt="logo" class="rounded-circle">

            </div>
            <ul class="nav flex-column">
                <ul class="nav flex-column gap-1 w-100">
                    <?php foreach ($ESchemaData as $item): ?>
                        <li class="nav-item">
                            <a href="<?= htmlspecialchars($item['link']) ?>"
                                class="nav-link rounded px-3 py-2 <?= !empty($item['active']) ? 'bg-primary text-white' : 'text-dark'; ?>">
                                <?= htmlspecialchars($item['title']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>

                </ul>

            </ul>
        </nav>

        <!-- Main area -->
        <main class="col-12 col-md-6 col-lg-9 col-sm-12 bg-light">
            <div
                class="d-flex justify-content-between align-items-center px-2 py-2 bg-white py-md-1 position-sticky top-0 ">
                <div class="title">Welcome to <?php echo $infoSchemaData[0]["name"] ?></div>

                <div class="dropdown">
                    <button class="d-flex align-items-center border-0 bg-white gap-2" data-bs-toggle="dropdown">
                        <img src="../src/assets/logo.jpg" width="60" height="60" style="border-radius:50%">
                        <div>Username</div>
                    </button>

                    <ul class="dropdown-menu bg-white">
                        <a href="../auth/signout.php" class="text-decoration-none">
                            <li><button class="dropdown-item">Sign Out</button></li>
                            <li><button class="dropdown-item">Account</button></li>
                        </a>
                    </ul>
                </div>
            </div>

            <div class="container-lg container-md container-sm p-3">
            </div>
        </main>
    </div>
</body>

</html>