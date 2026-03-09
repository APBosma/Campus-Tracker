<?php

// source: https://www.youtube.com/watch?v=LiomRvK7AM8
session_start();

$errors = ["login" => $_SESSION["login_error"] ?? ""];

session_unset();

function showError($error) {
    return !empty($errors) ? "<p class='error-message'>$error</p>" : "";
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="./admin_login_style.css" \
    type="text/css">
</head>

<body>
    <div class="login-block">
        <h1 class="logo">Admin Login</h1>
        <form id="login_form" action="login.php" method="post">
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
                <label>
                    <input type="checkbox" id="remember_checkbox">
                     remember me
                    </label>
                <button type="submit" class="btn" name="login_btn">
                    log in
                </button>
            </div>
        </form>
    </div>
    <script src="script.js"></script>
</body>

</html>