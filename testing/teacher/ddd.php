<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive UI</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">

    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-md navbar-dark bg-black fixed-top shadow-sm">
        <div class="container-fluid px-3">

            <!-- Brand -->
            <a class="navbar-brand fw-bold" href="#">
                <i class="bi bi-box"></i> MyApp
            </a>

            <!-- Toggle (Mobile) -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#mainNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Menu -->
            <div class="collapse navbar-collapse" id="mainNavbar">
                <ul class="navbar-nav ms-auto gap-2">

                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="bi bi-house"></i> Home
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-grid"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <i class="bi bi-person"></i> Profile
                        </a>
                    </li>

                </ul>
            </div>

        </div>
    </nav>

    <!-- Page Content -->
    <main class="container-fluid pt-5 mt-4">
        <div class="row p-3">
            <div class="col-12 col-md-6 col-lg-4 mb-3">
                <div class="card shadow-sm rounded-4">
                    <div class="card-body">
                        <h5 class="card-title">Card Title</h5>
                        <p class="card-text text-muted">
                            Mobile-first UI design with Bootstrap.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

</body>

</html>