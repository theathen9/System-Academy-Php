<?php
include "../data/dataShema.php";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Empowerment Education English One</title>
    <link rel="icon" type="image/png" href="../src/assets/icon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.13.1/font/bootstrap-icons.min.css"
        integrity="sha512-t7Few9xlddEmgd3oKZQahkNI4dS6l80+eGEzFQiqtyVYdvcSG2D3Iub77R20BdotfRPA9caaRkg1tyaJiPmO0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../src/style.css">

</head>

<body class="container-fluid p-0 ">
    <div class="row g-3">

        <!-- Sidebar -->
        <nav class="navBar col-12 col-md-3 col-sm-3 col-lg-2 p-3 vh-100 position-sticky top-0 ">
            <div class="d-flex gap-1 mb-4 align-items-center align-self-center">
                <img src="../src/assets/logo.jpg" width="60" height="60" alt="logo" class="rounded-circle">
                <div class="title">
                    <p class="m-auto">Empowerment <br>Education English One</p>
                </div>
            </div>
            <ul class="nav flex-column">
                <!-- Dashboard -->
                <li class="nav-item mb-1">

                    <a href="#" class="nav-link  text-white bg-primary rounded">
                        <i class="bi bi-speedometer2 me-1"></i>Dashboard</a>
                </li>

                <!-- Institute with submenu -->
                <li class="nav-item mb-1">
                    <a class="nav-link text-dark rounded d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" href="#instituteSubmenu" role="button" aria-expanded="false">
                        Institute
                        <span class="bi bi-chevron-down"></span>
                        <!-- Optional icon -->
                    </a>
                    <ul class="nav collapse flex-column ms-3" id="instituteSubmenu">
                        <li class="nav-item mb-1 w-100">
                            <a href="./institute/departments.php" class="nav-link text-dark rounded">Departments</a>
                        </li>
                        <li class="nav-item mb-1 w-100">
                            <a href="#" class="nav-link text-dark rounded">Teachers</a>
                        </li>
                        <li class="nav-item mb-1">
                            <a href="#" class="nav-link text-dark rounded">Students</a>
                        </li>
                    </ul>
                </li>

                <!-- Attendance -->
                <li class="nav-item mb-1">
                    <a class="nav-link text-dark rounded d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" href="#attendanceSubmenu" role="button" aria-expanded="false">
                        Attendance
                        <span class="bi bi-chevron-down"></span>
                        <!-- Optional icon -->
                    </a>
                    <ul class="nav collapse flex-column ms-3 " id="attendanceSubmenu">
                        <li class="nav-item mb-1 w-100">
                            <a href="./Dashboard.php" class="nav-link text-dark rounded">Dashboard</a>
                        </li>
                        <li class="nav-item mb-1 w-100">
                            <a href="#" class="nav-link text-dark rounded">Attendance</a>
                        </li>

                    </ul>
                </li>

                <!-- Examination with submenu -->
                <li class="nav-item mb-1 ">
                    <a class="nav-link text-dark rounded d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" href="#examSubmenu" role="button" aria-expanded="false">
                        Examination
                        <span class="bi bi-chevron-down"></span>
                    </a>
                    <ul class="nav collapse flex-column ms-3 " id="examSubmenu">
                        <li class="nav-item mb-1 w-100">
                            <a href="#" class="nav-link text-dark rounded ">Schedule</a>
                        </li>
                        <li class="nav-item mb-1 w-100">
                            <a href="#" class="nav-link text-dark rounded ">Results</a>
                        </li>
                    </ul>
                </li>

                <!-- Schedule with submenu -->
                <li class="nav-item mb-1 ">
                    <a class="nav-link text-dark rounded d-flex justify-content-between align-items-center"
                        data-bs-toggle="collapse" href="#scheduleSubmenu" role="button" aria-expanded="false">
                        Schedule
                        <span class="bi bi-chevron-down"></span>
                    </a>
                    <ul class="nav collapse flex-column ms-3 " id="scheduleSubmenu">
                        <li class="nav-item mb-1 w-100">
                            <a href="#" class="nav-link text-dark rounded ">Schedule</a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item mb-1">
                    <a href="#" class="nav-link text-dark rounded">Billing</a>
                </li>
                <li class="nav-item mb-1">
                    <a href="#" class="nav-link text-dark rounded">Accounts</a>
                </li>
            </ul>

        </nav>


        <!-- Main area -->
        <main style="height: 1000px;" class="col-12 col-md-6 col-lg-9 bg-light">
            <div
                class="d-flex justify-content-between align-items-center px-2 py-2 bg-white py-md-1 position-sticky top-0 ">
                <div class="app"><img src="../src/assets/logo.jpg" alt="" srcset=""></div>
                <ul class="list-unstyled m-0 p-0">
                    <li>
                        <a class="text-decoration-none text-black" data-bs-toggle="collapse" data-bs-target="#myMenu"
                            aria-expanded="false" aria-controls="myMenu">
                            <i class="bi bi-list fs-2"></i>
                        </a>
                    </li>
                </ul>
                <!-- Collapsible Menu -->
                <div class="collapse" id="myMenu">
                    <ul class="list-unstyled bg-light p-3">
                        <li><a href="#" class="text-black text-decoration-none">Dashboard</a></li>
                        <li><a href="#" class="text-black text-decoration-none">Departments</a></li>
                        <li><a href="#" class="text-black text-decoration-none">Teachers</a></li>
                    </ul>
                </div>




                <div class="title">Welcome to <?php echo $infoShemaData[0]["name"] ?></div>

                <div class="dropdown">
                    <button class="d-flex align-items-center border-0 bg-white gap-2" data-bs-toggle="dropdown">
                        <img src="../src/assets/logo.jpg" width="60" height="60" style="border-radius:50%">
                        <div>Username</div>
                    </button>

                    <ul class="dropdown-menu">
                        <a href="../auth/signout.php" class="text-decoration-none">
                            <li><button class="dropdown-item">Sign Out</button></li>
                        </a>
                    </ul>
                </div>

            </div>
    </div>



    <div class="container-lg container-md container-sm  p-4">
        <div>a</div>
    </div>
    </main>
    </div>
</body>

</html>