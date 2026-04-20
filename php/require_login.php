<!-- This code is used to require the login for the admin pages. Note that it starts the session. -->
<!-- To figure this out I looked up "Using PHP to require login" since we used Flask to do this in Software Engineering.
The Google AI showed me this example which helped:
<?php
// auth_check.php
session_start();

// Redirect to login if the user is not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit(); // Always call exit after header() to stop script execution
}
?>

I used this for my stuff and also to make sure that I did NOT start the session again. -->
<?php 
session_start();

if (!isset($_SESSION["loggedIn"])) {
    header("Location: php/admin_login.php");
    exit();
}
?>