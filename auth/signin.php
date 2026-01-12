<?php
// include "../config/db.php";
session_start();
$success = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = trim($_POST["username"]);
    $pass = trim($_POST["password"]);
    $id = trim($_POST["id"]);

    /* ---------- 2. Prepared SELECT ---------- */
    $stmt = $conn->prepare(
        "SELECT id, username, password, role, email FROM users WHERE username = ? or id=? LIMIT 1"
    );
    $stmt->bind_param("si", $user, $user);
    $stmt->execute();
    $result = $stmt->get_result();

    /* ---------- 3. Check username / password ---------- */
    if ($result && $row = $result->fetch_assoc()) {

        // ❗ Plain‑text version (matches your current table):
        $passwordIsValid = ($pass === $row["password"]);

        // ✅ Recommended secure version (when you store hashed passwords):
        // $passwordIsValid = password_verify($pass, $row["password"]);

        if ($passwordIsValid) {
            /* ---------- 4. Save session data ---------- */
            $_SESSION["loggedin"] = true;
            $_SESSION["user_id"]  = $row["id"];
            $_SESSION["c_user"] = $row["username"];
            $_SESSION["role"]     = $row["role"];
            $_SESSION["email"]    = $row["email"];
            echo $_SESSION["c_user"];

            /* ---------- 5. Redirect based on role ---------- */
            if ($row["role"] === "Admin" ) {
                header("Location: ../admin/dashboard.php");
            } elseif ($row["role"==="Teacher"]) {
                header("Location: ../teacher/dashboard.php");
            }else {
                header("Location: ../Student/dashboard.php");
            }
            exit;
        }
    }

    $error = "Invalid username or password!";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign in | Empowerment Education English One></title>
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
                            <h5 class="fw-bold mb-3  text-center ">Empowerment Education English One</h5>
                            <form action="../admin/dashboard.php" method="post" class="m-3 " autocomplete="off">
                                <div class="form-floating mb-3">
                                    <input type="text" class="form-control" id="floatingInput" placeholder="Username"
                                        name="username">
                                    <label for="floatingInput">Username</label>
                                </div>
                                <div class="form-floating mb-3">
                                    <input type="password" class="form-control" id="floatingPassword"
                                        placeholder="Password" name="password">
                                    <label for="floatingPassword">Password</label>
                                </div>

                                <div class="d-grid">
                                    <button class="btn btn-primary btn-login text-uppercase fw-bold" type="submit">Sign
                                        in</button>
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