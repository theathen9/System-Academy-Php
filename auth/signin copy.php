<?php
// ./auth/signin.php
session_start();
date_default_timezone_set('Asia/Phnom_Penh');

include "../config/db.php";
include "../config/app.php";
include "../data/dataSchema.php";

// Redirect based on role
function redirectByRole($role_id)
{
    switch ($role_id) {
        case 1:
            header("Location: ../admin/dashboard.php");
            break;
        case 2:
            header("Location: ../account/dashboard.php");
            break;
        case 3:
            header("Location: ../teacher/dashboard.php");
            break;
        default:
            header("Location: ../student/dashboard.php");
    }
    exit;
}

// ---------------------- TOKEN REFRESH ----------------------
if (isset($_COOKIE['access_token'])) {

    $accessTokenCookie = $_COOKIE['access_token'];
    $hashedToken = hash('sha256', $accessTokenCookie);

    $stmt = $conn->prepare("
        SELECT user_id, role_id, access_expiry, refresh_token, refresh_expiry
        FROM tblUsers
        WHERE access_token = ?
        LIMIT 1
    ");
    $stmt->bind_param("s", $hashedToken);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    if ($row) {
        $now = new DateTime('now', new DateTimeZone('Asia/Phnom_Penh'));

        // Access still valid
        if (new DateTime($row['access_expiry']) < $now) {
            header("Location: signin.php");
            exit;
        }

        // Access expired → try refresh
        if (isset($_COOKIE['refresh_token'])) {
            $refreshTokenCookie = $_COOKIE['refresh_token'];
            if (
                hash('sha256', $refreshTokenCookie) === $row['refresh_token'] &&
                new DateTime($row['refresh_expiry']) > $now
            ) {
                // ✅ Generate new tokens
                $newAccessToken  = bin2hex(random_bytes(32));
                $newRefreshToken = bin2hex(random_bytes(64));
                $hashedAccess    = hash('sha256', $newAccessToken);
                $hashedRefresh   = hash('sha256', $newRefreshToken);

                $accessExpiry  = (clone $now)->modify('+1 minute')->format('Y-m-d H:i:s');
                $refreshExpiry = (clone $now)->modify('+5 minutes')->format('Y-m-d H:i:s');

                $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
                $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

                // Update DB
                $update = $conn->prepare("
                    UPDATE tblUsers 
                    SET access_token=?, refresh_token=?, access_expiry=?, refresh_expiry=?, user_agent=?, ip_address=? 
                    WHERE user_id=?
                ");
                $update->bind_param(
                    "ssssssi",
                    $hashedAccess,
                    $hashedRefresh,
                    $accessExpiry,
                    $refreshExpiry,
                    $userAgent,
                    $ipAddress,
                    $row['user_id']
                );
                $update->execute();

                // Session
                $_SESSION['loggedin'] = true;
                $_SESSION["user_id"] = $row["user_id"];
                $_SESSION["role_id"] = $row["role_id"];


                $userId = $_SESSION["user_id"];
                $signature = hash_hmac('sha256', $userId, APP_SECRET);

                $value = $userId . '.' . $signature;

                setcookie("c_user", $value, [
                    'expires' => strtotime($accessExpiry),
                    'path' => '/',
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);

                // Update cookies
                setcookie("access_token", $newAccessToken, [
                    'expires' => strtotime($accessExpiry),
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);

                setcookie("refresh_token", $newRefreshToken, [
                    'expires' => strtotime($refreshExpiry),
                    'path' => '/',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'Strict'
                ]);

                redirectByRole($row['role_id']);
            } else {
                // ❌ Refresh failed → logout
                setcookie("access_token", "", time() - 3600, "/");
                setcookie("refresh_token", "", time() - 3600, "/");
                session_destroy();
                header("Location: signin.php");
                exit;
            }
        }
    }
}

// ---------------------- LOGIN POST ----------------------
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user = trim($_POST["username"]);
    $pass = trim($_POST["password"]);

    if (empty($user) || empty($pass)) {
        $error = "Username or ID and Password are required!";
    } else {
        $id = intval($user);
        $stmt = $conn->prepare("
            SELECT u.user_id, u.username, u.password, u.role_id, r.role_name, u.email
            FROM tblUsers u
            LEFT JOIN tblRoles r ON u.role_id = r.role_id
            WHERE u.username=? OR u.user_id=? OR u.email=?
            LIMIT 1
        ");
        $stmt->bind_param("sis", $user, $id, $user);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();

        if ($row && password_verify($pass, $row['password'])) {

            session_regenerate_id(true);

            $now = new DateTime('now', new DateTimeZone('Asia/Phnom_Penh'));
            $accessToken  = bin2hex(random_bytes(32));
            $refreshToken = bin2hex(random_bytes(64));
            $hashedAccess = hash('sha256', $accessToken);
            $hashedRefresh = hash('sha256', $refreshToken);

            $accessExpiry  = (clone $now)->modify('+1 minute')->format('Y-m-d H:i:s');
            $refreshExpiry = (clone $now)->modify('+5 minutes')->format('Y-m-d H:i:s');

            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

            // Save to DB
            $update = $conn->prepare("
                UPDATE tblUsers
                SET access_token=?, refresh_token=?, access_expiry=?, refresh_expiry=?, user_agent=?, ip_address=?
                WHERE user_id=?
            ");
            $update->bind_param(
                "ssssssi",
                $hashedAccess,
                $hashedRefresh,
                $accessExpiry,
                $refreshExpiry,
                $userAgent,
                $ipAddress,
                $row['user_id']
            );
            $update->execute();

            // Session
            $_SESSION['loggedin'] = true;
            $_SESSION["user_id"] = $row["user_id"];
            $_SESSION["role_id"] = $row["role_id"];

            $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

            $userId = $_SESSION["user_id"];
            $signature = hash_hmac('sha256', $userId, APP_SECRET);

            $value = $userId . '.' . $signature;

            setcookie("c_user", $value, [
                'expires' => strtotime($accessExpiry),
                'path' => '/',
                'httponly' => true,
                'samesite' => 'Strict'
            ]);

            setcookie("access_token", $accessToken, [
                'expires' => strtotime($accessExpiry),
                'path' => '/',
                'secure' => $secure,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);

            setcookie("refresh_token", $refreshToken, [
                'expires' => strtotime($refreshExpiry),
                'path' => '/',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Strict'
            ]);

            redirectByRole($row["role_id"]);
        } else {
            $error = "Invalid username or password!";
        }
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in | <?php echo $infoSchemaData[1]["name_short"] ?></title>
    <link rel="icon" type="image/png" href="../src/assets/icon.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body style="background-image: url(../src/assets/bgSignin.jpg); " class="">
    <div style="background: rgba(0, 0, 0, 0.4);" class="w-100 h-100 vh-100 vw-100 ">
        <div class="container vh-100 ">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-5">
                    <div class="card shadow">
                        <div class="card-body pb-4">
                            <img style="width: 125px;height: 125px" class="d-block mx-auto mb-4"
                                src="../src/assets/logo.jpg" alt="logo">
                            <h5 class="fw-bold mb-3  text-center ">3E ONE</h5>

                            <?php if (!empty($error)): ?>
                                <div class="alert alert-danger text-center">
                                    <?= $error ?>
                                </div>
                            <?php endif; ?>


                            <form method="post" class="m-3 " autocomplete="off">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" placeholder="Username or ID"
                                        name="username">
                                    <label for="floatingInput">Username or ID or Email</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="floatingPassword"
                                        placeholder="Password" name="password">
                                    <label for="floatingPassword">Password</label>
                                </div>

                                <div class="d-grid">
                                    <button class="btn btn-primary btn-login text-uppercase fw-bold" type="submit">
                                        Sign in
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>