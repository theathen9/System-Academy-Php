<?php
require_once __DIR__ . '/../config/bootstrap.php';
// components/Avatar.php
// This component renders a user avatar with a dropdown menu for account actions.
// Usage: Call Avatar($role) where $role is the user's role (e.g., 'admin' or 'user') to display the avatar and dropdown menu.
// role admin,accountant,teacher,student link to settings page
function Avatar($role = 'student')
{
    $role = strtolower($role);
    $accountUrl = match ($role) {
        'admin'   => BASE_URL . '/admin/account/settings.php',
        'accountant' => BASE_URL . '/account/account/settings.php',
        'teacher' => BASE_URL . '/teacher/profile.php',
        'student' => BASE_URL . '/student/profile.php',
        default   => BASE_URL . '/admin/account/settings.php'
    };
?>
    <div class="dropdown">
        <button id="account"
            class="d-flex align-items-center border-0 bg-white gap-2"
            data-bs-toggle="dropdown">

            <img id="profileImg"
                width="60"
                height="60"
                style="border-radius:50%"
                alt="User Avatar">

            <div id="username"></div>
        </button>

        <ul class="dropdown-menu bg-white">

            <li>
                <a href="<?= htmlspecialchars($accountUrl) ?>" class="dropdown-item">
                    Account
                </a>
            </li>

            <li>
                <hr class="dropdown-divider">
            </li>

            <li>
                <a href="<?= BASE_URL ?>/auth/signout.php" class="dropdown-item text-danger">
                    Sign Out
                </a>
            </li>

        </ul>
    </div>
<?php
}
?>