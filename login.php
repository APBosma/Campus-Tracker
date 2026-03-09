<?php

// source: https://www.youtube.com/watch?v=LiomRvK7AM8

session_start();
require_once "config.php";

if (isset($_POST["login_btn"])) {
    $username = $_POST["username"];
    $password = password_hash(
        $_POST["password"], 
        PASSWORD_DEFAULT
    );

    $result = $conn->query(
        "SELECT * FROM users WHERE username = '$username'"
    );
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user["password"])) {
            $_SESSION["username"] = $user["username"];
            // This is where the user will be redirected
            header("Location: admin.php");
            exit();
        }
    }

    $_SESSION["login_error"] = "Incorrect email or password";
    $_SESSION["active_form"] = "login";
    header("Location: admin_login.php");
    exit();
}

?>