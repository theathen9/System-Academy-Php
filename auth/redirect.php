<?php
session_start();

switch ($_SESSION['role']) {
    case 'Admin':
        header("Location: /admin/dashboard.php");
        break;
    case 'Teacher':
        header("Location: /teacher/dashboard.php");
        break;
    case 'Accountant':
        header("Location: /account/dashboard.php");
        break;
    case 'Student':
        header("Location: /student/dashboard.php");
        break;
}
exit();
?>