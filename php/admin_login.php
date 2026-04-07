<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to database
$servername = "localhost";
$username = "root";
$password = "mysql";
$dbname = "admins_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    $_SESSION["flash"] = [
        "text" => "Database connection failed.",
        "type" => "error"
    ];
    header("Location: ../admin_login.php");
    exit();
}

// If the login button is pressed
if (isset($_POST["login_btn"])) {
    $name = filter_input(INPUT_POST, $_POST["username"],
                         FILTER_SANITIZE_SPECIAL_CHARS);
    $pass = $_POST["password"];

    // Find the user by username
    // source: https://www.youtube.com/watch?v=LiomRvK7AM8
    $result = $conn->query("SELECT * FROM admins WHERE username = '$name'");
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (hash("sha256", $pass) == $user["password"]) {
            $_SESSION["name"] = $user["name"];
            header("Location: ./admin.php");
            exit();
        }
    }

    $_SESSION["login_error"] = "Incorrect email or password";
    header("Location: ./admin_login.php");
    exit();
}

// source: https://www.youtube.com/watch?v=LiomRvK7AM8
$errors = ["login" => $_SESSION["login_error"] ?? ""];

session_unset();

function showError($error) {
    return !empty($errors) ? "<p class='error'>$error</p>" : "";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="./style.css" \
    type="text/css">
    <link rel="stylesheet" href="./admin.css" \
    type="text/css">
    <link rel="icon" href="./favicon.ico" \
    type="image/x-icon">
</head>

<body>
    <main>
        <aside>
            <img src="./pictures/bell_tower.jpg" alt="Concord Bell Tower">
        </aside>
        <section class="admin-box" id="login-form">
            <h1 class="logo">Admin Login</h1>
            <form id="login_form" action="./admin_login.php" method="post">
                <?= showError($errors["login"]); ?>
                <div class="input_box">
                    <input id="username_input" name="username" \
                    type="text" placeholder="username" required>
                </div>
                <div class="input_box">
                    <input id="password_input" name="password" \
                    type="password" placeholder="password" required>
                </div>
                <div>
                    <button type="submit" class="btn" name="login_btn">
                        log in
                    </button>
                </div>
            </form>
        </section>
    </main>
</body>

</html>

